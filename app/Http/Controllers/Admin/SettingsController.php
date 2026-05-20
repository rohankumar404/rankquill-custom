<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Script;
use App\Models\SmtpSetting;
use App\Models\SocialLink;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Settings', [
            'settings' => [
                'general' => $this->settingsService->group('general'),
                'contact' => $this->settingsService->group('contact'),
                'theme' => $this->settingsService->group('theme'),
                'seo' => $this->settingsService->group('seo'),
            ],
            'smtp' => SmtpSetting::first() ?? new SmtpSetting,
            'scripts' => Script::all(),
            'social_links' => SocialLink::orderBy('order')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            // General Settings
            'general.site_name' => 'required|string|max:255',
            'general.site_tagline' => 'nullable|string|max:255',
            'general.maintenance_mode' => 'required|in:true,false,1,0',
            'logo' => 'nullable|image|max:2048',

            // Contact Settings
            'contact.contact_email' => 'nullable|email|max:255',
            'contact.contact_phone' => 'nullable|string|max:50',
            'contact.contact_address' => 'nullable|string|max:500',

            // Theme Settings
            'theme.primary_color' => 'nullable|string|max:7',
            'theme.secondary_color' => 'nullable|string|max:7',
            'theme.accent_color' => 'nullable|string|max:7',
            'theme.font_primary' => 'nullable|string|max:100',
            'theme.font_secondary' => 'nullable|string|max:100',

            // SEO Settings
            'seo.default_seo_title' => 'required|string|max:255',
            'seo.default_seo_description' => 'required|string|max:1000',
            'seo.default_seo_keywords' => 'nullable|string|max:1000',
            'seo.default_seo_robots' => 'nullable|string|max:255',

            // SMTP Settings
            'smtp.host' => 'required|string',
            'smtp.port' => 'required|integer',
            'smtp.username' => 'nullable|string',
            'smtp.password' => 'nullable|string',
            'smtp.encryption' => 'nullable|string',
            'smtp.from_address' => 'required|email',
            'smtp.from_name' => 'required|string',
            'smtp.is_active' => 'required|boolean',

            // Scripts & Social Links arrays
            'scripts' => 'nullable|array',
            'scripts.*.id' => 'nullable|integer',
            'scripts.*.name' => 'required|string',
            'scripts.*.placement' => 'required|in:head,body_start,body_end',
            'scripts.*.code' => 'required|string',
            'scripts.*.is_active' => 'required|boolean',
            'scripts.*.requires_consent' => 'required|boolean',

            'social_links' => 'nullable|array',
            'social_links.*.id' => 'nullable|integer',
            'social_links.*.platform_name' => 'required|string',
            'social_links.*.url' => 'required|url',
            'social_links.*.icon_svg' => 'nullable|string',
            'social_links.*.order' => 'required|integer',
            'social_links.*.is_active' => 'required|boolean',
        ]);

        // 1. General Settings update
        $generalData = $request->input('general', []);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $generalData['logo_url'] = asset('storage/'.$path);
        } elseif ($request->filled('general.logo_url')) {
            $generalData['logo_url'] = $request->input('general.logo_url');
        }

        $this->settingsService->setMany($generalData, 'general');

        // 2. Contact Settings update
        if ($request->has('contact')) {
            $this->settingsService->setMany($request->input('contact'), 'contact');
        }

        // 3. Theme Settings update
        if ($request->has('theme')) {
            $this->settingsService->setMany($request->input('theme'), 'theme');
        }

        // 4. SEO Settings update
        if ($request->has('seo')) {
            $this->settingsService->setMany($request->input('seo'), 'seo');
        }

        // 5. SMTP Settings update
        $smtpData = $request->input('smtp', []);
        // Only update password if a new one is provided
        if (empty($smtpData['password'])) {
            unset($smtpData['password']);
        }
        $this->settingsService->updateSmtp($smtpData);

        // 6. Sync Scripts
        $scriptIds = [];
        if ($request->has('scripts')) {
            foreach ($request->input('scripts', []) as $scriptData) {
                $script = Script::updateOrCreate(
                    ['id' => $scriptData['id'] ?? null],
                    [
                        'name' => $scriptData['name'],
                        'placement' => $scriptData['placement'],
                        'code' => $scriptData['code'],
                        'is_active' => $scriptData['is_active'],
                        'requires_consent' => $scriptData['requires_consent'],
                    ]
                );
                $scriptIds[] = $script->id;
            }
        }
        // Delete scripts not present in payload
        Script::whereNotIn('id', $scriptIds)->delete();

        // 7. Sync Social Links
        $socialLinkIds = [];
        if ($request->has('social_links')) {
            foreach ($request->input('social_links', []) as $socialData) {
                $socialLink = SocialLink::updateOrCreate(
                    ['id' => $socialData['id'] ?? null],
                    [
                        'platform_name' => $socialData['platform_name'],
                        'url' => $socialData['url'],
                        'icon_svg' => $socialData['icon_svg'],
                        'order' => $socialData['order'],
                        'is_active' => $socialData['is_active'],
                    ]
                );
                $socialLinkIds[] = $socialLink->id;
            }
        }
        // Delete social links not present in payload
        SocialLink::whereNotIn('id', $socialLinkIds)->delete();

        // Flush and reload settings cache
        $this->settingsService->clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Global configurations updated successfully.');
    }

    public function testSmtp(Request $request): JsonResponse
    {
        $request->validate([
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'nullable|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
            'test_email' => 'required|email',
        ]);

        try {
            $smtpPassword = $request->password;
            if (empty($smtpPassword)) {
                // Load saved password if none provided in test request
                $saved = SmtpSetting::first();
                $smtpPassword = $saved ? $saved->password : '';
            }

            // Temporarily swap config for the verification mailer
            config([
                'mail.mailers.smtp_test' => [
                    'transport' => 'smtp',
                    'host' => $request->host,
                    'port' => $request->port,
                    'encryption' => $request->encryption,
                    'username' => $request->username,
                    'password' => $smtpPassword,
                    'timeout' => 8,
                ],
            ]);

            Mail::mailer('smtp_test')->raw(
                "RankQuill Global Settings System Connection Check\n\nSMTP configuration is successfully active and operational.",
                function ($message) use ($request) {
                    $message->from($request->from_address, $request->from_name)
                        ->to($request->test_email)
                        ->subject('RankQuill SMTP Test Verification');
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Connection validated. Verification mail sent successfully to: '.$request->test_email,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP verification failed: '.$e->getMessage(),
            ], 500);
        }
    }
}
