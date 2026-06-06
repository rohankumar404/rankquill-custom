<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  blog: { type: Object, required: true },
  toc: { type: Array, default: () => [] },
  related: { type: Array, default: () => [] },
})

const schemaJson = props.blog.seo?.schema_markup
  ? JSON.stringify(props.blog.seo.schema_markup)
  : null

// Reading progress bar
const progress = ref(0)
const updateProgress = () => {
  const doc = document.documentElement
  const scrolled = doc.scrollTop || document.body.scrollTop
  const total = doc.scrollHeight - doc.clientHeight
  progress.value = total > 0 ? Math.round((scrolled / total) * 100) : 0
}
onMounted(() => window.addEventListener('scroll', updateProgress, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', updateProgress))
</script>

<template>
  <Head>
    <title>{{ blog.seo?.meta_title || blog.title }} — RankQuill Blog</title>
    <meta name="description" :content="blog.seo?.meta_description || blog.excerpt || ''" />
    <meta name="keywords" :content="blog.seo?.meta_keywords || blog.focus_keyword || ''" />
    <meta name="robots" :content="blog.seo?.robots || 'index, follow'" />
    <meta property="og:title" :content="blog.seo?.og_title || blog.title" />
    <meta property="og:description" :content="blog.seo?.og_description || blog.excerpt || ''" />
    <meta property="og:image" :content="blog.seo?.og_image_url || ''" />
    <script v-if="schemaJson" type="application/ld+json" v-html="schemaJson"></script>
  </Head>

  <PublicLayout>
    <!-- Reading progress bar -->
    <div id="reading-progress" :style="{ width: progress + '%' }"></div>

    <!-- ===== ARTICLE HERO ===== -->
    <section class="relative overflow-hidden bg-[#070b14] py-24 sm:py-32">
      <div class="absolute inset-0 hero-grid opacity-50"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="section-wrapper relative z-10 max-w-4xl mx-auto">
        <!-- Breadcrumb & Meta -->
        <div class="flex flex-wrap items-center gap-3 text-sm mb-6">
          <Link href="/blog" class="text-slate-500 hover:text-slate-300 transition-colors">Blog</Link>
          <span class="text-slate-700">/</span>
          <Link v-if="blog.category" :href="`/blog/category/${blog.category.slug}`" class="font-bold text-indigo-400 hover:text-indigo-300 transition-colors uppercase tracking-wider text-xs">
            {{ blog.category.name }}
          </Link>
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight mb-6">
          {{ blog.title }}
        </h1>

        <p v-if="blog.excerpt" class="text-slate-400 text-lg leading-relaxed mb-8">
          {{ blog.excerpt }}
        </p>

        <!-- Author + Meta bar -->
        <div class="flex flex-wrap items-center gap-6 pt-6 border-t border-slate-800">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white shadow-lg shadow-indigo-500/20">
              {{ blog.author?.name?.charAt(0) || 'S' }}
            </div>
            <div>
              <div class="text-sm font-bold text-white">{{ blog.author?.name || 'Staff Writer' }}</div>
              <div class="text-xs text-slate-500">{{ blog.published_at ? new Date(blog.published_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'Draft' }}</div>
            </div>
          </div>

          <div class="flex items-center gap-4 ml-auto">
            <div class="flex items-center gap-1.5 text-xs text-slate-400">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              {{ blog.read_time_minutes || 5 }} min read
            </div>
            <!-- Tags preview -->
            <div v-if="blog.tags?.length" class="flex gap-1">
              <span v-for="tag in blog.tags.slice(0,2)" :key="tag.id"
                class="text-[10px] bg-slate-800 border border-slate-700 text-slate-400 px-2 py-0.5 rounded-full">
                #{{ tag.name }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== MAIN ARTICLE CONTENT ===== -->
    <div class="bg-white dark:bg-slate-950">
      <div class="section-wrapper py-16">
        <div class="flex gap-12 items-start">

          <!-- Table of Contents Sidebar -->
          <aside v-if="toc?.length" class="hidden xl:block w-64 flex-shrink-0">
            <div class="sticky top-24 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
              <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Contents</p>
              <nav class="space-y-1">
                <a v-for="(heading, i) in toc" :key="i"
                  :href="`#${heading.slug}`"
                  class="block py-1.5 text-xs transition-colors rounded-lg px-2 hover:bg-slate-100 dark:hover:bg-slate-800"
                  :class="[
                    heading.level === 'h3' ? 'pl-5 text-slate-400 dark:text-slate-500' : 'text-slate-600 dark:text-slate-400 font-medium',
                    'hover:text-indigo-600 dark:hover:text-indigo-400'
                  ]">
                  {{ heading.text }}
                </a>
              </nav>
            </div>
          </aside>

          <!-- Article Body -->
          <article class="flex-1 min-w-0 max-w-3xl">
            <!-- Focus keyword chip -->
            <div v-if="blog.focus_keyword" class="inline-flex items-center gap-2 mb-8 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 rounded-full">
              <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-2 4a2 2 0 012 2m-2-4a2 2 0 01-2-2m2 2a2 2 0 00-2 2m0 0a2 2 0 01-2 2"/></svg>
              <span class="text-xs text-indigo-700 dark:text-indigo-400 font-medium">Focus: <strong>{{ blog.focus_keyword }}</strong></span>
            </div>

            <!-- Blog content -->
            <div class="prose prose-lg dark:prose-invert max-w-none
              prose-headings:font-black prose-headings:tracking-tight
              prose-h1:text-4xl prose-h2:text-3xl prose-h3:text-2xl
              prose-a:text-indigo-600 dark:prose-a:text-indigo-400 prose-a:no-underline hover:prose-a:underline
              prose-blockquote:border-indigo-500 prose-blockquote:bg-indigo-50 dark:prose-blockquote:bg-indigo-500/5 prose-blockquote:rounded-r-2xl prose-blockquote:not-italic
              prose-code:bg-slate-100 dark:prose-code:bg-slate-800 prose-code:rounded prose-code:px-1.5 prose-code:py-0.5
              prose-img:rounded-2xl prose-img:shadow-xl
              prose-p:text-slate-600 dark:prose-p:text-slate-300 prose-p:leading-relaxed"
              v-html="blog.body">
            </div>

            <!-- Tags -->
            <div v-if="blog.tags?.length" class="flex flex-wrap gap-2 mt-10 pt-8 border-t border-slate-100 dark:border-slate-800">
              <span class="text-xs text-slate-400 mr-2 font-medium self-center">Tags:</span>
              <span v-for="tag in blog.tags" :key="tag.id"
                class="text-xs bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 border border-slate-200 dark:border-slate-700 px-3 py-1 rounded-full transition-colors cursor-pointer">
                #{{ tag.name }}
              </span>
            </div>

            <!-- Share row -->
            <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
              <div>
                <p class="text-sm font-bold text-slate-900 dark:text-white mb-1">Enjoyed this article?</p>
                <p class="text-xs text-slate-500">Share it with your network.</p>
              </div>
              <div class="flex gap-2">
                <a :href="`https://twitter.com/intent/tweet?text=${encodeURIComponent(blog.title)}&url=${encodeURIComponent($page.url)}`" target="_blank"
                  class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-medium border border-slate-200 dark:border-slate-700 transition-colors">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                  Tweet
                </a>
                <a :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent($page.url)}`" target="_blank"
                  class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-500/10 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-xs font-medium border border-slate-200 dark:border-slate-700 transition-colors">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                  LinkedIn
                </a>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>

    <!-- ===== RELATED ARTICLES ===== -->
    <section v-if="related?.length" class="py-20 bg-slate-50 dark:bg-[#070b14] border-t border-slate-100 dark:border-slate-800">
      <div class="section-wrapper">
        <div class="flex items-center justify-between mb-10">
          <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Related Articles</h2>
          <Link href="/blog" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
            All Articles →
          </Link>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <article v-for="post in related" :key="post.id"
            class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden card-hover flex flex-col">
            <div class="h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            <div class="p-6 flex-1 flex flex-col">
              <div class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-3">{{ post.category?.name || 'Blog' }}</div>
              <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-snug mb-3 flex-1">
                <Link :href="`/blog/${post.category?.slug || 'uncategorized'}/${post.slug}`">{{ post.title }}</Link>
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed mb-4">{{ post.excerpt }}</p>
              <Link :href="`/blog/${post.category?.slug || 'uncategorized'}/${post.slug}`"
                class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                Read Article →
              </Link>
            </div>
          </article>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>
