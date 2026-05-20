<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  blog: {
    type: Object,
    required: true
  },
  toc: {
    type: Array,
    default: () => []
  },
  related: {
    type: Array,
    default: () => []
  }
})

// Format schema markup if present
const schemaJson = props.blog.seo?.schema_markup 
  ? JSON.stringify(props.blog.seo.schema_markup) 
  : null
</script>

<template>
  <Head>
    <title>{{ blog.seo?.meta_title || blog.title }}</title>
    <meta name="description" :content="blog.seo?.meta_description || blog.excerpt || ''" />
    <meta name="keywords" :content="blog.seo?.meta_keywords || blog.focus_keyword || ''" />
    <meta name="robots" :content="blog.seo?.robots || 'index, follow'" />
    <link rel="canonical" :href="blog.seo?.canonical_url || '#'" />
    
    <!-- Open Graph -->
    <meta property="og:title" :content="blog.seo?.og_title || blog.title" />
    <meta property="og:description" :content="blog.seo?.og_description || blog.excerpt || ''" />
    <meta property="og:image" :content="blog.seo?.og_image_url || ''" />
    
    <!-- JSON-LD Schema Markup -->
    <script v-if="schemaJson" type="application/ld+json" v-html="schemaJson"></script>
  </Head>

  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white">
    <!-- Header Navigation -->
    <header class="border-b border-slate-800/80 bg-slate-900/40 backdrop-blur-md sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="text-xl font-bold bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
          RankQuill
        </div>
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
          <a href="/" class="hover:text-indigo-400 transition-colors">Home</a>
          <a href="#" class="hover:text-indigo-400 transition-colors">Services</a>
          <a href="#" class="hover:text-indigo-400 transition-colors">Portfolio</a>
          <Link href="/blog" class="hover:text-indigo-400 transition-colors text-indigo-400">Blog</Link>
        </nav>
        <button class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2.5 rounded-full transition-all duration-300 shadow-lg shadow-indigo-600/20 hover:scale-[1.02]">
          Get Started
        </button>
      </div>
    </header>

    <!-- Article Header -->
    <div class="border-b border-slate-900/60 bg-slate-900/10 py-16">
      <div class="max-w-4xl mx-auto px-6 space-y-6">
        <!-- Category & Read Time -->
        <div class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-indigo-400">
          <Link :href="`/blog/category/${blog.category ? blog.category.slug : 'uncategorized'}`" class="hover:underline">
            {{ blog.category ? blog.category.name : 'Uncategorized' }}
          </Link>
          <span class="w-1.5 h-1.5 rounded-full bg-slate-800"></span>
          <span class="text-slate-500 font-normal normal-case">{{ blog.read_time_minutes }} min read</span>
        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
          {{ blog.title }}
        </h1>

        <p class="text-lg md:text-xl text-slate-400 font-light leading-relaxed">
          {{ blog.excerpt }}
        </p>

        <!-- Author Information -->
        <div class="flex items-center gap-4 pt-4 border-t border-slate-900/60">
          <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center font-bold text-white uppercase shadow-inner">
            {{ blog.author?.name ? blog.author.name.charAt(0) : 'W' }}
          </div>
          <div>
            <div class="font-semibold text-white">By {{ blog.author?.name || 'Staff Writer' }}</div>
            <div class="text-xs text-slate-500">Published on {{ blog.published_at ? new Date(blog.published_at).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'}) : 'Draft' }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Layout -->
    <main class="max-w-7xl mx-auto px-6 py-16 grid lg:grid-cols-4 gap-12">
      <!-- Table of Contents (TOC) Sidebar -->
      <aside v-if="toc && toc.length > 0" class="lg:col-span-1 space-y-8">
        <div class="sticky top-28 border border-slate-800/60 bg-slate-900/20 rounded-2xl p-6">
          <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-800 pb-3">Table of Contents</h2>
          <nav class="space-y-3">
            <a 
              v-for="(heading, i) in toc" 
              :key="i"
              :href="`#${heading.slug}`"
              class="block text-sm hover:text-indigo-400 transition-colors"
              :class="heading.level === 'h3' ? 'pl-4 text-slate-500 text-xs' : 'text-slate-400 font-medium'"
            >
              {{ heading.text }}
            </a>
          </nav>
        </div>
      </aside>

      <!-- Blog Body Content -->
      <div :class="toc && toc.length > 0 ? 'lg:col-span-3' : 'lg:col-span-4'" class="space-y-12">
        <article class="prose prose-invert prose-indigo max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-a:text-indigo-400 hover:prose-a:text-indigo-300">
          <!-- Render headings with id anchors dynamically matching slug anchors -->
          <div v-html="blog.body"></div>
        </article>

        <!-- Focus Keyword (Helpful for admins, visible in meta) -->
        <div v-if="blog.focus_keyword" class="border border-slate-900 bg-slate-950/40 p-4 rounded-xl text-xs text-slate-500 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-2 4a2 2 0 012 2m-2-4a2 2 0 01-2-2m2 2a2 2 0 00-2 2m0 0a2 2 0 01-2 2m3-3L9 14m-5 4h16" />
          </svg>
          Focus Keyword: <strong class="text-slate-400 font-semibold">{{ blog.focus_keyword }}</strong>
        </div>

        <!-- Tags Row -->
        <div v-if="blog.tags && blog.tags.length > 0" class="flex flex-wrap gap-2 pt-6 border-t border-slate-900/60">
          <span 
            v-for="tag in blog.tags" 
            :key="tag.id" 
            class="bg-slate-900 hover:bg-slate-800 text-xs text-slate-300 font-medium py-1.5 px-3 rounded-full border border-slate-800 transition-colors"
          >
            #{{ tag.name }}
          </span>
        </div>
      </div>
    </main>

    <!-- Related Articles Section -->
    <section v-if="related && related.length > 0" class="border-t border-slate-900 bg-slate-950/30 py-20 mt-20">
      <div class="max-w-7xl mx-auto px-6 space-y-12">
        <h2 class="text-3xl font-extrabold text-white tracking-tight">Related Articles</h2>
        <div class="grid md:grid-cols-3 gap-8">
          <article v-for="post in related" :key="post.id" class="group border border-slate-900/80 bg-slate-900/10 hover:bg-slate-900/20 rounded-2xl overflow-hidden flex flex-col justify-between hover:border-slate-800 transition-all duration-300">
            <div class="p-6 space-y-4">
              <span class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">{{ post.read_time_minutes }} min read</span>
              <h3 class="text-lg font-bold text-white group-hover:text-indigo-400 transition-colors">
                <Link :href="`/blog/${post.category ? post.category.slug : 'uncategorized'}/${post.slug}`">
                  {{ post.title }}
                </Link>
              </h3>
              <p class="text-xs text-slate-400 line-clamp-3 leading-relaxed">
                {{ post.excerpt || 'Explore this article for more information on similar content topics.' }}
              </p>
            </div>
            <div class="p-6 pt-0 mt-4 border-t border-slate-900/40">
              <Link :href="`/blog/${post.category ? post.category.slug : 'uncategorized'}/${post.slug}`" class="text-xs text-indigo-400 font-semibold flex items-center gap-1 group-hover:underline">
                Read More
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </Link>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-12 text-center text-sm text-slate-500">
      <div class="max-w-7xl mx-auto px-6">
        &copy; 2026 RankQuill Premium Engine. All rights reserved.
      </div>
    </footer>
  </div>
</template>
