<script setup>
import { Head, Link, router } from '@inertiajs/vue3'

const props = defineProps({
  portfolios: {
    type: Object,
    required: true
  },
  categories: {
    type: Array,
    required: true
  },
  selectedCategory: {
    type: String,
    default: null
  }
})

const filterCategory = (slug) => {
  router.get('/portfolio', { category: slug }, { preserveState: true })
}
</script>

<template>
  <Head>
    <title>Our Showcase Portfolio - RankQuill</title>
    <meta name="description" content="Explore our premium case studies, conversion metrics, design highlights, and development results for enterprise clients." />
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

    <main class="max-w-7xl mx-auto px-6 py-20 space-y-16">
      <div class="space-y-4 text-center max-w-3xl mx-auto">
        <span class="text-xs font-bold tracking-widest text-indigo-400 uppercase">Case Studies & Showcase</span>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
          Our Proven Results
        </h1>
        <p class="text-slate-400 font-light leading-relaxed text-base md:text-lg">
          We construct success matrices. Explore our portfolio of enterprise accomplishments, SEO traffic transformations, and bespoke client case studies.
        </p>
      </div>

      <!-- Categories Filter Tabs -->
      <div class="flex flex-wrap items-center justify-center gap-3">
        <button 
          @click="filterCategory(null)"
          class="text-xs font-semibold px-5 py-2.5 rounded-full transition-all"
          :class="!selectedCategory ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800'"
        >
          All Projects
        </button>
        <button 
          v-for="cat in categories" 
          :key="cat.id"
          @click="filterCategory(cat.slug)"
          class="text-xs font-semibold px-5 py-2.5 rounded-full transition-all"
          :class="selectedCategory === cat.slug ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800'"
        >
          {{ cat.name }}
        </button>
      </div>

      <div v-if="portfolios.data.length === 0" class="border border-slate-900 p-16 text-center text-slate-500 rounded-3xl">
        No case studies published yet.
      </div>

      <!-- Portfolio Grid -->
      <div v-else class="grid md:grid-cols-2 gap-10">
        <article v-for="project in portfolios.data" :key="project.id" class="group border border-slate-800/80 bg-slate-900/10 hover:bg-slate-900/30 rounded-3xl overflow-hidden flex flex-col justify-between hover:border-slate-700 transition-all duration-300">
          <div class="p-8 space-y-6">
            <!-- Client & Completion Date -->
            <div class="flex justify-between items-center text-xs text-slate-500">
              <span class="font-medium text-indigo-400 uppercase tracking-wider">{{ project.client_name }}</span>
              <span v-if="project.completed_at">{{ new Date(project.completed_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short' }) }}</span>
            </div>

            <!-- Title & Challenge Summary -->
            <div class="space-y-3">
              <h3 class="text-2xl md:text-3xl font-extrabold text-white group-hover:text-indigo-400 transition-colors leading-tight">
                <Link :href="`/portfolio/${project.slug}`">
                  {{ project.project_title }}
                </Link>
              </h3>
              <p class="text-sm text-slate-400 font-light leading-relaxed line-clamp-3">
                {{ project.challenge || 'An in-depth study showing how strategic optimizations solved performance bottlenecks and built a high-value customer platform.' }}
              </p>
            </div>

            <!-- Metrics Grid Highlight -->
            <div v-if="project.metrics && project.metrics.length > 0" class="grid grid-cols-2 gap-4 p-5 rounded-2xl bg-slate-950/60 border border-slate-900/60">
              <div v-for="(metric, mIdx) in project.metrics.slice(0, 2)" :key="mIdx" class="space-y-1">
                <div class="text-xs text-slate-500 font-medium">{{ metric.label }}</div>
                <div class="text-xl md:text-2xl font-extrabold text-white tracking-tight">{{ metric.value }}</div>
              </div>
            </div>
          </div>

          <!-- Card Action -->
          <div class="p-8 pt-0 border-t border-slate-900/40 mt-4 flex justify-between items-center">
            <div class="flex flex-wrap gap-1.5">
              <span v-for="cat in project.categories" :key="cat.id" class="text-[10px] bg-slate-900/80 text-slate-400 border border-slate-800/80 px-2.5 py-1 rounded-md">
                {{ cat.name }}
              </span>
            </div>
            <Link :href="`/portfolio/${project.slug}`" class="text-xs text-indigo-400 font-semibold group-hover:underline flex items-center gap-1 flex-shrink-0">
              Read Case Study
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </Link>
          </div>
        </article>
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
