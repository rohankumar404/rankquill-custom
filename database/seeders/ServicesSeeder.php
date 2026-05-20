<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Web Development',
                'slug' => 'web-development',
                'tagline' => 'Next-gen responsive websites',
                'description' => 'We design, build, and deploy premium modern web applications with full performance, responsive designs, and custom backend modules.',
                'price_starting_at' => 1999.00,
                'features' => ['Tailwind CSS Styling', 'Vite & Vue 3 Frontends', 'Laravel Backend Security', 'Indefinite Settings Caches'],
                'pricing' => [
                    [
                        'name' => 'Startup',
                        'price' => 1999,
                        'interval' => 'one-time',
                        'description' => 'Perfect for landing pages and product promotions.',
                        'features' => ['Single page layout', 'Responsive design', 'Basic SEO configuration', '1 month support'],
                    ],
                    [
                        'name' => 'Growth',
                        'price' => 4999,
                        'interval' => 'one-time',
                        'description' => 'Multi-page complete corporate sites.',
                        'features' => ['Up to 10 pages', 'Custom CMS page builders', 'Polymorphic FAQ/Testimonials', '3 months support'],
                    ],
                    [
                        'name' => 'Enterprise',
                        'price' => 9999,
                        'interval' => 'one-time',
                        'description' => 'Tailored large-scale portals.',
                        'features' => ['Unlimited custom pages', 'Full API integrations', 'Multi-tenant settings engines', 'Dedicated architect support'],
                    ],
                ],
                'seo' => [
                    'meta_title' => 'Enterprise Web Development Services - RankQuill',
                    'meta_description' => 'Premium high-performance web development solutions designed to scale your business operations.',
                ],
            ],
            [
                'title' => 'App Development',
                'slug' => 'app-development',
                'tagline' => 'Native iOS & Android mobile applications',
                'description' => 'Premium hybrid and native mobile app architectures using React Native and Flutter with secure offline synchronizations.',
                'price_starting_at' => 4999.00,
                'features' => ['Cross-Platform Apps', 'Offline Sync Capabilities', 'Biometric Authorization APIs', 'App Store Deployments'],
                'pricing' => [
                    [
                        'name' => 'MVP Launch',
                        'price' => 4999,
                        'interval' => 'one-time',
                        'description' => 'Validate your mobile project idea.',
                        'features' => ['Single platform deployment', 'Basic user profiles', 'Push notifications', 'Standard analytics integration'],
                    ],
                    [
                        'name' => 'Standard Scale',
                        'price' => 9999,
                        'interval' => 'one-time',
                        'description' => 'Launch full app experiences on Apple & Google.',
                        'features' => ['iOS & Android native deployment', 'Social logins', 'In-app purchase modules', 'Performance tracking integrations'],
                    ],
                ],
                'seo' => [
                    'meta_title' => 'Custom Mobile App Development Services - RankQuill',
                    'meta_description' => 'Award-winning cross-platform mobile apps for iOS and Android built on secure, scalable cloud architectures.',
                ],
            ],
            [
                'title' => 'SEO Optimization',
                'slug' => 'seo',
                'tagline' => 'Technical search engine indexing scale',
                'description' => 'Complete search engine audit services, organic link buildings, keyphrase optimization, and fast speed sitemaps setup.',
                'price_starting_at' => 999.00,
                'features' => ['Technical Site Audits', 'Competitor keyword mappings', 'XML Sitemap compilation', 'Polymorphic Schema markup'],
                'pricing' => [
                    [
                        'name' => 'Essential SEO',
                        'price' => 999,
                        'interval' => 'month',
                        'description' => 'Establish foundational search presence.',
                        'features' => ['Keyword research (50 terms)', 'On-page metadata audit', 'Google Search Console setup', 'Monthly rank progress reports'],
                    ],
                    [
                        'name' => 'Dominance SEO',
                        'price' => 2499,
                        'interval' => 'month',
                        'description' => 'Outrank key industry competitors.',
                        'features' => ['Advanced competitors mappings', 'Structured markup injection', 'Organic link building campaigns', 'Bi-weekly tracking consultations'],
                    ],
                ],
                'seo' => [
                    'meta_title' => 'Strategic SEO Optimization Services - RankQuill',
                    'meta_description' => 'Drive organic client conversions and ranking dominance with our data-driven technical search engine marketing.',
                ],
            ],
            [
                'title' => 'PPC Campaigns',
                'slug' => 'ppc',
                'tagline' => 'ROI-oriented paid search management',
                'description' => 'Target high-intent customers with tailored Google Adwords, Meta advertising, Retargeting pipelines, and landing page optimizations.',
                'price_starting_at' => 799.00,
                'features' => ['High-Intent Ad Copy', 'Dynamic Retargeting loops', 'Weekly conversion optimization', 'Custom analytics reports'],
                'pricing' => [
                    [
                        'name' => 'Growth PPC',
                        'price' => 799,
                        'interval' => 'month',
                        'description' => 'Drive immediate qualified buyer leads.',
                        'features' => ['Up to $5k monthly spend limit', 'Google Search ads setup', 'Negative keyword lists', 'A/B landing page testing'],
                    ],
                ],
                'seo' => [
                    'meta_title' => 'Pay Per Click (PPC) Paid Advertising Services - RankQuill',
                    'meta_description' => 'Generate instant lead generation pipelines with Google Ads and paid social remarketing campaigns.',
                ],
            ],
            [
                'title' => 'Graphic Design',
                'slug' => 'graphic-design',
                'tagline' => 'Stunning brand identity designs',
                'description' => 'Creative agency vector designs, typography style guides, business collateral assets, and modern interface layout designs.',
                'price_starting_at' => 1499.00,
                'features' => ['Brand Logo Packages', 'CSS color harmonies', 'Print & Business materials', 'UI/UX Design Mockups'],
                'pricing' => [
                    [
                        'name' => 'Identity Starter',
                        'price' => 1499,
                        'interval' => 'one-time',
                        'description' => 'Foundational design rules for early startups.',
                        'features' => ['Logo suite variants', 'Typography stylesheet', 'Core brand color deck', 'Social header dimensions'],
                    ],
                ],
                'seo' => [
                    'meta_title' => 'Creative Graphic & Brand Design Services - RankQuill',
                    'meta_description' => 'Bespoke corporate identity, modern packaging, and user interface designs crafted by our design team.',
                ],
            ],
            [
                'title' => 'AI Solutions',
                'slug' => 'ai-solutions',
                'tagline' => 'State of the art machine integrations',
                'description' => 'Automated customer support assistant agents, document summary API integrations, and vector database embeddings.',
                'price_starting_at' => 3999.00,
                'features' => ['Custom LLM integrations', 'Agentic support widgets', 'Semantic database search', 'Python workflow scripts'],
                'pricing' => [
                    [
                        'name' => 'AI Agent Scaffolding',
                        'price' => 3999,
                        'interval' => 'one-time',
                        'description' => 'Integrate chat assistants into existing support tools.',
                        'features' => ['Assistant widget styling', 'Knowledge base training integration', 'API connection endpoints', '1 month logs analysis'],
                    ],
                ],
                'seo' => [
                    'meta_title' => 'Enterprise AI Solutions & Chat Agents - RankQuill',
                    'meta_description' => 'Automate customer queries and operations using private, secure Large Language Model workflows.',
                ],
            ],
        ];

        foreach ($services as $servData) {
            $seo = $servData['seo'];
            unset($servData['seo']);

            $service = Service::where('slug', $servData['slug'])->first();
            if (! $service) {
                $service = Service::create($servData);

                if ($seo) {
                    $service->updateSeo($seo);
                }
            }
        }
    }
}
