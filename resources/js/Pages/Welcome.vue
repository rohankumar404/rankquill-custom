<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  services: { type: Array, default: () => [] },
  portfolios: { type: Array, default: () => [] },
  testimonials: { type: Array, default: () => [] },
  blogs: { type: Array, default: () => [] },
})

// Service icons map
const serviceIcons = {
  'web-development': 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
  'seo-optimization': 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
  'app-development': 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
  'ppc-campaigns': 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
  'graphic-design': 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
  'ai-solutions': 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
}

const serviceColors = [
  { bg: 'bg-indigo-500/10', text: 'text-indigo-500', hover: 'group-hover:bg-indigo-500', border: 'border-indigo-500/20' },
  { bg: 'bg-emerald-500/10', text: 'text-emerald-500', hover: 'group-hover:bg-emerald-500', border: 'border-emerald-500/20' },
  { bg: 'bg-purple-500/10', text: 'text-purple-500', hover: 'group-hover:bg-purple-500', border: 'border-purple-500/20' },
  { bg: 'bg-orange-500/10', text: 'text-orange-500', hover: 'group-hover:bg-orange-500', border: 'border-orange-500/20' },
  { bg: 'bg-pink-500/10', text: 'text-pink-500', hover: 'group-hover:bg-pink-500', border: 'border-pink-500/20' },
  { bg: 'bg-cyan-500/10', text: 'text-cyan-500', hover: 'group-hover:bg-cyan-500', border: 'border-cyan-500/20' },
]

const getServiceIcon = (slug) => serviceIcons[slug] || 'M13 10V3L4 14h7v7l9-11h-7z'
const getServiceColor = (idx) => serviceColors[idx % serviceColors.length]

// Newsletter
const email = ref('')
const loading = ref(false)
const message = ref('')
const status = ref('')

const handleSubscribe = async () => {
  if (!email.value) return
  loading.value = true
  message.value = ''
  try {
    const res = await axios.post('/newsletter/subscribe', { email: email.value })
    if (res.data.success) {
      status.value = 'success'
      message.value = res.data.message || 'You\'re subscribed! Welcome aboard.'
      email.value = ''
    } else {
      status.value = 'error'
      message.value = 'Something went wrong. Please try again.'
    }
  } catch (err) {
    status.value = 'error'
    message.value = err.response?.data?.errors?.email?.[0] || 'Failed to subscribe.'
  } finally {
    loading.value = false
  }
}

// Animate counters
const counts = ref({ satisfaction: 0, impressions: 0, roi: 0 })
onMounted(() => {
  setTimeout(() => {
    const interval = setInterval(() => {
      if (counts.value.satisfaction < 98) counts.value.satisfaction += 2
      if (counts.value.impressions < 250) counts.value.impressions += 5
      if (counts.value.roi < 48) counts.value.roi += 1
      if (counts.value.satisfaction >= 98 && counts.value.impressions >= 250 && counts.value.roi >= 48) {
        clearInterval(interval)
      }
    }, 30)
  }, 500)
})
</script>

