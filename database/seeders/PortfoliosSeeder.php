<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class PortfoliosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resolve categories from database
        $webDevCategory = Category::where('slug', 'web-development')->first()
            ?? Category::create(['name' => 'Web Development', 'slug' => 'web-development']);

        $seoCategory = Category::where('slug', 'seo')->first()
            ?? Category::create(['name' => 'SEO Optimization', 'slug' => 'seo']);

        $portfolios = [
            [
                'client_name' => 'Acme Corporation',
                'project_title' => 'E-Commerce Traffic Boost & Design Overhaul',
                'slug' => 'ecommerce-traffic-boost',
                'challenge' => "Acme Corporation experienced massive performance drops and drop-offs during customer checkout sessions. Their legacy structure caused load times of over 6.5 seconds, causing low organic visibility and search engines rankings declines.\n\nKey issues included slow database serialization and unoptimized CSS/JS files.",
                'approach' => "We completely refactored their frontend to use Vue 3 & Vite, built custom paginated pages with Laravel backends, and implemented dynamic caching mechanisms to speed up checkout queries.\n\nAdditionally, we optimized SEO metadata fields and dynamic XML sitemaps to rebuild search engine rankings.",
                'results' => "Acme Corporation's load times dropped to under 1.4 seconds. Organic traffic grew by 185%, leading to record-breaking sales conversions and complete user satisfaction.",
                'metrics' => [
                    ['label' => 'Organic Traffic Growth', 'value' => '+185%'],
                    ['label' => 'Sales Conversion Rate', 'value' => '4.2%'],
                    ['label' => 'Load Time Reduction', 'value' => '-5.1s'],
                    ['label' => 'Quarterly Revenue Gain', 'value' => '+$2.4M'],
                ],
                'before_image_path' => '/images/before-ecommerce.jpg',
                'after_image_path' => '/images/after-ecommerce.jpg',
                'gallery' => ['/images/gallery-1.jpg', '/images/gallery-2.jpg', '/images/gallery-3.jpg'],
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'status' => 'published',
                'completed_at' => '2026-03-15',
                'categories' => [$webDevCategory->id, $seoCategory->id],
                'seo' => [
                    'meta_title' => 'Case Study: Acme Corp E-Commerce Transformation - RankQuill',
                    'meta_description' => 'Explore how RankQuill optimized Acme Corp\'s e-commerce platform performance, boosting organic SEO traffic by 185% and increasing sales.',
                ],
            ],
            [
                'client_name' => 'Vanguard Pay',
                'project_title' => 'Fintech Mobile Banking App MVP Launch',
                'slug' => 'fintech-banking-app',
                'challenge' => "Vanguard Pay needed a secure, highly scalable MVP mobile application for iOS & Android to support instant payment pipelines.\n\nTime-to-market was extremely tight, requiring full development within 12 weeks.",
                'approach' => 'We engineered a robust cross-platform hybrid application utilizing Flutter, secured by biometric logins and OAuth credentials, linked to a high-speed Laravel API server.',
                'results' => 'The MVP launched perfectly on schedule on Apple App Store & Google Play. User acquisition hit 150k downloads in 30 days.',
                'metrics' => [
                    ['label' => 'App Store Downloads', 'value' => '150,000+'],
                    ['label' => 'Transaction Latency', 'value' => '<0.4s'],
                    ['label' => 'User Satisfaction Index', 'value' => '4.9/5.0'],
                ],
                'status' => 'published',
                'completed_at' => '2026-04-20',
                'categories' => [$webDevCategory->id],
                'seo' => [
                    'meta_title' => 'Case Study: Vanguard Pay Fintech Mobile App - RankQuill',
                    'meta_description' => 'Read our case study on building a high-performance payment application in 12 weeks for Vanguard Pay.',
                ],
            ],
        ];

        foreach ($portfolios as $pData) {
            $categories = $pData['categories'];
            $seo = $pData['seo'];
            unset($pData['categories'], $pData['seo']);

            $portfolio = Portfolio::where('slug', $pData['slug'])->first();
            if (! $portfolio) {
                $portfolio = Portfolio::create($pData);

                if ($categories) {
                    $portfolio->categories()->sync($categories);
                }

                if ($seo) {
                    $portfolio->updateSeo($seo);
                }
            }
        }
    }
}
