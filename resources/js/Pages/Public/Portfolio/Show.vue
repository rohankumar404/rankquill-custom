<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  portfolio: {
    type: Object,
    required: true
  }
})

const schemaJson = props.portfolio.seo?.schema_markup 
  ? JSON.stringify(props.portfolio.seo.schema_markup) 
  : null

// State for before-after slider
const sliderVal = ref(50)
</script>

<template>
  <Head>
    <title>{{ portfolio.seo?.meta_title || `${portfolio.project_title} - Case Study` }}</title>
    <meta name="description" :content="portfolio.seo?.meta_description || portfolio.challenge || ''" />
    <meta name="keywords" :content="portfolio.seo?.meta_keywords || ''" />
    <meta name="robots" :content="portfolio.seo?.robots || 'index, follow'" />
    <link rel="canonical" :href="portfolio.seo?.canonical_url || '#'" />
    
    <!-- Open Graph -->
    <meta property="og:title" :content="portfolio.seo?.og_title || portfolio.project_title" />
    <meta property="og:description" :content="portfolio.seo?.og_description || portfolio.challenge || ''" />
    <meta property="og:image" :content="portfolio.seo?.og_image_url || ''" />
    
    <!-- JSON-LD Schema Markup -->
    <script v-if="schemaJson" type="application/ld+json" v-html="schemaJson"></script>
  </Head>

  <PublicLayout>
    <!-- Case Study Header -->
    <div class="border-b border-slate-200/60 dark:border-slate-900/60 bg-slate-100/50 dark:bg-slate-900/10 py-20 relative overflow-hidden transition-colors duration-300">
      <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-3xl"></div>
      <div class="max-w-4xl mx-auto px-6 space-y-6 text-center">
        <div class="flex justify-center gap-2 flex-wrap">
          <span v-for="cat in portfolio.categories" :key="cat.id" class="bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 text-xs font-bold uppercase tracking-widest py-1 px-3.5 rounded-full">
            {{ cat.name }}
          </span>
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold text-slate-950 dark:text-white tracking-tight leading-tight">
          {{ portfolio.project_title }}
        </h1>
        <div class="flex items-center justify-center gap-3 text-slate-500 dark:text-slate-400 text-sm font-light">
          <span>Client: <strong class="text-slate-900 dark:text-white font-semibold">{{ portfolio.client_name }}</strong></span>
          <span class="w-1.5 h-1.5 rounded-full bg-slate-200 dark:bg-slate-800"></span>
          <span v-if="portfolio.completed_at">Completed: <strong class="text-slate-900 dark:text-white font-semibold">{{ new Date(portfolio.completed_at).toLocaleDateString('en-US', {month: 'long', year: 'numeric'}) }}</strong></span>
        </div>
      </div>
    </div>

    <!-- KPIs Metrics Showcase -->
    <section v-if="portfolio.metrics && portfolio.metrics.length > 0" class="max-w-7xl mx-auto px-6 -mt-8 relative z-10">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl shadow-slate-150/50 dark:shadow-slate-950/50 transition-colors">
        <div v-for="(metric, idx) in portfolio.metrics" :key="idx" class="text-center space-y-2 border-r border-slate-200 dark:border-slate-800/80 last:border-0">
          <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ metric.label }}</div>
          <div class="text-3xl md:text-5xl font-extrabold bg-gradient-to-r from-slate-950 via-slate-800 to-indigo-650 dark:from-white dark:via-slate-100 dark:to-indigo-400 bg-clip-text text-transparent tracking-tight">
            {{ metric.value }}
          </div>
        </div>
      </div>
    </section>

    <!-- Case Study Content Sections -->
    <main class="max-w-7xl mx-auto px-6 py-16 grid lg:grid-cols-3 gap-12">
      <!-- Challenge, Approach, Results -->
      <div class="lg:col-span-2 space-y-16">
        
        <!-- Challenge -->
        <section v-if="portfolio.challenge" class="space-y-4">
          <h2 class="text-2xl md:text-3xl font-extrabold text-slate-950 dark:text-white tracking-tight">The Challenge</h2>
          <p class="text-slate-600 dark:text-slate-400 font-light leading-relaxed whitespace-pre-line text-sm md:text-base">
            {{ portfolio.challenge }}
          </p>
        </section>

        <!-- Approach -->
        <section v-if="portfolio.approach" class="space-y-4">
          <h2 class="text-2xl md:text-3xl font-extrabold text-slate-950 dark:text-white tracking-tight">Our Strategy & Approach</h2>
          <p class="text-slate-600 dark:text-slate-400 font-light leading-relaxed whitespace-pre-line text-sm md:text-base">
            {{ portfolio.approach }}
          </p>
        </section>

        <!-- Results -->
        <section v-if="portfolio.results" class="space-y-4">
          <h2 class="text-2xl md:text-3xl font-extrabold text-slate-950 dark:text-white tracking-tight">The Results</h2>
          <p class="text-slate-600 dark:text-slate-400 font-light leading-relaxed whitespace-pre-line text-sm md:text-base">
            {{ portfolio.results }}
          </p>
        </section>

        <!-- Dynamic Block Sections -->
        <div v-if="portfolio.content && portfolio.content.length > 0" class="pt-8 border-t border-slate-200 dark:border-slate-900 space-y-16">
          <div v-for="(section, idx) in portfolio.content" :key="idx">
            
            <!-- Standard text content block -->
            <div v-if="section.type === 'content'" class="prose dark:prose-invert prose-indigo max-w-none">
              <div v-html="section.data.body"></div>
            </div>

            <!-- FAQs inside Case study -->
            <div v-else-if="section.type === 'faqs'" class="space-y-4">
              <h3 class="text-xl font-bold text-slate-950 dark:text-white">{{ section.data.title || 'Case FAQ' }}</h3>
              <div class="space-y-3">
                <div v-for="faq in section.data.resolved_items" :key="faq.id" class="border border-slate-200 dark:border-slate-900 bg-white dark:bg-slate-900/20 p-5 rounded-xl">
                  <h4 class="font-semibold text-slate-955 dark:text-white mb-2">{{ faq.question }}</h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 font-light leading-relaxed">{{ faq.answer }}</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- Sidebar Media: Video, Gallery, Before-After -->
      <div class="lg:col-span-1 space-y-12">
        
        <!-- Video Embed -->
        <div v-if="portfolio.video_url" class="border border-slate-200 dark:border-slate-900 bg-white dark:bg-slate-900/10 p-6 rounded-3xl space-y-4">
          <h3 class="text-lg font-bold text-slate-950 dark:text-white tracking-tight border-b border-slate-200 dark:border-slate-900 pb-3">Project Video</h3>
          <div class="relative aspect-video rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
            <!-- Checking for basic embed strings or raw mp4 source -->
            <iframe 
              v-if="portfolio.video_url.includes('youtube.com') || portfolio.video_url.includes('youtu.be') || portfolio.video_url.includes('vimeo.com')"
              :src="portfolio.video_url" 
              class="absolute inset-0 w-full h-full" 
              frameborder="0" 
              allowfullscreen
            ></iframe>
            <video v-else :src="portfolio.video_url" controls class="w-full h-full object-cover"></video>
          </div>
        </div>

        <!-- Before-After Slider -->
        <div v-if="portfolio.before_image_path && portfolio.after_image_path" class="border border-slate-200 dark:border-slate-900 bg-white dark:bg-slate-900/10 p-6 rounded-3xl space-y-6">
          <h3 class="text-lg font-bold text-slate-950 dark:text-white tracking-tight border-b border-slate-200 dark:border-slate-900 pb-3">Before vs After</h3>
          
          <!-- Slider Comparison Container -->
          <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden select-none border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-900">
            <!-- After Image -->
            <img :src="portfolio.after_image_path" alt="After Version" width="380" height="285" loading="lazy" class="absolute inset-0 w-full h-full object-cover" />
            
            <!-- Before Image (Clipped) -->
            <div 
              class="absolute inset-0 overflow-hidden" 
              :style="{ width: sliderVal + '%' }"
            >
              <img :src="portfolio.before_image_path" alt="Before Version" width="380" height="285" loading="lazy" class="absolute inset-0 w-[380px] h-[285px] max-w-none object-cover" />
            </div>

            <!-- Handle slider line -->
            <div 
              class="absolute top-0 bottom-0 w-0.5 bg-indigo-500 cursor-ew-resize"
              :style="{ left: sliderVal + '%' }"
            >
              <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-6 h-6 rounded-full bg-indigo-600 border-2 border-white flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 9l-4 4 4 4m8-8l4 4-4 4" />
                </svg>
              </div>
            </div>

            <!-- Hidden slider input -->
            <input 
              type="range" 
              min="0" 
              max="100" 
              v-model="sliderVal" 
              class="absolute inset-0 opacity-0 cursor-ew-resize w-full h-full"
            />
          </div>
          <div class="flex justify-between text-xs text-slate-400 dark:text-slate-500 font-medium">
            <span>&larr; Before (drag slider)</span>
            <span>After &rarr;</span>
          </div>
        </div>

        <!-- Showcase Gallery -->
        <div v-if="portfolio.gallery && portfolio.gallery.length > 0" class="border border-slate-200 dark:border-slate-900 bg-white dark:bg-slate-900/10 p-6 rounded-3xl space-y-4">
          <h3 class="text-lg font-bold text-slate-950 dark:text-white tracking-tight border-b border-slate-200 dark:border-slate-900 pb-3">Project Gallery</h3>
          <div class="grid grid-cols-2 gap-3">
            <div v-for="(img, idx) in portfolio.gallery" :key="idx" class="group relative aspect-[4/3] rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
              <img :src="img" alt="Gallery item" width="160" height="120" loading="lazy" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300" />
            </div>
          </div>
        </div>

      </div>
    </main>
  </PublicLayout>
</template>
