<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

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

  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white">
    <!-- Header Navigation -->
    <header class="border-b border-slate-800/80 bg-slate-900/40 backdrop-blur-md sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="text-xl font-bold bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
          RankQuill
        </div>
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
          <a href="/" class="hover:text-indigo-400 transition-colors">Home</a>
          <Link href="/services" class="hover:text-indigo-400 transition-colors">Services</Link>
          <Link href="/portfolio" class="hover:text-indigo-400 transition-colors text-indigo-400">Portfolio</Link>
          <Link href="/blog" class="hover:text-indigo-400 transition-colors">Blog</Link>
        </nav>
        <button class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2.5 rounded-full transition-all duration-300 shadow-lg shadow-indigo-600/20 hover:scale-[1.02]">
          Get Started
        </button>
      </div>
    </header>

    <!-- Case Study Header -->
    <div class="border-b border-slate-900/60 bg-slate-900/10 py-20 relative overflow-hidden">
      <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-3xl"></div>
      <div class="max-w-4xl mx-auto px-6 space-y-6 text-center">
        <div class="flex justify-center gap-2 flex-wrap">
          <span v-for="cat in portfolio.categories" :key="cat.id" class="bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-bold uppercase tracking-widest py-1 px-3.5 rounded-full">
            {{ cat.name }}
          </span>
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
          {{ portfolio.project_title }}
        </h1>
        <div class="flex items-center justify-center gap-3 text-slate-400 text-sm font-light">
          <span>Client: <strong class="text-white font-semibold">{{ portfolio.client_name }}</strong></span>
          <span class="w-1.5 h-1.5 rounded-full bg-slate-800"></span>
          <span v-if="portfolio.completed_at">Completed: <strong class="text-white font-semibold">{{ new Date(portfolio.completed_at).toLocaleDateString('en-US', {month: 'long', year: 'numeric'}) }}</strong></span>
        </div>
      </div>
    </div>

    <!-- KPIs Metrics Showcase -->
    <section v-if="portfolio.metrics && portfolio.metrics.length > 0" class="max-w-7xl mx-auto px-6 -mt-8 relative z-10">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl shadow-slate-950/50">
        <div v-for="(metric, idx) in portfolio.metrics" :key="idx" class="text-center space-y-2 border-r border-slate-800/80 last:border-0">
          <div class="text-xs font-bold uppercase tracking-widest text-slate-500">{{ metric.label }}</div>
          <div class="text-3xl md:text-5xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-indigo-400 bg-clip-text text-transparent tracking-tight">
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
          <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">The Challenge</h2>
          <p class="text-slate-400 font-light leading-relaxed whitespace-pre-line text-sm md:text-base">
            {{ portfolio.challenge }}
          </p>
        </section>

        <!-- Approach -->
        <section v-if="portfolio.approach" class="space-y-4">
          <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Our Strategy & Approach</h2>
          <p class="text-slate-400 font-light leading-relaxed whitespace-pre-line text-sm md:text-base">
            {{ portfolio.approach }}
          </p>
        </section>

        <!-- Results -->
        <section v-if="portfolio.results" class="space-y-4">
          <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">The Results</h2>
          <p class="text-slate-400 font-light leading-relaxed whitespace-pre-line text-sm md:text-base">
            {{ portfolio.results }}
          </p>
        </section>

        <!-- Dynamic Block Sections -->
        <div v-if="portfolio.content && portfolio.content.length > 0" class="pt-8 border-t border-slate-900 space-y-16">
          <div v-for="(section, idx) in portfolio.content" :key="idx">
            
            <!-- Standard text content block -->
            <div v-if="section.type === 'content'" class="prose prose-invert prose-indigo max-w-none">
              <div v-html="section.data.body"></div>
            </div>

            <!-- FAQs inside Case study -->
            <div v-else-if="section.type === 'faqs'" class="space-y-4">
              <h3 class="text-xl font-bold text-white">{{ section.data.title || 'Case FAQ' }}</h3>
              <div class="space-y-3">
                <div v-for="faq in section.data.resolved_items" :key="faq.id" class="border border-slate-900 bg-slate-900/20 p-5 rounded-xl">
                  <h4 class="font-semibold text-white mb-2">{{ faq.question }}</h4>
                  <p class="text-xs text-slate-400 font-light leading-relaxed">{{ faq.answer }}</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- Sidebar Media: Video, Gallery, Before-After -->
      <div class="lg:col-span-1 space-y-12">
        
        <!-- Video Embed -->
        <div v-if="portfolio.video_url" class="border border-slate-900 bg-slate-900/10 p-6 rounded-3xl space-y-4">
          <h3 class="text-lg font-bold text-white tracking-tight border-b border-slate-900 pb-3">Project Video</h3>
          <div class="relative aspect-video rounded-xl overflow-hidden border border-slate-800 bg-slate-950">
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
        <div v-if="portfolio.before_image_path && portfolio.after_image_path" class="border border-slate-900 bg-slate-900/10 p-6 rounded-3xl space-y-6">
          <h3 class="text-lg font-bold text-white tracking-tight border-b border-slate-900 pb-3">Before vs After</h3>
          
          <!-- Slider Comparison Container -->
          <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden select-none border border-slate-800 bg-slate-900">
            <!-- After Image -->
            <img :src="portfolio.after_image_path" alt="After Version" class="absolute inset-0 w-full h-full object-cover" />
            
            <!-- Before Image (Clipped) -->
            <div 
              class="absolute inset-0 overflow-hidden" 
              :style="{ width: sliderVal + '%' }"
            >
              <img :src="portfolio.before_image_path" alt="Before Version" class="absolute inset-0 w-[380px] h-[285px] max-w-none object-cover" />
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
          <div class="flex justify-between text-xs text-slate-500 font-medium">
            <span>&larr; Before (drag slider)</span>
            <span>After &rarr;</span>
          </div>
        </div>

        <!-- Showcase Gallery -->
        <div v-if="portfolio.gallery && portfolio.gallery.length > 0" class="border border-slate-900 bg-slate-900/10 p-6 rounded-3xl space-y-4">
          <h3 class="text-lg font-bold text-white tracking-tight border-b border-slate-900 pb-3">Project Gallery</h3>
          <div class="grid grid-cols-2 gap-3">
            <div v-for="(img, idx) in portfolio.gallery" :key="idx" class="group relative aspect-[4/3] rounded-xl overflow-hidden border border-slate-800 bg-slate-950">
              <img :src="img" alt="Gallery item" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300" />
            </div>
          </div>
        </div>

      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-12 text-center text-sm text-slate-500">
      <div class="max-w-7xl mx-auto px-6">
        &copy; 2026 RankQuill Premium Engine. All rights reserved.
      </div>
    </footer>
  </div>
</template>
