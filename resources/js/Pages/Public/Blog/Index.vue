<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  blogs: {
    type: Object,
    required: true
  },
  categories: {
    type: Array,
    required: true
  },
  category: {
    type: Object,
    default: null
  }
})
</script>

<template>
  <Head>
    <title>{{ category ? category.name : 'Blog' }} - RankQuill</title>
    <meta name="description" content="Explore insightful blog posts on SEO tactics, content production, and business optimizations." />
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

    <main class="max-w-7xl mx-auto px-6 py-16 grid lg:grid-cols-4 gap-12">
      <!-- Categories Sidebar -->
      <aside class="space-y-8 lg:col-span-1">
        <div class="border border-slate-800/60 bg-slate-900/20 rounded-2xl p-6">
          <h2 class="text-lg font-bold text-white mb-4 tracking-tight border-b border-slate-800 pb-3">Categories</h2>
          <div class="space-y-2.5">
            <Link 
              href="/blog" 
              class="block text-sm py-2 px-3 rounded-lg transition-all"
              :class="!category ? 'bg-indigo-600/20 text-indigo-400 font-semibold border-l-2 border-indigo-500' : 'text-slate-400 hover:text-white hover:bg-slate-900/50'"
            >
              All Articles
            </Link>
            <Link 
              v-for="cat in categories" 
              :key="cat.id"
              :href="`/blog/category/${cat.slug}`"
              class="block text-sm py-2 px-3 rounded-lg transition-all"
              :class="category && category.id === cat.id ? 'bg-indigo-600/20 text-indigo-400 font-semibold border-l-2 border-indigo-500' : 'text-slate-400 hover:text-white hover:bg-slate-900/50'"
            >
              {{ cat.name }}
            </Link>
          </div>
        </div>
      </aside>

      <!-- Blogs Content Grid -->
      <div class="lg:col-span-3 space-y-12">
        <div class="space-y-4">
          <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight">
            {{ category ? category.name : 'Enterprise SEO & CMS Blog' }}
          </h1>
          <p class="text-slate-400 font-light leading-relaxed max-w-3xl">
            {{ category ? `Browse resources and guides filed under ${category.name}.` : 'Stay up to date with modern content strategies, web performance analysis, and search engine parameters.' }}
          </p>
        </div>

        <div v-if="blogs.data.length === 0" class="border border-slate-800 rounded-3xl p-16 text-center text-slate-500">
          No articles found under this section. Check back soon!
        </div>

        <div v-else class="grid sm:grid-cols-2 gap-8">
          <article v-for="post in blogs.data" :key="post.id" class="group border border-slate-800/80 bg-slate-900/20 hover:bg-slate-900/40 rounded-2xl overflow-hidden flex flex-col justify-between hover:border-slate-700 transition-all duration-300">
            <div class="p-6 space-y-4">
              <!-- Meta Row -->
              <div class="flex items-center gap-3 text-xs text-indigo-400 font-semibold uppercase tracking-wider">
                <span>{{ post.category ? post.category.name : 'Uncategorized' }}</span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-800"></span>
                <span class="text-slate-500 font-normal normal-case">{{ post.read_time_minutes }} min read</span>
              </div>
              <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition-colors">
                <Link :href="`/blog/${post.category ? post.category.slug : 'uncategorized'}/${post.slug}`">
                  {{ post.title }}
                </Link>
              </h3>
              <p class="text-sm text-slate-400 font-light leading-relaxed">
                {{ post.excerpt || 'Read this post to learn about strategic CMS implementation details and enterprise integrations.' }}
              </p>
            </div>
            <div class="p-6 pt-0 flex items-center justify-between border-t border-slate-900/60 mt-4">
              <span class="text-xs text-slate-500">
                By {{ post.author ? post.author.name : 'Staff Writer' }}
              </span>
              <Link :href="`/blog/${post.category ? post.category.slug : 'uncategorized'}/${post.slug}`" class="text-xs text-indigo-400 font-medium group-hover:underline flex items-center gap-1">
                Read Article
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </Link>
            </div>
          </article>
        </div>

        <!-- Simple Pagination Mockup -->
        <div v-if="blogs.last_page > 1" class="flex gap-2">
          <Link 
            v-for="(link, i) in blogs.links" 
            :key="i"
            :href="link.url || '#'"
            v-html="link.label"
            class="px-4 py-2 text-sm rounded-lg border border-slate-800/80 transition-colors"
            :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'text-slate-400 hover:bg-slate-900/40 hover:text-white'"
          />
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-12 mt-20 text-center text-sm text-slate-500">
      <div class="max-w-7xl mx-auto px-6">
        &copy; 2026 RankQuill Premium Engine. All rights reserved.
      </div>
    </footer>
  </div>
</template>
