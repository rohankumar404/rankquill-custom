<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\SeoMeta;
use App\Models\Service;
use Illuminate\Support\Facades\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SeoService
{
    /**
     * Generate the XML sitemap.
     */
    public function generateSitemap(): Sitemap
    {
        $sitemap = Sitemap::create();

        // Core URL
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // Crawl and add models using HasSeo
        $models = [
            Page::class => ['prefix' => '', 'priority' => 0.8],
            Blog::class => ['prefix' => 'blog', 'priority' => 0.7],
            Service::class => ['prefix' => 'services', 'priority' => 0.8],
            Portfolio::class => ['prefix' => 'portfolio', 'priority' => 0.7],
        ];

        foreach ($models as $class => $config) {
            if (class_exists($class)) {
                // Fetch items using standard status conventions
                $items = $class::query();

                if ($class === Blog::class) {
                    $items->with('category');
                }

                if (in_array($class, [Blog::class, Portfolio::class, Page::class])) {
                    $items->where('status', 'published');
                } elseif ($class === Service::class) {
                    $items->where('status', 'active');
                }

                $results = $items->get();

                foreach ($results as $item) {
                    if ($class === Blog::class) {
                        $categorySlug = $item->category ? $item->category->slug : 'uncategorized';
                        $path = '/blog/'.$categorySlug.'/'.$item->slug;
                    } else {
                        $path = $config['prefix']
                            ? '/'.$config['prefix'].'/'.$item->slug
                            : '/'.$item->slug;
                    }

                    $sitemap->add(Url::create($path)
                        ->setLastModificationDate($item->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority($config['priority']));
                }
            }
        }

        return $sitemap;
    }

    /**
     * Generate robots.txt content.
     */
    public function generateRobotsTxt(): string
    {
        $appUrl = config('app.url');

        if (config('app.env') === 'production') {
            return "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\n\nSitemap: {$appUrl}/sitemap.xml";
        }

        return "User-agent: *\nDisallow: /";
    }

    /**
     * Generate breadcrumbs array from current URI.
     */
    public function getBreadcrumbs(): array
    {
        $segments = Request::segments();
        $breadcrumbs = [];
        $url = '';

        $breadcrumbs[] = [
            'label' => 'Home',
            'url' => '/',
        ];

        foreach ($segments as $segment) {
            $url .= '/'.$segment;
            $breadcrumbs[] = [
                'label' => ucwords(str_replace('-', ' ', $segment)),
                'url' => $url,
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Render tags payload directly (mostly for backend tracking or Blade context).
     */
    public function renderTags(?SeoMeta $seo): array
    {
        if (! $seo) {
            return [];
        }

        return [
            'meta_title' => $seo->meta_title,
            'meta_description' => $seo->meta_description,
            'meta_keywords' => $seo->meta_keywords,
            'robots' => $seo->robots,
            'canonical_url' => $seo->canonical_url ?: Request::url(),
            'og_title' => $seo->og_title ?: $seo->meta_title,
            'og_description' => $seo->og_description ?: $seo->meta_description,
            'og_image_url' => $seo->og_image_url,
            'twitter_card' => $seo->twitter_card,
            'twitter_title' => $seo->twitter_title ?: $seo->og_title ?: $seo->meta_title,
            'twitter_description' => $seo->twitter_description ?: $seo->og_description ?: $seo->meta_description,
            'twitter_image' => $seo->twitter_image ?: $seo->og_image_url,
            'schema_markup' => $seo->schema_markup,
        ];
    }
}
