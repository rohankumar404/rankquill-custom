<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Page;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed FAQs
        $faqs = [
            [
                'question' => 'What is RankQuill?',
                'answer' => 'RankQuill is a premium high-performance content management engine and digital agency platform built with Laravel 11, Vue 3, and Tailwind CSS. It is architected for maximum search visibility and high-conversion landing flows.',
                'order' => 1,
            ],
            [
                'question' => 'How does the search visibility optimization work?',
                'answer' => 'We implement semantic entity clustering, machine-readable JSON-LD schemas, breadcrumb architectures, and optimized structured data so your content is readable by both traditional engines like Google and generative answer systems like ChatGPT and Gemini.',
                'order' => 2,
            ],
            [
                'question' => 'Can I customize the styles and layouts?',
                'answer' => 'Yes. RankQuill includes a global settings engine that handles site colors, typography presets, logo media folders, script placements, and layouts from the admin dashboard.',
                'order' => 3,
            ],
            [
                'question' => 'What database systems do you support?',
                'answer' => 'We support standard MySQL out of the box, with Redis caches utilized for fast settings loading and queue systems handling lead notifications.',
                'order' => 4,
            ],
        ];

        $faqIds = [];
        foreach ($faqs as $f) {
            $faq = Faq::create($f);
            $faqIds[] = $faq->id;
        }

        // 2. Seed Team Members
        $team = [
            [
                'name' => 'Sarah Jenkins',
                'position' => 'Chief Technology Officer',
                'bio' => 'Enterprise software architect with 12+ years of experience leading modern Laravel and Inertia systems.',
                'order' => 1,
            ],
            [
                'name' => 'Michael Chen',
                'position' => 'Director of Search & Visibility',
                'bio' => 'Technical search architect specializing in AEO, semantic schema clustering, and knowledge graph engineering.',
                'order' => 2,
            ],
            [
                'name' => 'Elena Rostova',
                'position' => 'Lead Front-end Developer',
                'bio' => 'Vue 3 specialist focused on high-performance components, fluid animations, and Core Web Vitals optimization.',
                'order' => 3,
            ],
            [
                'name' => 'David Vance',
                'position' => 'Director of Conversion Design',
                'bio' => 'UX/UI designer creating immersive brand styles, sticky CTA layers, and sleek dark modes.',
                'order' => 4,
            ],
        ];

        $teamIds = [];
        foreach ($team as $t) {
            $member = TeamMember::create($t);
            $teamIds[] = $member->id;
        }

        // 3. Seed Testimonials
        $testimonials = [
            [
                'client_name' => 'Marcus Aurelius',
                'client_position' => 'Director of Growth',
                'client_company' => 'Acme Corp',
                'testimonial' => 'RankQuill completely transformed our organic conversion funnel. Our website loading speed dropped under 1 second and search rankings rose by 140% in two months.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'client_name' => 'Ada Lovelace',
                'client_position' => 'Technical Director',
                'client_company' => 'Prime Logic',
                'testimonial' => 'The structured data integration and clean JSON-LD layouts make it incredibly easy for AI bots to query our services and cite our publications.',
                'rating' => 5,
                'is_featured' => true,
            ],
        ];

        $testimonialIds = [];
        foreach ($testimonials as $t) {
            $testObj = Testimonial::create($t);
            $testimonialIds[] = $testObj->id;
        }

        // 4. Seed About Us Page
        Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'status' => 'published',
            'content' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'title' => 'We Design Premium Software Systems',
                        'subtitle' => 'Our collective expertise merges modern web frameworks with future-proof search marketing strategies.',
                        'primary_cta_text' => 'Meet the Team',
                        'primary_cta_url' => '#team',
                    ],
                ],
                [
                    'type' => 'content',
                    'data' => [
                        'body' => '<h2>Our Core Principles</h2><p>We believe digital platforms should be beautiful, high-performing, and easily discoverable by search systems. Our team designs responsive platforms from scratch, utilizing the Laravel and Vue ecosystems to ensure optimal stability and scale.</p><p>By prioritizing clean semantic structures and E-E-A-T credentials, we elevate client systems to dominate search results.</p>',
                    ],
                ],
                [
                    'type' => 'team',
                    'data' => [
                        'title' => 'Meet Our Specialists',
                        'member_ids' => $teamIds,
                    ],
                ],
                [
                    'type' => 'testimonials',
                    'data' => [
                        'title' => 'Client Success Stories',
                        'testimonial_ids' => $testimonialIds,
                    ],
                ],
                [
                    'type' => 'cta',
                    'data' => [
                        'title' => 'Let\'s Construct Your Digital Strategy',
                        'subtitle' => 'Schedule a discovery session with our lead architects today.',
                        'button_text' => 'Get in Touch',
                        'button_url' => '/contact-us',
                    ],
                ],
            ],
        ]);

        // 5. Seed Contact Us Page
        Page::create([
            'title' => 'Contact Us',
            'slug' => 'contact-us',
            'status' => 'published',
            'content' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'title' => 'Get In Touch',
                        'subtitle' => 'Connect with our technical strategy team to configure custom builds.',
                        'primary_cta_text' => 'Browse FAQs',
                        'primary_cta_url' => '#faqs',
                    ],
                ],
                [
                    'type' => 'content',
                    'data' => [
                        'body' => '<h3>Work with us</h3><p>Have an upcoming project or scaling concern? Send us an inquiry via our contact systems, or reach out directly via <strong>contact@rankquill.com</strong>.</p>',
                    ],
                ],
                [
                    'type' => 'faqs',
                    'data' => [
                        'title' => 'Common Inquiries',
                        'faq_ids' => $faqIds,
                    ],
                ],
            ],
        ]);
    }
}
