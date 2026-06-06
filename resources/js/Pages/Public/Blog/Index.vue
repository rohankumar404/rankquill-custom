<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
  blogs: { type: Object, required: true },
  categories: { type: Array, required: true },
  category: { type: Object, default: null }
})
</script>

<template>
  <Head>
    <title>{{ category ? category.name + ' — ' : '' }}Blog & Insights — RankQuill</title>
    <meta name="description" content="Stay up to date with modern content strategies, SEO, web performance, and AI-powered growth techniques." />
  </Head>

  <PublicLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-[#070b14] py-24 sm:py-32">
      <div class="absolute inset-0 hero-grid opacity-60"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="section-wrapper relative z-10">
        <div class="flex items-center justify-center gap-2 text-sm text-slate-500 mb-6">
          <Link href="/" class="hover:text-slate-300 transition-colors">Home</Link>
          <span>/</span>
          <span class="text-slate-300">Blog</span>
          <template v-if="category">
            <span>/</span>
            <span class="text-slate-300">{{ category.name }}</span>
          </template>
        </div>
        <div class="text-center max-w-3xl mx-auto space-y-4">
          <span class="section-label">Knowledge Hub</span>
          <h1 class="text-5xl sm:text-6xl font-black text-white tracking-tight leading-tight">
            {{ category ? category.name : 'Insights & Ideas' }}
          </h1>
          <p class="text-slate-400 text-lg leading-relaxed">
            {{ category
              ? `Browse expert resources and guides filed under ${category.name}.`
              : 'Deep-dives into SEO, web performance, AI automation, and digital growth strategy.' }}
          </p>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <div class="py-16 bg-white dark:bg-slate-950">
      <div class="section-wrapper">
        <div class="flex flex-col lg:flex-row gap-12">

          <!-- Sidebar -->
          <aside class="lg:w-64 flex-shrink-0">
            <div class="sticky top-24 space-y-6">
              <!-- Categories -->
              <div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
                <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Categories</h2>
                <nav class="space-y-1">
                  <Link href="/blog"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm transition-all"
                    :class="!category ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'">
                    All Articles
                    <span v-if="!category" class="text-xs bg-white/20 px-1.5 py-0.5 rounded">✓</span>
                  </Link>
                  <Link v-for="cat in categories" :key="cat.id"
                    :href="`/blog/category/${cat.slug}`"
                    class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm transition-all"
                    :class="category?.id === cat.id ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'">
                    {{ cat.name }}
                    <span class="text-xs opacity-50">{{ cat.posts_count || '' }}</span>
                  </Link>
                </nav>
              </div>

              <!-- CTA -->
              <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-5 text-center space-y-3">
                <p class="text-xs font-bold text-indigo-200 uppercase tracking-wider">Newsletter</p>
                <p class="text-white font-bold text-sm leading-snug">Get weekly insights delivered</p>
                <Link href="/#newsletter" class="block w-full text-center bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors border border-white/20">
                  Subscribe Free →
                </Link>
              </div>
            </div>
          </aside>

          <!-- Blog Posts Grid -->
          <div class="flex-1 min-w-0">
            <div v-if="blogs.data.length === 0" class="text-center py-20 border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
              <svg class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
              <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-2">No articles yet</h3>
              <p class="text-slate-500 text-sm">Check back soon for fresh content!</p>
            </div>

            <div v-else>
              <div class="grid sm:grid-cols-2 gap-6 mb-10">
                <article v-for="(post, idx) in blogs.data" :key="post.id"
                  class="group bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden card-hover flex flex-col">

                  <!-- Color accent bar -->
                  <div class="h-1 bg-gradient-to-r"
                    :class="['from-indigo-500 to-purple-500','from-purple-500 to-pink-500','from-cyan-500 to-indigo-500','from-emerald-500 to-cyan-500'][idx % 4]">
                  </div>

                  <div class="p-6 flex-1 flex flex-col">
                    <!-- Meta -->
                    <div class="flex items-center gap-3 text-xs mb-4">
                      <span class="font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">
                        {{ post.category?.name || 'Uncategorized' }}
                      </span>
                      <span class="text-slate-300 dark:text-slate-700">·</span>
                      <span class="text-slate-400">{{ post.read_time_minutes || 3 }} min</span>
                    </div>

                    <!-- Title -->
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-snug mb-3 flex-1">
                      <Link :href="`/blog/${post.category?.slug || 'uncategorized'}/${post.slug}`">
                        {{ post.title }}
                      </Link>
                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 mb-5">
                      {{ post.excerpt || 'Read this post for expert insights on digital marketing and growth strategies.' }}
                    </p>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                      <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-[10px] font-bold">
                          {{ (post.author?.name || 'S').charAt(0) }}
                        </div>
                        <span class="text-xs text-slate-500">{{ post.author?.name || 'Staff Writer' }}</span>
                      </div>
                      <Link :href="`/blog/${post.category?.slug || 'uncategorized'}/${post.slug}`"
                        class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        Read →
                      </Link>
                    </div>
                  </div>
                </article>
              </div>

              <!-- Pagination -->
              <div v-if="blogs.last_page > 1" class="flex items-center justify-center gap-2">
                <Link v-for="(link, i) in blogs.links" :key="i"
                  :href="link.url || '#'"
                  v-html="link.label"
                  class="min-w-[40px] h-10 flex items-center justify-center px-3 rounded-xl text-sm font-medium transition-all duration-200 border"
                  :class="link.active
                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-600/20'
                    : link.url
                      ? 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-indigo-500/30 hover:text-indigo-600 dark:hover:text-indigo-400'
                      : 'border-slate-100 dark:border-slate-900 text-slate-300 dark:text-slate-700 cursor-not-allowed'"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>
