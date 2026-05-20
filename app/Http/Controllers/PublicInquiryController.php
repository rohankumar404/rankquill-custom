<?php

namespace App\Http\Controllers;

use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PublicInquiryController extends Controller
{
    /**
     * Store a newly submitted contact inquiry.
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Honeypot check (hidden 'website' field)
        if ($request->filled('website')) {
            // Silently discard submission to deceive spam bots
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been received.',
            ]);
        }

        // 2. Validate input
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'source' => 'nullable|string|max:100',
        ];

        // Fetch reCAPTCHA config from settings engine
        $recaptchaEnabled = setting('recaptcha_enabled') === 'true';
        $recaptchaSecret = setting('recaptcha_secret_key');

        if ($recaptchaEnabled && $recaptchaSecret) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // 3. reCAPTCHA Validation
        if ($recaptchaEnabled && $recaptchaSecret) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $recaptchaSecret,
                'response' => $validated['g-recaptcha-response'],
                'remoteip' => $request->ip(),
            ]);

            if (! $response->json('success')) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'recaptcha' => ['reCAPTCHA verification failed. Please try again.'],
                    ],
                ], 422);
            }
        }

        // 4. Capture metadata
        $metadata = [
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'referrer' => $request->header('referer'),
        ];

        // 5. Store Inquiry
        $inquiry = Inquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
            'subject' => $validated['subject'] ?? 'General Contact',
            'message' => $validated['message'],
            'source' => $validated['source'] ?? 'contact_form',
            'metadata' => $metadata,
        ]);

        // 6. Send notification to multiple emails
        $emailsSetting = setting('lead_notification_emails');
        if ($emailsSetting) {
            $recipients = collect(explode(',', $emailsSetting))
                ->map(fn ($email) => trim($email))
                ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
                ->toArray();

            if (! empty($recipients)) {
                try {
                    Mail::to($recipients)->send(new InquiryReceived($inquiry));
                } catch (\Exception $e) {
                    // Log mail transport failures silently so it doesn't crash form submission
                    logger()->error('Failed sending inquiry mail notification: '.$e->getMessage());
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your message has been received.',
        ]);
    }
}
