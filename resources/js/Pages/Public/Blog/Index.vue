<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

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

  <PublicLayout>
    <main class="max-w-7xl mx-auto px-6 py-16 grid lg:grid-cols-4 gap-12">
      <!-- Categories Sidebar -->
      <aside class="space-y-8 lg:col-span-1">
        <div class="border border-slate-200/60 dark:border-slate-800/60 bg-white/50 dark:bg-slate-900/20 rounded-2xl p-6">
          <h2 class="text-lg font-bold text-slate-950 dark:text-white mb-4 tracking-tight border-b border-slate-100 dark:border-slate-800 pb-3">Categories</h2>
          <div class="space-y-2.5">
            <Link 
              href="/blog" 
              class="block text-sm py-2 px-3 rounded-lg transition-all"
              :class="!category ? 'bg-indigo-600/10 dark:bg-indigo-600/20 text-indigo-600 dark:text-indigo-400 font-semibold border-l-2 border-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-900/50'"
            >
              All Articles
            </Link>
            <Link 
              v-for="cat in categories" 
              :key="cat.id"
              :href="`/blog/category/${cat.slug}`"
              class="block text-sm py-2 px-3 rounded-lg transition-all"
              :class="category && category.id === cat.id ? 'bg-indigo-600/10 dark:bg-indigo-600/20 text-indigo-600 dark:text-indigo-400 font-semibold border-l-2 border-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-900/50'"
            >
              {{ cat.name }}
            </Link>
          </div>
        </div>
      </aside>

      <!-- Blogs Content Grid -->
      <div class="lg:col-span-3 space-y-12">
        <div class="space-y-4">
          <h1 class="text-3xl md:text-5xl font-extrabold text-slate-950 dark:text-white tracking-tight">
            {{ category ? category.name : 'Enterprise SEO & CMS Blog' }}
          </h1>
          <p class="text-slate-500 dark:text-slate-400 font-light leading-relaxed max-w-3xl">
            {{ category ? `Browse resources and guides filed under ${category.name}.` : 'Stay up to date with modern content strategies, web performance analysis, and search engine parameters.' }}
          </p>
        </div>

        <div v-if="blogs.data.length === 0" class="border border-slate-200 dark:border-slate-850 rounded-3xl p-16 text-center text-slate-400">
          No articles found under this section. Check back soon!
        </div>

        <div v-else class="grid sm:grid-cols-2 gap-8">
          <article v-for="post in blogs.data" :key="post.id" class="group border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900/20 hover:bg-slate-50 dark:hover:bg-slate-900/40 rounded-2xl overflow-hidden flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300">
            <div class="p-6 space-y-4">
              <!-- Meta Row -->
              <div class="flex items-center gap-3 text-xs text-indigo-600 dark:text-indigo-400 font-semibold uppercase tracking-wider">
                <span>{{ post.category ? post.category.name : 'Uncategorized' }}</span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-200 dark:bg-slate-800"></span>
                <span class="text-slate-400 dark:text-slate-500 font-normal normal-case">{{ post.read_time_minutes }} min read</span>
              </div>
              <h3 class="text-xl font-bold text-slate-950 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                <Link :href="`/blog/${post.category ? post.category.slug : 'uncategorized'}/${post.slug}`">
                  {{ post.title }}
                </Link>
              </h3>
              <p class="text-sm text-slate-500 dark:text-slate-400 font-light leading-relaxed">
                {{ post.excerpt || 'Read this post to learn about strategic CMS implementation details and enterprise integrations.' }}
              </p>
            </div>
            <div class="p-6 pt-0 flex items-center justify-between border-t border-slate-100 dark:border-slate-900/60 mt-4">
              <span class="text-xs text-slate-400 dark:text-slate-500">
                By {{ post.author ? post.author.name : 'Staff Writer' }}
              </span>
              <Link :href="`/blog/${post.category ? post.category.slug : 'uncategorized'}/${post.slug}`" class="text-xs text-indigo-600 dark:text-indigo-400 font-medium group-hover:underline flex items-center gap-1">
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
            class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-800/80 transition-colors"
            :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900/40 hover:text-slate-800 dark:hover:text-white'"
          />
        </div>
      </div>
    </main>
  </PublicLayout>
</template>
