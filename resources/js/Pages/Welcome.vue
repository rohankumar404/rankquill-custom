<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  services: {
    type: Array,
    default: () => []
  },
  portfolios: {
    type: Array,
    default: () => []
  },
  testimonials: {
    type: Array,
    default: () => []
  },
  blogs: {
    type: Array,
    default: () => []
  }
})

// Newsletter state
const email = ref('')
const loading = ref(false)
const message = ref('')
const status = ref('') // 'success' or 'error'

const handleSubscribe = async () => {
  if (!email.value) return
  loading.value = true
  message.value = ''
  status.value = ''
  
  try {
    const res = await axios.post('/newsletter/subscribe', { email: email.value })
    if (res.data.success) {
      status.value = 'success'
      message.value = res.data.message || 'Thank you! You have successfully subscribed.'
      email.value = ''
    } else {
      status.value = 'error'
      message.value = 'Something went wrong. Please try again.'
    }
  } catch (err) {
    status.value = 'error'
    if (err.response && err.response.data && err.response.data.errors) {
      message.value = err.response.data.errors.email[0]
    } else {
      message.value = 'Failed to subscribe. Please verify your email.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <Head>
    <title>RankQuill - Premium High-Performance CMS Engine & Digital Agency</title>
    <meta name="description" content="RankQuill delivers cutting-edge digital experiences, search optimization models, and bespoke software solutions for enterprise brands." />
  </Head>

  <PublicLayout>
    <!-- 1. Hero Section -->
    <section class="relative overflow-hidden pt-32 pb-24 md:pt-40 md:pb-36 transition-colors duration-300">
      <!-- Glow Bubbles -->
      <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute top-1/2 -right-40 w-[600px] h-[600px] bg-purple-500/10 dark:bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>

      <div class="max-w-7xl mx-auto px-6 relative z-10 grid lg:grid-cols-12 gap-16 items-center">
        <div class="lg:col-span-7 space-y-8 text-left">
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-ping"></span>
            Next-Gen Performance Engine
          </span>

          <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight leading-[1.1] text-slate-950 dark:text-white">
            We Construct <br />
            <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-650 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
              Success Matrices
            </span>
          </h1>

          <p class="text-slate-600 dark:text-slate-400 text-lg md:text-xl font-light leading-relaxed max-w-2xl">
            Empower your enterprise with lightning-fast builds, semantic SEO structures, and dynamic lead management pipelines built for high conversion velocity.
          </p>

          <div class="flex flex-wrap gap-4 pt-2">
            <Link href="/services" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-8 py-4 rounded-full transition-all duration-300 shadow-lg shadow-indigo-600/20 hover:scale-[1.02] text-center">
              Explore Our Services
            </Link>
            <Link href="/portfolio" class="bg-slate-100 hover:bg-slate-200 text-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800 dark:text-slate-200 font-semibold px-8 py-4 rounded-full transition-all duration-300 border border-slate-200/60 dark:border-slate-800 hover:scale-[1.02] text-center">
              View Showcase
            </Link>
          </div>
        </div>

        <div class="lg:col-span-5 relative">
          <!-- Glassmorphism Tech Card -->
          <div class="relative bg-white/40 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/80 rounded-3xl p-8 shadow-2xl backdrop-blur-xl space-y-6">
            <div class="flex justify-between items-center border-b border-slate-200/40 dark:border-slate-800/40 pb-4">
              <div class="flex gap-2">
                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
              </div>
              <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">performance_index.json</span>
            </div>
            
            <div class="space-y-4">
              <div class="flex justify-between items-center">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Lighthouse Score</span>
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold font-mono">100%</span>
              </div>
              <div class="h-2 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
              </div>

              <div class="flex justify-between items-center pt-2">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Core Web Vitals</span>
                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-bold font-mono">Passed</span>
              </div>
              <div class="h-2 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 rounded-full" style="width: 95%"></div>
              </div>

              <div class="flex justify-between items-center pt-2">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Search Visibility</span>
                <span class="text-xs text-purple-600 dark:text-purple-400 font-bold font-mono">Excellent</span>
              </div>
              <div class="h-2 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-purple-500 rounded-full" style="width: 98%"></div>
              </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-150 dark:border-slate-900/60 p-4 rounded-2xl flex justify-between items-center">
              <div>
                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium uppercase tracking-wider">Conversion Velocity</div>
                <div class="text-xl font-extrabold text-slate-950 dark:text-white mt-1">+342% Avg ROI</div>
              </div>
              <div class="h-10 w-10 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 2. Trusted By Section -->
    <section class="py-12 border-y border-slate-150 dark:border-slate-900 bg-slate-50/40 dark:bg-slate-950/10">
      <div class="max-w-7xl mx-auto px-6">
        <p class="text-center text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-8">
          Trusted by Industry Leaders Worldwide
        </p>
        <div class="flex flex-wrap items-center justify-center gap-12 md:gap-20 opacity-60 dark:opacity-40">
          <span class="text-xl font-bold font-mono text-slate-500 dark:text-slate-400 hover:text-indigo-500 transition-colors">STRIPE</span>
          <span class="text-xl font-bold font-mono text-slate-500 dark:text-slate-400 hover:text-indigo-500 transition-colors">SLACK</span>
          <span class="text-xl font-bold font-mono text-slate-500 dark:text-slate-400 hover:text-indigo-500 transition-colors">AIRBNB</span>
          <span class="text-xl font-bold font-mono text-slate-500 dark:text-slate-400 hover:text-indigo-500 transition-colors">MICROSOFT</span>
          <span class="text-xl font-bold font-mono text-slate-500 dark:text-slate-400 hover:text-indigo-500 transition-colors">HUBSPOT</span>
        </div>
      </div>
    </section>

    <!-- 3. Services Section -->
    <section id="services" class="py-24">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
          <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase">Core Capabilities</span>
          <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-950 dark:text-white tracking-tight">
            Our Digital Solutions
          </h2>
          <p class="text-slate-500 dark:text-slate-400 font-light leading-relaxed text-base sm:text-lg">
            High-performance engineering coupled with strategic data growth architecture.
          </p>
        </div>

        <div v-if="services.length === 0" class="border border-slate-200 dark:border-slate-900 p-16 text-center text-slate-400 dark:text-slate-500 rounded-3xl">
          No services published yet.
        </div>

        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <article v-for="service in services" :key="service.id" class="group p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900/10 hover:bg-slate-50 dark:hover:bg-slate-900/30 hover:border-slate-350 dark:hover:border-slate-700/80 transition-all duration-300 flex flex-col justify-between">
            <div class="space-y-6">
              <!-- Service Icon Mock -->
              <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
              </div>

              <div class="space-y-2">
                <h3 class="text-xl font-bold text-slate-950 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                  {{ service.title }}
                </h3>
                <p class="text-xs text-indigo-650 dark:text-indigo-400 font-medium uppercase tracking-wider">
                  {{ service.tagline || 'Specialized Module' }}
                </p>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-light line-clamp-3">
                  {{ service.description }}
                </p>
              </div>
            </div>

            <div class="pt-8 border-t border-slate-100 dark:border-slate-900/40 mt-8 flex justify-between items-center">
              <span v-if="service.price_starting_at" class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                Starts at <strong class="text-slate-900 dark:text-white font-bold">${{ parseFloat(service.price_starting_at).toLocaleString() }}</strong>
              </span>
              <Link :href="`/services/${service.slug}`" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 group-hover:underline flex items-center gap-1">
                Details
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </Link>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 4. Counters Section -->
    <section class="py-20 bg-slate-50 dark:bg-slate-950/40 border-y border-slate-150 dark:border-slate-900 transition-colors">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div class="text-center space-y-2 border-r border-slate-200 dark:border-slate-900 last:border-0">
            <div class="text-3xl md:text-5xl font-extrabold text-slate-950 dark:text-white tracking-tight">98%</div>
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Client Satisfaction</div>
          </div>
          <div class="text-center space-y-2 border-r border-slate-200 dark:border-slate-900 last:border-0">
            <div class="text-3xl md:text-5xl font-extrabold text-slate-955 dark:text-white tracking-tight">250M+</div>
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Impressions Delivered</div>
          </div>
          <div class="text-center space-y-2 border-r border-slate-200 dark:border-slate-900 last:border-0">
            <div class="text-3xl md:text-5xl font-extrabold text-slate-955 dark:text-white tracking-tight">4.8x</div>
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Average SEO ROI</div>
          </div>
          <div class="text-center space-y-2 last:border-0">
            <div class="text-3xl md:text-5xl font-extrabold text-slate-955 dark:text-white tracking-tight">24/7</div>
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Real-time Support</div>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. Portfolio Section -->
    <section id="portfolio" class="py-24">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
          <div class="space-y-4">
            <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase">Our Masterpieces</span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-950 dark:text-white tracking-tight">
              Featured Case Studies
            </h2>
          </div>
          <Link href="/portfolio" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1.5">
            View All Projects
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </Link>
        </div>

        <div v-if="portfolios.length === 0" class="border border-slate-200 dark:border-slate-900 p-16 text-center text-slate-400 dark:text-slate-500 rounded-3xl">
          No case studies published yet.
        </div>

        <div v-else class="grid md:grid-cols-3 gap-8">
          <article v-for="project in portfolios" :key="project.id" class="group border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900/10 hover:bg-slate-50 dark:hover:bg-slate-900/30 rounded-3xl overflow-hidden flex flex-col justify-between hover:border-slate-350 dark:hover:border-slate-700 transition-all duration-300">
            <div class="p-8 space-y-6">
              <div class="flex justify-between items-center text-xs text-slate-400 dark:text-slate-500">
                <span class="font-medium text-indigo-650 dark:text-indigo-400 uppercase tracking-wider">{{ project.client_name }}</span>
                <span v-if="project.completed_at">{{ new Date(project.completed_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short' }) }}</span>
              </div>

              <div class="space-y-3">
                <h3 class="text-xl font-bold text-slate-955 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">
                  <Link :href="`/portfolio/${project.slug}`">
                    {{ project.project_title }}
                  </Link>
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-light leading-relaxed line-clamp-3">
                  {{ project.challenge || 'Strategic optimization and scaling case study showing metrics-driven organic transformations.' }}
                </p>
              </div>

              <div v-if="project.metrics && project.metrics.length > 0" class="grid grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-150 dark:border-slate-900/60">
                <div v-for="(metric, mIdx) in project.metrics.slice(0, 2)" :key="mIdx" class="space-y-1">
                  <div class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ metric.label }}</div>
                  <div class="text-base font-extrabold text-slate-955 dark:text-white tracking-tight">{{ metric.value }}</div>
                </div>
              </div>
            </div>

            <div class="p-8 pt-0 border-t border-slate-100 dark:border-slate-900/40 mt-4 flex justify-between items-center">
              <div class="flex flex-wrap gap-1">
                <span v-for="cat in project.categories" :key="cat.id" class="text-[9px] bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800 px-2 py-0.5 rounded">
                  {{ cat.name }}
                </span>
              </div>
              <Link :href="`/portfolio/${project.slug}`" class="text-xs text-indigo-650 dark:text-indigo-400 font-semibold group-hover:underline flex flex-shrink-0 items-center gap-1">
                Read Study
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </Link>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 6. Process Section -->
    <section class="py-24 bg-slate-50 dark:bg-slate-950/20 border-y border-slate-150 dark:border-slate-900/50">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
          <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase">Workflow Execution</span>
          <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-950 dark:text-white tracking-tight">
            Our Proven Process
          </h2>
          <p class="text-slate-500 dark:text-slate-400 font-light leading-relaxed">
            How we transform challenges into scalable successes.
          </p>
        </div>

        <div class="grid md:grid-cols-4 gap-8 relative">
          <!-- Item 1 -->
          <div class="relative space-y-4 bg-white dark:bg-slate-905 border border-slate-200 dark:border-slate-900 p-6 rounded-3xl">
            <span class="text-3xl font-extrabold bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">01</span>
            <h3 class="text-lg font-bold text-slate-950 dark:text-white">Audit & Strategy</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-light">
              Deep analytics analysis, site crawls, structural mapping, and KPI target consensus.
            </p>
          </div>

          <!-- Item 2 -->
          <div class="relative space-y-4 bg-white dark:bg-slate-905 border border-slate-200 dark:border-slate-900 p-6 rounded-3xl">
            <span class="text-3xl font-extrabold bg-gradient-to-r from-purple-500 to-pink-500 bg-clip-text text-transparent">02</span>
            <h3 class="text-lg font-bold text-slate-950 dark:text-white">Engine Design</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-light">
              Formulate optimized schemas, metadata architectures, and responsive components.
            </p>
          </div>

          <!-- Item 3 -->
          <div class="relative space-y-4 bg-white dark:bg-slate-905 border border-slate-200 dark:border-slate-900 p-6 rounded-3xl">
            <span class="text-3xl font-extrabold bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text text-transparent">03</span>
            <h3 class="text-lg font-bold text-slate-955 dark:text-white">Optimization</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-light">
              Construct super-fast builds, optimize SEO configurations, and sync newsletter marketing.
            </p>
          </div>

          <!-- Item 4 -->
          <div class="relative space-y-4 bg-white dark:bg-slate-905 border border-slate-200 dark:border-slate-900 p-6 rounded-3xl">
            <span class="text-3xl font-extrabold bg-gradient-to-r from-rose-500 to-indigo-500 bg-clip-text text-transparent">04</span>
            <h3 class="text-lg font-bold text-slate-955 dark:text-white">Continuous Growth</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-light">
              Iterative scaling models, metrics auditing, and user conversions acceleration.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- 7. Testimonials Section -->
    <section class="py-24">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
          <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase">Endorsements</span>
          <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-950 dark:text-white tracking-tight">
            Client Success Stories
          </h2>
        </div>

        <div v-if="testimonials.length === 0" class="border border-slate-200 dark:border-slate-900 p-16 text-center text-slate-400 dark:text-slate-500 rounded-3xl">
          No testimonials published yet.
        </div>

        <div v-else class="grid md:grid-cols-3 gap-8">
          <div v-for="t in testimonials" :key="t.id" class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/10 p-8 rounded-3xl flex flex-col justify-between hover:border-indigo-500/30 transition-all duration-300">
            <div class="space-y-6">
              <!-- Rating Stars -->
              <div class="flex gap-1 text-amber-500">
                <span v-for="star in 5" :key="star">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="star <= t.rating ? 'fill-current' : 'text-slate-200 dark:text-slate-800'" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.25.588 1.81l-3.97 2.887a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.887a1 1 0 00-1.175 0l-3.97 2.887c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.887c-.77-.56-.372-1.81.587-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z" />
                  </svg>
                </span>
              </div>
              <p class="text-slate-600 dark:text-slate-300 italic text-sm leading-relaxed">
                "{{ t.testimonial }}"
              </p>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-slate-100 dark:border-slate-900/40 mt-6">
              <div v-if="t.avatar_path" class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-800">
                <img :src="t.avatar_path" alt="Avatar" class="object-cover w-full h-full" />
              </div>
              <div v-else class="w-10 h-10 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xs">
                {{ t.client_name.charAt(0) }}
              </div>
              <div>
                <h4 class="font-bold text-slate-950 dark:text-white text-xs">{{ t.client_name }}</h4>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ t.client_position }}, {{ t.client_company }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 8. Blog Section -->
    <section id="blog" class="py-24 bg-slate-50 dark:bg-slate-950/20 border-y border-slate-150 dark:border-slate-900/50">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
          <div class="space-y-4">
            <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase">Knowledge Center</span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-955 dark:text-white tracking-tight">
              Latest Insights
            </h2>
          </div>
          <Link href="/blog" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1.5">
            Browse All Articles
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </Link>
        </div>

        <div v-if="blogs.length === 0" class="border border-slate-200 dark:border-slate-900 p-16 text-center text-slate-400 dark:text-slate-500 rounded-3xl">
          No articles published yet.
        </div>

        <div v-else class="grid md:grid-cols-3 gap-8">
          <article v-for="post in blogs" :key="post.id" class="group border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900/10 hover:bg-slate-50 dark:hover:bg-slate-900/30 rounded-3xl overflow-hidden flex flex-col justify-between hover:border-slate-350 dark:hover:border-slate-700 transition-all duration-300">
            <div class="p-8 space-y-6">
              <div class="flex justify-between items-center text-xs text-slate-400 dark:text-slate-500">
                <span v-if="post.category" class="font-bold text-indigo-650 dark:text-indigo-400 uppercase tracking-widest">{{ post.category.name }}</span>
                <span>{{ post.read_time_minutes || 3 }} min read</span>
              </div>

              <div class="space-y-3">
                <h3 class="text-lg font-bold text-slate-955 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">
                  <Link :href="`/blog/${post.category?.slug || 'uncategorized'}/${post.slug}`">
                    {{ post.title }}
                  </Link>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-light line-clamp-3">
                  {{ post.excerpt }}
                </p>
              </div>
            </div>

            <div class="p-8 pt-0 border-t border-slate-100 dark:border-slate-900/40 mt-4 flex justify-between items-center">
              <span class="text-[10px] text-slate-400 dark:text-slate-500">
                {{ new Date(post.published_at || post.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) }}
              </span>
              <Link :href="`/blog/${post.category?.slug || 'uncategorized'}/${post.slug}`" class="text-xs text-indigo-650 dark:text-indigo-400 font-semibold group-hover:underline flex items-center gap-1">
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

    <!-- 9. Newsletter Section -->
    <section class="py-24 relative overflow-hidden">
      <!-- Glow Bubbles -->
      <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

      <div class="max-w-4xl mx-auto px-6 relative z-10">
        <div class="bg-gradient-to-br from-indigo-50 dark:from-indigo-950/40 to-purple-50 dark:to-purple-950/20 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 md:p-16 text-center space-y-8 shadow-2xl backdrop-blur-md">
          <div class="space-y-4">
            <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase">Weekly Dispatch</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-950 dark:text-white tracking-tight">
              Stay Ahead of the Curve
            </h2>
            <p class="text-slate-500 dark:text-slate-400 font-light max-w-xl mx-auto text-sm md:text-base leading-relaxed">
              Get premium digital marketing guidelines, search strategy blueprints, and software engineering releases straight to your inbox.
            </p>
          </div>

          <form @submit.prevent="handleSubscribe" class="max-w-md mx-auto flex flex-col sm:flex-row gap-3">
            <input 
              v-model="email" 
              type="email" 
              placeholder="Enter your work email" 
              required
              class="flex-grow px-5 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-slate-900 dark:text-slate-100 text-sm transition-colors shadow-inner"
            />
            <button 
              type="submit" 
              :disabled="loading"
              class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg shadow-indigo-600/20 hover:scale-[1.01] text-sm flex items-center justify-center gap-2 flex-shrink-0 disabled:opacity-50"
            >
              <svg v-if="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Subscribe
            </button>
          </form>

          <!-- Inline Status Message -->
          <div v-if="message" class="text-xs transition-all duration-300" :class="status === 'success' ? 'text-indigo-650 dark:text-indigo-400 font-semibold' : 'text-rose-500'">
            {{ message }}
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>
