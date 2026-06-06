<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  portfolio: { type: Object, required: true }
})

const schemaJson = props.portfolio.seo?.schema_markup
  ? JSON.stringify(props.portfolio.seo.schema_markup)
  : null

const sliderVal = ref(50)
</script>

<template>
  <Head>
    <title>{{ portfolio.seo?.meta_title || portfolio.project_title + ' — Case Study' }} — RankQuill</title>
    <meta name="description" :content="portfolio.seo?.meta_description || portfolio.challenge || ''" />
    <meta name="robots" :content="portfolio.seo?.robots || 'index, follow'" />
    <meta property="og:title" :content="portfolio.seo?.og_title || portfolio.project_title" />
    <meta property="og:description" :content="portfolio.seo?.og_description || portfolio.challenge || ''" />
    <meta property="og:image" :content="portfolio.seo?.og_image_url || ''" />
    <script v-if="schemaJson" type="application/ld+json" v-html="schemaJson"></script>
  </Head>

  <PublicLayout>

    <!-- ===== HERO ===== -->
    <section class="relative overflow-hidden bg-[#070b14] py-28 sm:py-36">
      <div class="absolute inset-0 hero-grid opacity-60"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="section-wrapper relative z-10">
        <!-- Breadcrumb -->
        <div class="flex items-center justify-center gap-2 text-sm text-slate-500 mb-8">
          <Link href="/" class="hover:text-slate-300 transition-colors">Home</Link>
          <span>/</span>
          <Link href="/portfolio" class="hover:text-slate-300 transition-colors">Portfolio</Link>
          <span>/</span>
          <span class="text-slate-300 truncate max-w-[200px]">{{ portfolio.project_title }}</span>
        </div>

        <div class="max-w-4xl mx-auto text-center space-y-6">
          <!-- Category chips -->
          <div class="flex flex-wrap justify-center gap-2">
            <span v-for="cat in portfolio.categories" :key="cat.id"
              class="section-label py-1 px-3">
              {{ cat.name }}
            </span>
          </div>

          <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight">
            {{ portfolio.project_title }}
          </h1>

          <!-- Client + Date row -->
          <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-slate-400">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              <span>Client: <strong class="text-white font-bold">{{ portfolio.client_name }}</strong></span>
            </div>
            <div v-if="portfolio.completed_at" class="flex items-center gap-2">
              <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              <span>Completed: <strong class="text-white font-bold">{{ new Date(portfolio.completed_at).toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) }}</strong></span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== METRICS STRIP ===== -->
    <section v-if="portfolio.metrics?.length" class="bg-white dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800">
      <div class="section-wrapper py-10">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-0">
          <div v-for="(metric, idx) in portfolio.metrics" :key="idx"
            class="text-center px-6 py-6 border-r border-slate-100 dark:border-slate-800 last:border-0">
            <div class="text-3xl sm:text-4xl font-black gradient-text mb-1">{{ metric.value }}</div>
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ metric.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="bg-white dark:bg-slate-950 py-20">
      <div class="section-wrapper">
        <div class="grid lg:grid-cols-3 gap-12">

          <!-- Left: Story Columns -->
          <div class="lg:col-span-2 space-y-14">

            <!-- The Challenge -->
            <div v-if="portfolio.challenge" class="space-y-5">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">The Challenge</h2>
              </div>
              <p class="text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-line pl-11">{{ portfolio.challenge }}</p>
            </div>

            <!-- Our Approach -->
            <div v-if="portfolio.approach" class="space-y-5">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Our Strategy & Approach</h2>
              </div>
              <p class="text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-line pl-11">{{ portfolio.approach }}</p>
            </div>

            <!-- The Results -->
            <div v-if="portfolio.results" class="space-y-5">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">The Results</h2>
              </div>
              <p class="text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-line pl-11">{{ portfolio.results }}</p>
            </div>

            <!-- Dynamic content blocks -->
            <div v-if="portfolio.content?.length" class="pt-8 border-t border-slate-100 dark:border-slate-800 space-y-12">
              <div v-for="(section, idx) in portfolio.content" :key="idx">
                <div v-if="section.type === 'content'"
                  class="prose prose-slate dark:prose-invert prose-lg max-w-none prose-headings:font-black prose-a:text-indigo-600 dark:prose-a:text-indigo-400"
                  v-html="section.data.body">
                </div>
                <div v-else-if="section.type === 'faqs'" class="space-y-4">
                  <h3 class="text-xl font-black text-slate-900 dark:text-white mb-5">{{ section.data.title || 'Project FAQs' }}</h3>
                  <details v-for="faq in section.data.resolved_items" :key="faq.id"
                    class="group bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden">
                    <summary class="flex items-center justify-between gap-4 p-5 cursor-pointer list-none">
                      <h4 class="text-sm font-semibold text-slate-900 dark:text-white">{{ faq.question }}</h4>
                      <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="px-5 pb-5"><p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ faq.answer }}</p></div>
                  </details>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Sidebar Media -->
          <div class="lg:col-span-1 space-y-6">

            <!-- Video Embed -->
            <div v-if="portfolio.video_url" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 space-y-4">
              <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400">Project Video</h3>
              <div class="relative aspect-video rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950">
                <iframe
                  v-if="portfolio.video_url.includes('youtube') || portfolio.video_url.includes('youtu.be') || portfolio.video_url.includes('vimeo')"
                  :src="portfolio.video_url"
                  class="absolute inset-0 w-full h-full"
                  frameborder="0"
                  allowfullscreen
                ></iframe>
                <video v-else :src="portfolio.video_url" controls class="w-full h-full object-cover"></video>
              </div>
            </div>

            <!-- Before/After Slider -->
            <div v-if="portfolio.before_image_path && portfolio.after_image_path"
              class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 space-y-4">
              <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400">Before vs After</h3>
              <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden select-none border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-900">
                <img :src="portfolio.after_image_path" alt="After" class="absolute inset-0 w-full h-full object-cover" />
                <div class="absolute inset-0 overflow-hidden" :style="{ width: sliderVal + '%' }">
                  <img :src="portfolio.before_image_path" alt="Before" class="absolute inset-0 w-full h-full object-cover" style="min-width: 100vw" />
                </div>
                <div class="absolute top-0 bottom-0 w-0.5 bg-white shadow-lg" :style="{ left: sliderVal + '%' }">
                  <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-8 h-8 rounded-full bg-indigo-600 border-2 border-white flex items-center justify-center shadow-xl">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 9l-4 4 4 4m8-8l4 4-4 4"/></svg>
                  </div>
                </div>
                <input type="range" min="0" max="100" v-model="sliderVal" class="absolute inset-0 opacity-0 cursor-ew-resize w-full h-full" />
              </div>
              <div class="flex justify-between text-xs text-slate-400 font-medium">
                <span>← Before</span>
                <span>After →</span>
              </div>
            </div>

            <!-- Gallery -->
            <div v-if="portfolio.gallery?.length"
              class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 space-y-4">
              <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400">Project Gallery</h3>
              <div class="grid grid-cols-2 gap-2">
                <div v-for="(img, i) in portfolio.gallery" :key="i"
                  class="group aspect-[4/3] rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950">
                  <img :src="img" :alt="`Gallery ${i+1}`" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                </div>
              </div>
            </div>

            <!-- Back to Portfolio CTA -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-6 text-center space-y-4">
              <p class="text-xs font-bold text-indigo-200 uppercase tracking-wider">Ready to Scale?</p>
              <p class="text-white font-bold text-base leading-snug">Let's create your success story</p>
              <Link href="/contact-us" class="block w-full text-center bg-white text-indigo-700 font-bold text-sm py-3 rounded-2xl hover:bg-indigo-50 transition-colors shadow-lg">
                Start Your Project →
              </Link>
              <Link href="/portfolio" class="block text-xs text-indigo-200 hover:text-white transition-colors">
                ← Back to Portfolio
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

  </PublicLayout>
</template>
