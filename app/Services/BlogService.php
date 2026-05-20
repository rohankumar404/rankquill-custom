<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Str;

class BlogService
{
    /**
     * Parse HTML body to extract <h2> and <h3> tags for Table of Contents (TOC).
     */
    public function generateToc(string $htmlBody): array
    {
        preg_match_all('/<(h2|h3)[^>]*>(.*?)<\/\1>/i', $htmlBody, $matches);

        $toc = [];

        if (! empty($matches[0])) {
            foreach ($matches[2] as $index => $text) {
                $level = strtolower($matches[1][$index]);
                $cleanText = html_entity_decode(strip_tags($text));

                $toc[] = [
                    'text' => $cleanText,
                    'slug' => Str::slug($cleanText),
                    'level' => $level,
                ];
            }
        }

        return $toc;
    }

    /**
     * Find related posts based on shared category and tags.
     */
    public function getRelatedPosts(Blog $blog, int $limit = 3)
    {
        $tagIds = $blog->tags->pluck('id')->toArray();

        return Blog::published()
            ->where('id', '!=', $blog->id)
            ->where(function ($query) use ($blog, $tagIds) {
                $query->where('category_id', $blog->category_id);
                if (! empty($tagIds)) {
                    $query->orWhereHas('tags', function ($q) use ($tagIds) {
                        $q->whereIn('tags.id', $tagIds);
                    });
                }
            })
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Generate dynamic RSS 2.0 feed XML content.
     */
    public function generateRssFeed(): string
    {
        $posts = Blog::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->limit(20)
            ->get();

        $appUrl = config('app.url');
        $siteName = setting('site_name', 'RankQuill');
        $siteTagline = setting('site_tagline', 'SEO & CMS Engine');

        $rss = '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL;
        $rss .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
        $rss .= '  <channel>'.PHP_EOL;
        $rss .= "    <title><![CDATA[{$siteName}]]></title>".PHP_EOL;
        $rss .= "    <description><![CDATA[{$siteTagline}]]></description>".PHP_EOL;
        $rss .= "    <link>{$appUrl}</link>".PHP_EOL;
        $rss .= '    <language>en-us</language>'.PHP_EOL;
        $rss .= '    <pubDate>'.now()->toRssString().'</pubDate>'.PHP_EOL;
        $rss .= '    <atom:link href="'.$appUrl.'/blog/feed" rel="self" type="application/rss+xml" />'.PHP_EOL;

        foreach ($posts as $post) {
            $categorySlug = $post->category ? $post->category->slug : 'uncategorized';
            $link = "{$appUrl}/blog/{$categorySlug}/{$post->slug}";
            $pubDate = $post->published_at ? $post->published_at->toRssString() : $post->created_at->toRssString();

            $rss .= '    <item>'.PHP_EOL;
            $rss .= "      <title><![CDATA[{$post->title}]]></title>".PHP_EOL;
            $rss .= "      <link>{$link}</link>".PHP_EOL;
            $rss .= "      <guid isPermaLink=\"true\">{$link}</guid>".PHP_EOL;
            $rss .= "      <description><![CDATA[{$post->excerpt}]]></description>".PHP_EOL;
            $rss .= "      <pubDate>{$pubDate}</pubDate>".PHP_EOL;
            if ($post->author) {
                $rss .= "      <author><![CDATA[{$post->author->email} ({$post->author->name})]]></author>".PHP_EOL;
            }
            $rss .= '    </item>'.PHP_EOL;
        }

        $rss .= '  </channel>'.PHP_EOL;
        $rss .= '</rss>';

        return $rss;
    }
}
