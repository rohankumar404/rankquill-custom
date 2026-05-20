<?php

namespace Database\Seeders;

use App\Models\Script;
use App\Models\Setting;
use App\Models\SmtpSetting;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Settings table
        $settings = [
            // General
            [
                'key' => 'site_name',
                'value' => 'RankQuill',
                'group' => 'general',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Premium SEO & Digital Growth Agency',
                'group' => 'general',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'group' => 'general',
            ],
            [
                'key' => 'logo_url',
                'value' => null,
                'group' => 'general',
            ],

            // Contact
            [
                'key' => 'contact_email',
                'value' => 'hello@rankquill.com',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (555) 019-2834',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_address',
                'value' => '123 SEO Boulevard, Suite 500, San Francisco, CA',
                'group' => 'contact',
            ],

            // Theme (Colors & Fonts)
            [
                'key' => 'primary_color',
                'value' => '#6366f1', // Indigo 500
                'group' => 'theme',
            ],
            [
                'key' => 'secondary_color',
                'value' => '#0f172a', // Slate 900
                'group' => 'theme',
            ],
            [
                'key' => 'accent_color',
                'value' => '#f43f5e', // Rose 500
                'group' => 'theme',
            ],
            [
                'key' => 'font_primary',
                'value' => 'Outfit',
                'group' => 'theme',
            ],
            [
                'key' => 'font_secondary',
                'value' => 'Inter',
                'group' => 'theme',
            ],

            // Default SEO
            [
                'key' => 'default_seo_title',
                'value' => 'RankQuill | Premium SEO & Digital Growth Agency',
                'group' => 'seo',
            ],
            [
                'key' => 'default_seo_description',
                'value' => 'We scale brands with data-driven SEO, custom web application architecture, and precision digital marketing.',
                'group' => 'seo',
            ],
            [
                'key' => 'default_seo_keywords',
                'value' => 'seo agency, digital marketing, nextjs development, laravel architecture, growth strategy',
                'group' => 'seo',
            ],
            [
                'key' => 'default_seo_robots',
                'value' => 'index, follow',
                'group' => 'seo',
            ],
            [
                'key' => 'lead_notification_emails',
                'value' => 'hello@rankquill.com,leads@rankquill.com',
                'group' => 'leads',
            ],
            [
                'key' => 'recaptcha_enabled',
                'value' => 'false',
                'group' => 'leads',
            ],
            [
                'key' => 'recaptcha_site_key',
                'value' => null,
                'group' => 'leads',
            ],
            [
                'key' => 'recaptcha_secret_key',
                'value' => null,
                'group' => 'leads',
            ],
            [
                'key' => 'mailchimp_enabled',
                'value' => 'false',
                'group' => 'newsletter',
            ],
            [
                'key' => 'mailchimp_api_key',
                'value' => null,
                'group' => 'newsletter',
            ],
            [
                'key' => 'mailchimp_list_id',
                'value' => null,
                'group' => 'newsletter',
            ],
            [
                'key' => 'brevo_enabled',
                'value' => 'false',
                'group' => 'newsletter',
            ],
            [
                'key' => 'brevo_api_key',
                'value' => null,
                'group' => 'newsletter',
            ],
            [
                'key' => 'brevo_list_id',
                'value' => null,
                'group' => 'newsletter',
            ],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], [
                'value' => $s['value'],
                'group' => $s['group'],
            ]);
        }

        // 2. Seed SmtpSetting
        SmtpSetting::updateOrCreate(
            ['id' => 1],
            [
                'host' => 'smtp.mailtrap.io',
                'port' => 2525,
                'username' => 'test_user',
                'password' => 'test_password',
                'encryption' => 'tls',
                'from_address' => 'no-reply@rankquill.com',
                'from_name' => 'RankQuill',
                'is_active' => true,
            ]
        );

        // 3. Seed SocialLinks
        $socialLinks = [
            [
                'platform_name' => 'Facebook',
                'url' => 'https://facebook.com/rankquill',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'platform_name' => 'Twitter/X',
                'url' => 'https://twitter.com/rankquill',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'platform_name' => 'LinkedIn',
                'url' => 'https://linkedin.com/company/rankquill',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'platform_name' => 'Instagram',
                'url' => 'https://instagram.com/rankquill',
                'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($socialLinks as $link) {
            SocialLink::updateOrCreate(
                ['platform_name' => $link['platform_name']],
                [
                    'url' => $link['url'],
                    'icon_svg' => $link['icon_svg'],
                    'order' => $link['order'],
                    'is_active' => $link['is_active'],
                ]
            );
        }

        // 4. Seed Scripts
        Script::updateOrCreate(
            ['name' => 'Google Analytics'],
            [
                'placement' => 'head',
                'code' => "<!-- Google Tag Manager -->\n<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\nnew Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\nj=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>\n<!-- End Google Tag Manager -->",
                'is_active' => false,
                'requires_consent' => true,
            ]
        );
    }
}