<template>
  <Head>
    <title>RankQuill — Premium Digital Agency | SEO, Web Dev & AI Solutions</title>
    <meta name="description" content="RankQuill delivers cutting-edge digital experiences, search optimization, and bespoke software solutions for enterprise brands worldwide." />
  </Head>

  <PublicLayout>

    <!-- ========================================================
         HERO SECTION
    ========================================================= -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-[#070b14]">
      <!-- Background Grid -->
      <div class="absolute inset-0 hero-grid opacity-60"></div>

      <!-- Glow Orbs -->
      <div class="absolute top-1/4 -left-32 w-[500px] h-[500px] rounded-full bg-indigo-600/10 blur-[120px] pointer-events-none"></div>
      <div class="absolute bottom-1/4 -right-32 w-[500px] h-[500px] rounded-full bg-purple-600/10 blur-[120px] pointer-events-none"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] rounded-full bg-indigo-500/5 blur-[100px] pointer-events-none"></div>

      <div class="section-wrapper relative z-10 py-32 lg:py-40">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">

          <!-- Left: Copy -->
          <div class="lg:col-span-7 space-y-8">
            <!-- Badge -->
            <div class="animate-fade-up inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold">
              <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
              </span>
              Premium Digital Agency · Est. 2020
            </div>

            <!-- Headline -->
            <div class="animate-fade-up delay-100 space-y-4">
              <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-[1.05] text-white">
                We Build
                <br />
                <span class="gradient-text-animated">Digital Empires</span>
              </h1>
              <p class="text-slate-400 text-lg sm:text-xl leading-relaxed max-w-xl">
                Enterprise-grade web platforms, AI-powered SEO, and conversion-obsessed digital strategies that dominate search and drive measurable ROI.
              </p>
            </div>

            <!-- CTAs -->
            <div class="animate-fade-up delay-200 flex flex-wrap gap-4">
              <Link href="/contact-us" class="btn-primary text-base px-8 py-4 rounded-full glow-indigo">
                Start Your Project
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </Link>
              <Link href="/portfolio" class="inline-flex items-center gap-2 px-8 py-4 rounded-full border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white text-base font-semibold transition-all duration-300">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                See Our Work
              </Link>
            </div>

            <!-- Social Proof Row -->
            <div class="animate-fade-up delay-300 flex flex-wrap items-center gap-6 pt-2">
              <div class="flex -space-x-2">
                <div v-for="i in 4" :key="i" class="w-9 h-9 rounded-full border-2 border-[#070b14] bg-gradient-to-br"
                  :class="['from-indigo-400 to-purple-500', 'from-purple-400 to-pink-500', 'from-cyan-400 to-indigo-500', 'from-pink-400 to-rose-500'][i-1]">
                </div>
              </div>
              <div class="text-sm text-slate-400">
                <span class="font-bold text-white">200+</span> enterprises trust us
              </div>
              <div class="flex items-center gap-1 text-amber-400">
                <svg v-for="s in 5" :key="s" class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <span class="text-xs text-slate-400 ml-1">4.9/5 rating</span>
              </div>
            </div>
          </div>

          <!-- Right: Stats Dashboard Card -->
          <div class="lg:col-span-5 animate-fade-up delay-400">
            <div class="relative">
              <!-- Main Card -->
              <div class="glass rounded-3xl p-6 space-y-5 glow-soft border border-white/10">
                <!-- Terminal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                  <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                  </div>
                  <span class="text-xs text-slate-500 font-mono">performance_dashboard.live</span>
                  <span class="flex items-center gap-1.5 text-[10px] text-emerald-400 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> LIVE
                  </span>
                </div>

                <!-- Metrics -->
                <div class="space-y-4">
                  <!-- Lighthouse -->
                  <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs">
                      <span class="text-slate-400 font-medium">Lighthouse Score</span>
                      <span class="text-emerald-400 font-bold font-mono">100 / 100</span>
                    </div>
                    <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                      <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400" style="width:100%; transition: width 2s ease;"></div>
                    </div>
                  </div>

                  <!-- Core Web Vitals -->
                  <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs">
                      <span class="text-slate-400 font-medium">Core Web Vitals</span>
                      <span class="text-indigo-400 font-bold font-mono">All Passed ✓</span>
                    </div>
                    <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                      <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-500" style="width:96%;"></div>
                    </div>
                  </div>

                  <!-- SEO Coverage -->
                  <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs">
                      <span class="text-slate-400 font-medium">SEO Coverage</span>
                      <span class="text-purple-400 font-bold font-mono">Premium Tier</span>
                    </div>
                    <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                      <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-pink-500" style="width:98%;"></div>
                    </div>
                  </div>
                </div>

                <!-- Stat Row -->
                <div class="grid grid-cols-3 gap-3 pt-2">
                  <div class="bg-slate-800/60 rounded-2xl p-4 text-center">
                    <div class="text-2xl font-black text-white font-mono">{{ counts.satisfaction }}%</div>
                    <div class="text-[10px] text-slate-500 mt-1 font-medium">Client Retention</div>
                  </div>
                  <div class="bg-slate-800/60 rounded-2xl p-4 text-center">
                    <div class="text-2xl font-black text-white font-mono">{{ counts.impressions }}M+</div>
                    <div class="text-[10px] text-slate-500 mt-1 font-medium">Impressions</div>
                  </div>
                  <div class="bg-slate-800/60 rounded-2xl p-4 text-center">
                    <div class="text-2xl font-black text-white font-mono">{{ counts.roi / 10 }}x</div>
                    <div class="text-[10px] text-slate-500 mt-1 font-medium">SEO ROI</div>
                  </div>
                </div>

                <!-- CTA Inside Card -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-4 flex items-center justify-between">
                  <div>
                    <p class="text-xs text-indigo-200 font-medium">Average Client Growth</p>
                    <p class="text-xl font-black text-white">+342% Revenue</p>
                  </div>
                  <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                  </div>
                </div>
              </div>

              <!-- Floating badge -->
              <div class="absolute -top-4 -right-4 bg-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-emerald-500/30 animate-float">
                #1 Ranked Agency ✓
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Scroll Indicator -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-slate-600 animate-bounce">
        <span class="text-xs font-medium tracking-widest uppercase">Scroll</span>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
      </div>
    </section>

    <!-- ========================================================
         MARQUEE — TRUSTED BY
    ========================================================= -->
    <section class="py-10 bg-white dark:bg-slate-900/50 border-y border-slate-100 dark:border-slate-800 overflow-hidden">
      <div class="flex items-center overflow-hidden">
        <div class="flex gap-12 items-center animate-marquee whitespace-nowrap">
          <template v-for="n in 3" :key="n">
            <span class="text-lg font-black text-slate-300 dark:text-slate-600 tracking-wider">STRIPE</span>
            <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
            <span class="text-lg font-black text-slate-300 dark:text-slate-600 tracking-wider">GOOGLE</span>
            <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
            <span class="text-lg font-black text-slate-300 dark:text-slate-600 tracking-wider">MICROSOFT</span>
            <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
            <span class="text-lg font-black text-slate-300 dark:text-slate-600 tracking-wider">AIRBNB</span>
            <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
            <span class="text-lg font-black text-slate-300 dark:text-slate-600 tracking-wider">HUBSPOT</span>
            <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
            <span class="text-lg font-black text-slate-300 dark:text-slate-600 tracking-wider">SHOPIFY</span>
            <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
            <span class="text-lg font-black text-slate-300 dark:text-slate-600 tracking-wider">SLACK</span>
            <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
            <span class="text-lg font-black text-slate-300 dark:text-slate-600 tracking-wider">ATLASSIAN</span>
            <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700 mx-6"></span>
          </template>
        </div>
      </div>
    </section>

    <!-- ========================================================
         SERVICES SECTION
    ========================================================= -->
    <section id="services" class="py-24 sm:py-32 bg-slate-50 dark:bg-[#070b14] relative overflow-hidden">
      <!-- Subtle bg -->
      <div class="absolute inset-0 hero-grid opacity-30 dark:opacity-60"></div>
      <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-indigo-500/5 rounded-full blur-3xl"></div>

      <div class="section-wrapper relative">
        <div class="section-header">
          <span class="section-label">Core Capabilities</span>
          <h2 class="section-title">Services That Drive <br /><span class="gradient-text">Real Results</span></h2>
          <p class="section-subtitle">Enterprise-grade digital solutions engineered for measurable growth and market dominance.</p>
        </div>

        <!-- Services Grid -->
        <div v-if="services.length === 0" class="text-center py-20 text-slate-400 dark:text-slate-600">
          <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          <p>Services coming soon. <Link href="/admin" class="text-indigo-500 hover:underline">Add from Admin →</Link></p>
        </div>

        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <article
            v-for="(service, idx) in services"
            :key="service.id"
            class="group relative bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 card-hover flex flex-col overflow-hidden"
          >
            <!-- Gradient top line -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

            <!-- Icon -->
            <div class="mb-6" :class="[getServiceColor(idx).bg, 'w-14 h-14 rounded-2xl flex items-center justify-center border', getServiceColor(idx).border, 'transition-all duration-300', 'group-hover:scale-110']">
              <svg class="w-7 h-7" :class="getServiceColor(idx).text" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" :d="getServiceIcon(service.slug)" />
              </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 space-y-3 mb-6">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                  {{ service.title }}
                </h3>
                <span v-if="service.price_starting_at" class="text-xs font-bold text-slate-400 dark:text-slate-500 whitespace-nowrap ml-2">
                  from ${{ parseFloat(service.price_starting_at).toLocaleString() }}
                </span>
              </div>
              <p v-if="service.tagline" class="text-xs font-semibold uppercase tracking-widest" :class="getServiceColor(idx).text">{{ service.tagline }}</p>
              <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed line-clamp-3">
                {{ service.description || 'Professional solutions tailored for enterprise-level performance and business targets.' }}
              </p>
            </div>

            <!-- Features -->
            <ul v-if="service.features?.length" class="space-y-2 mb-6">
              <li v-for="(feat, fIdx) in service.features.slice(0, 3)" :key="fIdx" class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ feat }}
              </li>
            </ul>

            <!-- CTA -->
            <Link :href="`/services/${service.slug}`"
              class="flex items-center justify-between px-5 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 group-hover:border-indigo-500/30 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-500/10 transition-all duration-300 text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
              Explore Details
              <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </Link>
          </article>
        </div>

        <!-- All Services Link -->
        <div class="text-center mt-12">
          <Link href="/services" class="btn-outline">
            View All Services
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </Link>
        </div>
      </div>
    </section>

    <!-- ========================================================
         STATS SECTION
    ========================================================= -->
    <section class="py-20 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 relative overflow-hidden">
      <div class="absolute inset-0 hero-grid opacity-20"></div>
      <div class="section-wrapper relative">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-0">
          <div class="text-center md:border-r md:border-white/20 px-6">
            <div class="text-4xl sm:text-5xl font-black text-white mb-2">98%</div>
            <div class="text-indigo-100 text-sm font-medium">Client Retention Rate</div>
          </div>
          <div class="text-center md:border-r md:border-white/20 px-6">
            <div class="text-4xl sm:text-5xl font-black text-white mb-2">250M+</div>
            <div class="text-indigo-100 text-sm font-medium">Impressions Delivered</div>
          </div>
          <div class="text-center md:border-r md:border-white/20 px-6">
            <div class="text-4xl sm:text-5xl font-black text-white mb-2">4.8x</div>
            <div class="text-indigo-100 text-sm font-medium">Average SEO ROI</div>
          </div>
          <div class="text-center px-6">
            <div class="text-4xl sm:text-5xl font-black text-white mb-2">200+</div>
            <div class="text-indigo-100 text-sm font-medium">Enterprise Clients</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========================================================
         PORTFOLIO SECTION
    ========================================================= -->
    <section id="portfolio" class="py-24 sm:py-32 bg-white dark:bg-slate-950">
      <div class="section-wrapper">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16 gap-6">
          <div class="space-y-4">
            <span class="section-label">Featured Work</span>
            <h2 class="section-title">Case Studies That<br /><span class="gradient-text">Speak Results</span></h2>
          </div>
          <Link href="/portfolio" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors group">
            View All Projects
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </Link>
        </div>

        <div v-if="portfolios.length === 0" class="border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl p-20 text-center text-slate-400 dark:text-slate-600">
          No case studies yet. <Link href="/admin" class="text-indigo-500 hover:underline">Add from Admin →</Link>
        </div>

        <div v-else class="grid md:grid-cols-3 gap-6">
          <article
            v-for="(project, idx) in portfolios"
            :key="project.id"
            class="group bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden card-hover flex flex-col"
          >
            <!-- Thumbnail Placeholder -->
            <div class="relative h-48 overflow-hidden"
              :class="['bg-gradient-to-br from-indigo-600 to-purple-700', 'bg-gradient-to-br from-purple-600 to-pink-700', 'bg-gradient-to-br from-cyan-600 to-indigo-700'][idx % 3]">
              <div class="absolute inset-0 flex items-center justify-center opacity-20">
                <svg class="w-24 h-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <div class="absolute top-4 left-4">
                <span class="bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full border border-white/30">{{ project.client_name }}</span>
              </div>
              <div class="absolute bottom-4 right-4 text-white/60 text-xs">
                {{ project.completed_at ? new Date(project.completed_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short' }) : '' }}
              </div>
            </div>

            <!-- Content -->
            <div class="p-6 flex-1 flex flex-col">
              <div class="flex-1 space-y-3 mb-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">
                  <Link :href="`/portfolio/${project.slug}`">{{ project.project_title }}</Link>
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                  {{ project.challenge || 'Strategic optimization and scaling case study showing metrics-driven organic transformations.' }}
                </p>
              </div>

              <!-- Metrics -->
              <div v-if="project.metrics?.length" class="grid grid-cols-2 gap-3 mb-4">
                <div v-for="(metric, mIdx) in project.metrics.slice(0, 2)" :key="mIdx" class="bg-white dark:bg-slate-800 rounded-xl p-3 border border-slate-100 dark:border-slate-700">
                  <div class="text-xs text-slate-400 dark:text-slate-500">{{ metric.label }}</div>
                  <div class="text-base font-extrabold text-slate-900 dark:text-white mt-0.5">{{ metric.value }}</div>
                </div>
              </div>

              <!-- Tags & Link -->
              <div class="flex items-center justify-between">
                <div class="flex flex-wrap gap-1">
                  <span v-for="cat in project.categories?.slice(0, 2)" :key="cat.id" class="text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-700">{{ cat.name }}</span>
                </div>
                <Link :href="`/portfolio/${project.slug}`" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 group-hover:gap-2 transition-all">
                  Case Study
                  <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </Link>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- ========================================================
         PROCESS SECTION
    ========================================================= -->
    <section class="py-24 sm:py-32 bg-slate-50 dark:bg-[#070b14] relative overflow-hidden">
      <div class="absolute inset-0 hero-grid opacity-40"></div>
      <div class="section-wrapper relative">
        <div class="section-header">
          <span class="section-label">How We Work</span>
          <h2 class="section-title">Our Proven <span class="gradient-text">4-Step Process</span></h2>
          <p class="section-subtitle">A battle-tested methodology that transforms challenges into scalable digital success stories.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
          <!-- Connecting line (desktop) -->
          <div class="absolute hidden lg:block top-8 left-[12.5%] right-[12.5%] h-px bg-gradient-to-r from-indigo-500/20 via-purple-500/20 to-pink-500/20" style="top: 32px;"></div>

          <div v-for="(step, idx) in [
            { num: '01', title: 'Audit & Strategy', desc: 'Deep analytics analysis, site crawls, competitive mapping, and KPI alignment.', color: 'from-indigo-600 to-indigo-500', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
            { num: '02', title: 'Architecture Design', desc: 'SEO schemas, metadata systems, performance stacks, and conversion funnels.', color: 'from-purple-600 to-purple-500', icon: 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z' },
            { num: '03', title: 'Build & Optimize', desc: 'High-performance builds with automated testing, CI/CD pipelines, and live monitoring.', color: 'from-pink-600 to-rose-500', icon: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4' },
            { num: '04', title: 'Scale & Grow', desc: 'Iterative scaling with A/B testing, conversion optimization, and growth hacking.', color: 'from-emerald-600 to-emerald-500', icon: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' },
          ]" :key="idx" class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/5">
            <!-- Step number badge -->
            <div :class="`w-14 h-14 rounded-2xl bg-gradient-to-br ${step.color} flex items-center justify-center mb-6 shadow-lg`">
              <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" :d="step.icon"/>
              </svg>
            </div>
            <div class="text-4xl font-black text-slate-100 dark:text-slate-800 absolute top-4 right-6">{{ step.num }}</div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-3">{{ step.title }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ========================================================
         TESTIMONIALS SECTION
    ========================================================= -->
    <section class="py-24 sm:py-32 bg-white dark:bg-slate-950">
      <div class="section-wrapper">
        <div class="section-header">
          <span class="section-label">Social Proof</span>
          <h2 class="section-title">Clients Who <span class="gradient-text">Love Us</span></h2>
        </div>

        <div v-if="testimonials.length === 0" class="border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl p-20 text-center text-slate-400">
          No testimonials yet.
        </div>

        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="(t, idx) in testimonials"
            :key="t.id"
            class="group relative bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 card-hover flex flex-col justify-between overflow-hidden"
          >
            <!-- Quote Icon -->
            <div class="absolute top-6 right-6 text-slate-100 dark:text-slate-800 opacity-60">
              <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
            </div>

            <!-- Stars -->
            <div class="flex gap-1 mb-4">
              <svg v-for="s in 5" :key="s" class="w-4 h-4" :class="s <= (t.rating || 5) ? 'text-amber-400 fill-current' : 'text-slate-200 dark:text-slate-700'" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>

            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed italic flex-1 mb-6">
              "{{ t.testimonial }}"
            </p>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
              <div v-if="t.avatar_path" class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-indigo-500/20">
                <img :src="t.avatar_path" :alt="t.client_name" class="w-full h-full object-cover"/>
              </div>
              <div v-else class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm ring-2 ring-indigo-500/20">
                {{ t.client_name?.charAt(0) || '?' }}
              </div>
              <div>
                <div class="text-sm font-bold text-slate-900 dark:text-white">{{ t.client_name }}</div>
                <div class="text-xs text-slate-400">{{ t.client_position }}<span v-if="t.client_company">, {{ t.client_company }}</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========================================================
         BLOG SECTION
    ========================================================= -->
    <section id="blog" class="py-24 sm:py-32 bg-slate-50 dark:bg-[#070b14] relative overflow-hidden">
      <div class="absolute inset-0 hero-grid opacity-40"></div>
      <div class="section-wrapper relative">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16 gap-6">
          <div class="space-y-4">
            <span class="section-label">Knowledge Hub</span>
            <h2 class="section-title">Fresh <span class="gradient-text">Insights</span></h2>
          </div>
          <Link href="/blog" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors group">
            All Articles
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </Link>
        </div>

        <div v-if="blogs.length === 0" class="border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl p-20 text-center text-slate-400">
          No articles published yet.
        </div>

        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <article
            v-for="(post, idx) in blogs"
            :key="post.id"
            class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden card-hover flex flex-col"
          >
            <!-- Color Bar -->
            <div class="h-1 bg-gradient-to-r"
              :class="['from-indigo-500 to-purple-500', 'from-purple-500 to-pink-500', 'from-cyan-500 to-indigo-500'][idx % 3]">
            </div>

            <div class="p-6 flex-1 flex flex-col">
              <!-- Meta -->
              <div class="flex items-center justify-between mb-4 text-xs">
                <span v-if="post.category" class="font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">{{ post.category.name }}</span>
                <span class="text-slate-400">{{ post.read_time_minutes || 3 }} min read</span>
              </div>

              <!-- Title -->
              <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-snug mb-3 flex-1">
                <Link :href="`/blog/${post.category?.slug || 'uncategorized'}/${post.slug}`">
                  {{ post.title }}
                </Link>
              </h3>

              <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 mb-6">
                {{ post.excerpt }}
              </p>

              <!-- Footer -->
              <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs text-slate-400">
                  {{ new Date(post.published_at || post.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) }}
                </span>
                <Link :href="`/blog/${post.category?.slug || 'uncategorized'}/${post.slug}`" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                  Read →
                </Link>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- ========================================================
         CTA / NEWSLETTER SECTION
    ========================================================= -->
    <section class="py-24 sm:py-32 bg-white dark:bg-slate-950 relative overflow-hidden">
      <div class="absolute inset-0">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-indigo-500/5 dark:bg-indigo-500/10 rounded-full blur-3xl"></div>
      </div>

      <div class="section-wrapper relative">
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-12 sm:p-20 overflow-hidden">
          <!-- Pattern -->
          <div class="absolute inset-0 hero-grid opacity-20"></div>

          <!-- Orbs -->
          <div class="absolute -top-20 -right-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
          <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>

          <div class="relative z-10 max-w-2xl mx-auto text-center space-y-6">
            <span class="inline-block text-indigo-200 text-xs font-bold uppercase tracking-widest border border-white/20 px-3 py-1 rounded-full">Weekly Newsletter</span>
            <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tight">
              Stay Ahead of the<br/>Digital Curve
            </h2>
            <p class="text-indigo-100 text-lg leading-relaxed">
              Get premium insights on SEO, AI automation, and growth strategies delivered weekly. Join 5,000+ marketers.
            </p>

            <form @submit.prevent="handleSubscribe" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
              <input
                v-model="email"
                type="email"
                placeholder="Enter your email address"
                required
                class="flex-1 px-5 py-3.5 rounded-full bg-white/20 border border-white/30 placeholder-white/60 text-white focus:outline-none focus:bg-white/30 focus:border-white/50 transition-all text-sm backdrop-blur-sm"
              />
              <button type="submit" :disabled="loading" class="px-6 py-3.5 bg-white text-indigo-700 font-bold rounded-full hover:bg-indigo-50 transition-all shadow-xl hover:shadow-white/20 hover:scale-[1.02] disabled:opacity-70 text-sm whitespace-nowrap">
                <span v-if="loading">Subscribing...</span>
                <span v-else>Subscribe Free →</span>
              </button>
            </form>

            <div v-if="message" class="text-sm" :class="status === 'success' ? 'text-white font-semibold' : 'text-rose-200'">
              {{ message }}
            </div>

            <p class="text-indigo-200/60 text-xs">No spam ever. Unsubscribe anytime.</p>
          </div>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>
