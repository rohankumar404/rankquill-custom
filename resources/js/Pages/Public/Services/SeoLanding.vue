<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, computed } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
  service: { type: Object, required: true }
})

// ── SEO Tools Data & Filters ─────────────────────────────────
const activeTab = ref('SEO & Analytics')

const toolTabs = [
  'SEO & Analytics',
  'Keyword Research',
  'Technical SEO',
  'Content & Optimization',
  'Backlink Analysis',
  'Rank Tracking & Reporting',
  'AEO & GEO Tools'
]

const tools = [
  { name: 'Google Search Console', categories: ['SEO & Analytics', 'Rank Tracking & Reporting'], color: '#f4b400' },
  { name: 'Google Analytics 4', categories: ['SEO & Analytics'], color: '#e37400' },
  { name: 'Google Looker Studio', categories: ['SEO & Analytics', 'Rank Tracking & Reporting'], color: '#4285f4' },
  { name: 'Ahrefs', categories: ['Keyword Research', 'Backlink Analysis', 'Rank Tracking & Reporting'], color: '#ff7a59' },
  { name: 'SEMrush', categories: ['Keyword Research', 'Backlink Analysis', 'Rank Tracking & Reporting'], color: '#ff642d' },
  { name: 'Moz Pro', categories: ['Keyword Research', 'Backlink Analysis', 'Rank Tracking & Reporting'], color: '#00b4f1' },
  { name: 'Screaming Frog', categories: ['Technical SEO'], color: '#448b2c' },
  { name: 'Sitebulb', categories: ['Technical SEO'], color: '#3f1f69' },
  { name: 'Ubersuggest', categories: ['Keyword Research'], color: '#e76f51' },
  { name: 'KeywordTool.io', categories: ['Keyword Research'], color: '#2a9d8f' },
  { name: 'Surfer SEO', categories: ['Content & Optimization'], color: '#ff3b30' },
  { name: 'Clearscope', categories: ['Content & Optimization'], color: '#264653' },
  { name: 'Frase', categories: ['Content & Optimization'], color: '#5e50f9' },
  { name: 'MarketMuse', categories: ['Content & Optimization'], color: '#00c2ff' },
  { name: 'PageSpeed Insights', categories: ['Technical SEO'], color: '#00c48c' },
  { name: 'GTMetrix', categories: ['Technical SEO'], color: '#005ea6' },
  { name: 'Schema Validator', categories: ['Technical SEO'], color: '#d32f2f' },
  { name: 'Merlin', categories: ['Content & Optimization', 'AEO & GEO Tools'], color: '#7c3aed' },
  { name: 'AlsoAsked', categories: ['Keyword Research', 'AEO & GEO Tools'], color: '#009688' },
  { name: 'AnswerThePublic', categories: ['Keyword Research'], color: '#ffb703' },
  { name: 'Perplexity', categories: ['AEO & GEO Tools'], color: '#00a896' },
  { name: 'ChatGPT', categories: ['Content & Optimization', 'AEO & GEO Tools'], color: '#10a37f' },
  { name: 'Gemini', categories: ['Content & Optimization', 'AEO & GEO Tools'], color: '#1a73e8' },
  { name: 'Bing Webmaster Tools', categories: ['SEO & Analytics', 'Technical SEO'], color: '#008375' }
]

const filteredTools = computed(() => {
  return tools.filter(t => t.categories.includes(activeTab.value))
})

const getToolLogo = (name) => {
  const logos = {
    'Google Search Console': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#4285F4" opacity="0.15"/><path d="M12 4L4 8v8l8 4 8-4V8l-8-4z" fill="#4285F4" stroke="#4285F4" stroke-width="1.5"/><circle cx="12" cy="12" r="3" fill="#fff"/></svg>`,
    'Google Analytics 4': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#E37400" opacity="0.15"/><path d="M19 19H5V5" stroke="#E37400" stroke-width="1.5" stroke-linecap="round"/><path d="M9 15v-4M13 15V8M17 15V11" stroke="#E37400" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'Google Looker Studio': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#4285f4" opacity="0.15"/><path d="M12 5v14M7 9h10M9 14h6" stroke="#4285f4" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'Ahrefs': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#ff7a59" opacity="0.15"/><path d="M6 12s2-4 6-4 6 4 6 4" stroke="#ff7a59" stroke-width="1.5"/><path d="M10 16s1.5-2 2-2 2 2 2 2" stroke="#ff7a59" stroke-width="1.5"/></svg>`,
    'SEMrush': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#ff642d" opacity="0.15"/><path d="M8 12a4 4 0 118 0M12 8v8M8 12h8" stroke="#ff642d" stroke-width="1.5"/></svg>`,
    'Moz Pro': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00b4f1" opacity="0.15"/><path d="M6 16V8l6 5 6-5v8" stroke="#00b4f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
    'Screaming Frog': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#448b2c" opacity="0.15"/><circle cx="12" cy="12" r="5" stroke="#448b2c" stroke-width="1.5"/><path d="M9 9l6 6M15 9l-6 6" stroke="#448b2c" stroke-width="1.5"/></svg>`,
    'Sitebulb': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#3f1f69" opacity="0.15"/><path d="M9 16a4 4 0 01-1-2.5C8 11 10 9 12 9s4 2 4 4.5a4 4 0 01-1 2.5m-6 2h4" stroke="#3f1f69" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'Ubersuggest': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#e76f51" opacity="0.15"/><path d="M8 8v5a4 4 0 008 0V8" stroke="#e76f51" stroke-width="2" stroke-linecap="round"/></svg>`,
    'KeywordTool.io': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#2a9d8f" opacity="0.15"/><rect x="6" y="9" width="12" height="7" rx="1" stroke="#2a9d8f" stroke-width="1.5"/><path d="M9 12h6M8 14h8" stroke="#2a9d8f" stroke-width="1.5"/></svg>`,
    'Surfer SEO': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#ff3b30" opacity="0.15"/><path d="M6 10c2 0 3 4 6 4s4-4 6-4" stroke="#ff3b30" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'Clearscope': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#264653" opacity="0.15"/><circle cx="12" cy="12" r="5" stroke="#264653" stroke-width="1.5"/><circle cx="12" cy="12" r="1.5" fill="#264653"/></svg>`,
    'Frase': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#5e50f9" opacity="0.15"/><path d="M7 9h10v2H7V9zm0 4h6v2H7v-2z" fill="#5e50f9"/></svg>`,
    'MarketMuse': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00c2ff" opacity="0.15"/><path d="M7 15l3-6 4 6 3-6" stroke="#00c2ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
    'PageSpeed Insights': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00c48c" opacity="0.15"/><circle cx="12" cy="13" r="5" stroke="#00c48c" stroke-width="1.5"/><path d="M12 13l3-3" stroke="#00c48c" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'GTMetrix': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#005ea6" opacity="0.15"/><path d="M6 14h12M9 11v3M12 8v6M15 10v4" stroke="#005ea6" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'Schema Validator': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#d32f2f" opacity="0.15"/><path d="M8 9l-3 3 3 3M16 9l3 3-3 3M11 7l2 10" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'Merlin': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#7c3aed" opacity="0.15"/><path d="M12 6a6 6 0 016 6c0 3.3-3 6-6 6s-6-2.7-6-6a6 6 0 016-6z" stroke="#7c3aed" stroke-width="1.5"/></svg>`,
    'AlsoAsked': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#009688" opacity="0.15"/><path d="M12 7v4m0 0L9 14m3-3l3 3" stroke="#009688" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'AnswerThePublic': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#ffb703" opacity="0.15"/><path d="M8 12a4 4 0 108 0 4 4 0 00-8 0z" stroke="#ffb703" stroke-width="1.5"/><circle cx="12" cy="12" r="1.5" fill="#ffb703"/></svg>`,
    'Perplexity': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00a896" opacity="0.15"/><path d="M12 7v10M7 12h10" stroke="#00a896" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'ChatGPT': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#10a37f" opacity="0.15"/><path d="M12 8a4 4 0 00-4 4c0 1.5 1 2.5 2 3l-1 2 3-1.5 3 1.5-1-2c1-.5 2-1.5 2-3a4 4 0 00-4-4z" stroke="#10a37f" stroke-width="1.5" fill="none"/></svg>`,
    'Gemini': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#1a73e8" opacity="0.15"/><path d="M12 6l1.5 4.5L18 12l-4.5 1.5L12 18l-1.5-4.5L6 12l4.5-1.5L12 6z" fill="#1a73e8"/></svg>`,
    'Bing Webmaster Tools': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#008375" opacity="0.15"/><path d="M8 8l4 8 4-8" stroke="#008375" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`
  }
  return logos[name] || logos['Google Search Console']
}

// ── Interactive Counter States ───────────────────────────────
const animatedStats = ref({
  organicTraffic: 0,
  clients: 0,
  keywords: 0,
  trafficIncrease: 0,
  retention: 0
})

const statsObserved = ref(false)
const statsInView = ref(false)

const animateValue = (obj, key, start, end, duration) => {
  let startTimestamp = null
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp
    const progress = Math.min((timestamp - startTimestamp) / duration, 1)
    obj[key] = Math.floor(progress * (end - start) + start)
    if (progress < 1) {
      window.requestAnimationFrame(step)
    } else {
      obj[key] = end
    }
  }
  window.requestAnimationFrame(step)
}

// ── Scroll Reveal Logic ──────────────────────────────────────
const observers = []
onMounted(() => {
  // Reveal elements logic
  document.querySelectorAll('[data-rev]').forEach(el => {
    const delay = parseFloat(el.dataset.delay || 0)
    el.style.opacity = '0'
    el.style.transform = 'translateY(24px)'
    el.style.transition = `opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s, transform 0.65s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s`
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.opacity = '1'
          e.target.style.transform = 'translateY(0)'
          obs.unobserve(e.target)
        }
      })
    }, { threshold: 0.05 })
    obs.observe(el)
    observers.push(obs)
  })

  // Hero Donut animation starts immediately
  animateValue(animatedStats.value, 'organicTraffic', 0, 175, 2000)

  // Why Choose Us Stats Observer
  const statsEl = document.querySelector('.seo-why-us__stats-container')
  if (statsEl) {
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          statsInView.value = true
          if (!statsObserved.value) {
            statsObserved.value = true
            animateValue(animatedStats.value, 'clients', 0, 250, 2000)
            animateValue(animatedStats.value, 'keywords', 0, 100, 2000) // 100 represents 1M (val/100)
            animateValue(animatedStats.value, 'trafficIncrease', 0, 300, 2000)
            animateValue(animatedStats.value, 'retention', 0, 98, 2000)
          }
        }
      })
    }, { threshold: 0.1 })
    obs.observe(statsEl)
    observers.push(obs)
  }
})

onUnmounted(() => {
  observers.forEach(o => o.disconnect())
})
</script>

<template>
  <Head>
    <title>{{ service.seo?.meta_title || 'Search Engine Optimization (SEO) Services' }} — RankQuill</title>
    <meta name="description" :content="service.seo?.meta_description || 'Premium SEO services that drive organic traffic, top keyword rankings, and compounding business revenue.'" />
    <meta name="robots" content="index, follow" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  </Head>

  <PublicLayout>
    <div class="seo-page">

      <!-- ═══════════════════════════════════════════════════════
           SECTION 1 – HERO
      ═══════════════════════════════════════════════════════ -->
      <section class="seo-hero">
        <!-- Background Effects -->
        <div class="seo-hero__bg-dots"></div>
        <div class="seo-hero__glow-1"></div>
        <div class="seo-hero__glow-2"></div>

        <div class="seo-container">
          <div class="seo-hero__wrapper">
            
            <!-- Left Info column (45%) -->
            <div class="seo-hero__content">
              <!-- Breadcrumb -->
              <div class="seo-breadcrumb">
                <Link href="/" class="seo-breadcrumb__link">Home</Link>
                <span class="seo-breadcrumb__sep">&gt;</span>
                <Link href="/services" class="seo-breadcrumb__link">Services</Link>
                <span class="seo-breadcrumb__sep">&gt;</span>
                <span class="seo-breadcrumb__active">Search Engine Optimization</span>
              </div>

              <!-- Category Badge -->
              <div class="seo-hero__badge-wrap">
                <span class="seo-badge">Search Engine Optimization</span>
              </div>

              <!-- Headline -->
              <h1 class="seo-hero__title">
                Rank Higher.<br>
                Get Found.<br>
                <span class="seo-hero__title--highlight">Grow Faster.</span>
              </h1>

              <!-- Description -->
              <p class="seo-hero__desc">
                Data-driven SEO strategies that improve rankings, drive qualified traffic, and deliver measurable growth for your business.
              </p>

              <!-- SEO Benefits Row -->
              <div class="seo-benefits">
                <div class="seo-benefit-item">
                  <span class="seo-benefit-item__icon-wrap">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                  </span>
                  <span class="seo-benefit-item__label">Higher Rankings</span>
                </div>
                <div class="seo-benefit-item">
                  <span class="seo-benefit-item__icon-wrap">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </span>
                  <span class="seo-benefit-item__label">More Traffic</span>
                </div>
                <div class="seo-benefit-item">
                  <span class="seo-benefit-item__icon-wrap">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </span>
                  <span class="seo-benefit-item__label">Better Conversions</span>
                </div>
                <div class="seo-benefit-item">
                  <span class="seo-benefit-item__icon-wrap">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                  </span>
                  <span class="seo-benefit-item__label">Long-Term Growth</span>
                </div>
              </div>

              <!-- CTA Buttons -->
              <div class="seo-hero__btns">
                <Link href="/contact-us" class="seo-btn seo-btn--primary">
                  Get a Free SEO Audit
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </Link>
                <a href="#packages" class="seo-btn seo-btn--outline">
                  View Our SEO Packages
                  <span class="seo-btn__circle">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                  </span>
                </a>
              </div>
            </div>

            <!-- Right Visual Column (55%) -->
            <div class="seo-hero__visual">
              <div class="seo-dashboard-stack">
                
                <!-- DASHBOARD CARD 1: Google Rankings -->
                <div class="seo-db-card seo-db-card--rankings">
                  <div class="seo-db-card__header">
                    <div class="seo-db-card__brand">
                      <!-- Google 'G' Symbol Icon -->
                      <span class="seo-google-g">G</span>
                      <span class="seo-db-card__brand-title">Google Rankings</span>
                    </div>
                  </div>
                  <div class="seo-db-card__body-split">
                    <div class="seo-db-card__stat-group">
                      <div class="seo-db-card__number-highlight font-green">#1</div>
                      <div class="seo-db-card__number-sub">Position</div>
                      <div class="seo-db-card__trend-pill">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        +20 vs last month
                      </div>
                    </div>
                    <div class="seo-db-card__chart-wrap">
                      <svg viewBox="0 0 200 100" class="seo-svg-chart">
                        <!-- Curved Line Graph -->
                        <path d="M10 80 Q 40 70, 70 45 T 130 50 T 190 20" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" class="line-drawing-anim"/>
                        <circle cx="190" cy="20" r="4" fill="#10b981"/>
                        <path d="M10 80 Q 40 70, 70 45 T 130 50 T 190 20 L 190 100 L 10 100 Z" fill="url(#greenGrad)" opacity="0.1"/>
                        <defs>
                          <linearGradient id="greenGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10b981"/>
                            <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                          </linearGradient>
                        </defs>
                      </svg>
                    </div>
                  </div>
                </div>

                <div class="seo-dashboard-row">
                  <!-- DASHBOARD CARD 2: Organic Traffic (Donut Chart) -->
                  <div class="seo-db-card seo-db-card--half">
                    <div class="seo-db-card__title">Organic Traffic</div>
                    <div class="seo-donut-widget">
                      <div class="seo-donut-chart">
                        <svg class="seo-donut-svg" viewBox="0 0 100 100">
                          <!-- Background track -->
                          <circle cx="50" cy="50" r="40" stroke="#f1f5f9" stroke-width="12" fill="transparent"/>
                          <!-- Active segment -->
                          <circle cx="50" cy="50" r="40" stroke="#3b82f6" stroke-width="12" fill="transparent"
                                  stroke-dasharray="251.2" :stroke-dashoffset="251.2 - (251.2 * 0.75)"
                                  stroke-linecap="round" class="donut-circle-anim"/>
                        </svg>
                        <div class="seo-donut-center">
                          <span class="seo-donut-number">+{{ animatedStats.organicTraffic }}%</span>
                        </div>
                      </div>
                      <div class="seo-donut-legend">
                        <div class="seo-legend-item"><span class="seo-legend-dot bg-blue"></span>Organic Search</div>
                        <div class="seo-legend-item"><span class="seo-legend-dot bg-lightblue"></span>Direct</div>
                        <div class="seo-legend-item"><span class="seo-legend-dot bg-orange"></span>Referral</div>
                        <div class="seo-legend-item"><span class="seo-legend-dot bg-gray"></span>Social</div>
                      </div>
                    </div>
                  </div>

                  <!-- DASHBOARD CARD 3: Keyword Rankings (Bar Chart) -->
                  <div class="seo-db-card seo-db-card--half">
                    <div class="seo-db-card__title">Keyword Rankings</div>
                    <div class="seo-bar-widget">
                      <div class="seo-bar-header">
                        <span class="seo-bar-number">1,248</span>
                        <span class="seo-bar-trend">+286 vs last month</span>
                      </div>
                      <div class="seo-bar-chart">
                        <div v-for="(h, idx) in [30, 45, 35, 60, 50, 75, 90]" :key="idx" 
                             class="seo-bar-item" 
                             :style="{ height: h + '%', animationDelay: (idx * 0.1) + 's' }"></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- BOTTOM SEO PILL CARDS -->
                <div class="seo-pills-row">
                  <div class="seo-pill-card pill-aeo">
                    <div class="seo-pill-card__icon-wrap">
                      <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div class="seo-pill-card__info">
                      <span class="seo-pill-card__title font-green">AEO</span>
                      <span class="seo-pill-card__desc">Answer Engine Optimization</span>
                    </div>
                  </div>
                  <div class="seo-pill-card pill-geo">
                    <div class="seo-pill-card__icon-wrap">
                      <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div class="seo-pill-card__info">
                      <span class="seo-pill-card__title font-blue">GEO</span>
                      <span class="seo-pill-card__desc">Generative Engine Optimization</span>
                    </div>
                  </div>
                  <div class="seo-pill-card pill-seo">
                    <div class="seo-pill-card__icon-wrap">
                      <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div class="seo-pill-card__info">
                      <span class="seo-pill-card__title font-purple">SEO</span>
                      <span class="seo-pill-card__desc">Search Engine Optimization</span>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 2 – SEO PROCESS
      ═══════════════════════════════════════════════════════ -->
      <section class="seo-section bg-slate-50 border-t border-b border-slate-100">
        <div class="seo-container">
          <div class="seo-section-header" data-rev>
            <h2 class="seo-section-title">Our SEO Process</h2>
            <p class="seo-section-sub">A proven process to improve rankings and drive sustainable growth.</p>
          </div>

          <div class="seo-process-grid">
            
            <!-- Process Step 1 -->
            <div class="seo-process-item" data-rev data-delay="0.1">
              <div class="seo-process-card">
                <div class="seo-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="seo-process-card__number">01</span>
                <h3 class="seo-process-card__title">Website Audit</h3>
                <p class="seo-process-card__desc">We analyze your website's health, performance, and SEO issues.</p>
              </div>
              <div class="seo-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Process Step 2 -->
            <div class="seo-process-item" data-rev data-delay="0.2">
              <div class="seo-process-card">
                <div class="seo-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <span class="seo-process-card__number">02</span>
                <h3 class="seo-process-card__title">Keyword Research</h3>
                <p class="seo-process-card__desc">We find high-value keywords that your audience is searching for.</p>
              </div>
              <div class="seo-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Process Step 3 -->
            <div class="seo-process-item" data-rev data-delay="0.3">
              <div class="seo-process-card">
                <div class="seo-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <span class="seo-process-card__number">03</span>
                <h3 class="seo-process-card__title">Strategy & Planning</h3>
                <p class="seo-process-card__desc">We create a customized SEO strategy aligned with your business goals.</p>
              </div>
              <div class="seo-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Process Step 4 -->
            <div class="seo-process-item" data-rev data-delay="0.4">
              <div class="seo-process-card">
                <div class="seo-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <span class="seo-process-card__number">04</span>
                <h3 class="seo-process-card__title">On-Page Optimization</h3>
                <p class="seo-process-card__desc">We optimize your content, meta tags, headings, and internal linking.</p>
              </div>
              <div class="seo-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Process Step 5 -->
            <div class="seo-process-item" data-rev data-delay="0.5">
              <div class="seo-process-card">
                <div class="seo-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <span class="seo-process-card__number">05</span>
                <h3 class="seo-process-card__title">Off-Page Optimization</h3>
                <p class="seo-process-card__desc">We build high-quality backlinks and improve your website authority.</p>
              </div>
              <div class="seo-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Process Step 6 -->
            <div class="seo-process-item" data-rev data-delay="0.6">
              <div class="seo-process-card">
                <div class="seo-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="seo-process-card__number">06</span>
                <h3 class="seo-process-card__title">Track & Improve</h3>
                <p class="seo-process-card__desc">We monitor performance, track rankings, and optimize strategies for growth.</p>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 3 – COMPREHENSIVE SEO SERVICES
      ═══════════════════════════════════════════════════════ -->
      <section class="seo-section bg-white">
        <div class="seo-container">
          <div class="seo-section-header" data-rev>
            <h2 class="seo-section-title">Comprehensive SEO Services</h2>
            <p class="seo-section-sub">End-to-end solutions to improve visibility, attract traffic & grow your business.</p>
          </div>

          <div class="seo-services-grid">
            
            <!-- Service 1 -->
            <div class="seo-service-card" data-rev data-delay="0.05">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
              </div>
              <h3 class="seo-service-card__title">Technical SEO</h3>
              <p class="seo-service-card__desc">Improve website crawlability, indexing, site speed, Core Web Vitals, and mobile usability.</p>
            </div>

            <!-- Service 2 -->
            <div class="seo-service-card" data-rev data-delay="0.1">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </div>
              <h3 class="seo-service-card__title">On-Page SEO</h3>
              <p class="seo-service-card__desc">Optimize content, meta tags, headings, images, and internal links for better rankings.</p>
            </div>

            <!-- Service 3 -->
            <div class="seo-service-card" data-rev data-delay="0.15">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
              </div>
              <h3 class="seo-service-card__title">Off-Page SEO</h3>
              <p class="seo-service-card__desc">Build high-quality backlinks, improve domain authority, and boost brand mentions.</p>
            </div>

            <!-- Service 4 -->
            <div class="seo-service-card" data-rev data-delay="0.2">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <h3 class="seo-service-card__title">Local SEO</h3>
              <p class="seo-service-card__desc">Rank higher in local searches and Google Maps to attract nearby customers.</p>
            </div>

            <!-- Service 5 -->
            <div class="seo-service-card" data-rev data-delay="0.25">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
              </div>
              <h3 class="seo-service-card__title">E-Commerce SEO</h3>
              <p class="seo-service-card__desc">Optimize product, category, and collection pages to increase organic sales.</p>
            </div>

            <!-- Service 6 -->
            <div class="seo-service-card" data-rev data-delay="0.3">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
              </div>
              <h3 class="seo-service-card__title">AEO (Answer Engine Optimization)</h3>
              <p class="seo-service-card__desc">Optimize content to appear in direct answers, featured snippets, and People Also Ask.</p>
            </div>

            <!-- Service 7 -->
            <div class="seo-service-card" data-rev data-delay="0.35">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
              </div>
              <h3 class="seo-service-card__title">GEO (Generative Engine Optimization)</h3>
              <p class="seo-service-card__desc">Optimize for AI search platforms and LLMs like ChatGPT, Perplexity, Gemini, and more.</p>
            </div>

            <!-- Service 8 -->
            <div class="seo-service-card" data-rev data-delay="0.4">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5l-5 5v-5z"/></svg>
              </div>
              <h3 class="seo-service-card__title">Content SEO</h3>
              <p class="seo-service-card__desc">Create and optimize high-quality content that ranks, engages, and converts visitors.</p>
            </div>

            <!-- Service 9 -->
            <div class="seo-service-card" data-rev data-delay="0.45">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
              </div>
              <h3 class="seo-service-card__title">Keyword Research</h3>
              <p class="seo-service-card__desc">Discover high-intent, high-value keywords with low competition and high volume.</p>
            </div>

            <!-- Service 10 -->
            <div class="seo-service-card" data-rev data-delay="0.5">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
              </div>
              <h3 class="seo-service-card__title">Competitor Analysis</h3>
              <p class="seo-service-card__desc">Analyze competitors' SEO strategies, keyword rankings, and find growth opportunities.</p>
            </div>

            <!-- Service 11 -->
            <div class="seo-service-card" data-rev data-delay="0.55">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <h3 class="seo-service-card__title">SEO Audit & Reporting</h3>
              <p class="seo-service-card__desc">In-depth website audits with actionable insights and regular, transparent performance reports.</p>
            </div>

            <!-- Service 12 -->
            <div class="seo-service-card" data-rev data-delay="0.6">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <h3 class="seo-service-card__title">International SEO</h3>
              <p class="seo-service-card__desc">Optimize your website for multiple languages, search engines, and global markets.</p>
            </div>

            <!-- Service 13 -->
            <div class="seo-service-card" data-rev data-delay="0.65">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
              </div>
              <h3 class="seo-service-card__title">Voice Search Optimization</h3>
              <p class="seo-service-card__desc">Optimize for conversational queries, long-tail keywords, and digital assistants.</p>
            </div>

            <!-- Service 14 -->
            <div class="seo-service-card" data-rev data-delay="0.7">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6m-6 4h6m-6 4h6"/></svg>
              </div>
              <h3 class="seo-service-card__title">Schema Markup</h3>
              <p class="seo-service-card__desc">Implement structured schema data to display rich snippets and boost search visibility.</p>
            </div>

            <!-- Service 15 -->
            <div class="seo-service-card" data-rev data-delay="0.75">
              <div class="seo-service-card__icon-wrap">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <h3 class="seo-service-card__title">SEO Consulting</h3>
              <p class="seo-service-card__desc">Get expert direction, roadmap consulting, and bespoke organic strategies for growth.</p>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 4 – SEO TOOLS WE USE
      ═══════════════════════════════════════════════════════ -->
      <section class="seo-section bg-slate-50 border-t border-b border-slate-100">
        <div class="seo-container">
          <div class="seo-section-header" data-rev>
            <h2 class="seo-section-title">SEO Tools We Use</h2>
            <p class="seo-section-sub">Industry-leading tools to analyze, optimize, and track your SEO performance.</p>
          </div>

          <!-- Filter Tabs -->
          <div class="seo-tabs" data-rev>
            <button v-for="tab in toolTabs" :key="tab"
                    @click="activeTab = tab"
                    :class="['seo-tab', activeTab === tab ? 'seo-tab--active' : '']">
              {{ tab }}
            </button>
          </div>

          <!-- Tool Grid -->
          <div class="seo-tools-grid">
            <div v-for="tool in filteredTools" :key="tool.name" class="seo-tool-card" data-rev>
              <div class="seo-tool-card__logo" v-html="getToolLogo(tool.name)"></div>
              <span class="seo-tool-card__name">{{ tool.name }}</span>
            </div>
          </div>

        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 5 – WHY CHOOSE US
      ═══════════════════════════════════════════════════════ -->
      <section class="seo-section bg-white seo-why-us__stats-container">
        <div class="seo-container">
          <div class="seo-why-us__grid">
            
            <!-- Left Side: Copy/Features -->
            <div class="seo-why-us__content" data-rev>
              <h2 class="seo-why-us__title">Why Choose Us</h2>
              
              <div class="seo-why-us__features">
                <div class="seo-why-us__feature-item">
                  <div class="seo-why-us__feature-icon">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <div>
                    <h3 class="seo-why-us__feature-title">Data-Driven Strategies</h3>
                    <p class="seo-why-us__feature-desc">We use data and insights to create custom search engine strategies that deliver measurable results.</p>
                  </div>
                </div>

                <div class="seo-why-us__feature-item">
                  <div class="seo-why-us__feature-icon">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <div>
                    <h3 class="seo-why-us__feature-title">Transparent Reporting</h3>
                    <p class="seo-why-us__feature-desc">Get clear, easy-to-understand performance reports with real-time status and ranking updates.</p>
                  </div>
                </div>

                <div class="seo-why-us__feature-item">
                  <div class="seo-why-us__feature-icon">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <div>
                    <h3 class="seo-why-us__feature-title">White-Hat SEO</h3>
                    <p class="seo-why-us__feature-desc">We strictly practice ethical white-hat SEO methodologies to ensure long-term and organic algorithmic safety.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Side: Stats & Visual Card -->
            <div class="seo-why-us__visual" data-rev data-delay="0.2">
              <div class="seo-stats-card">
                <div class="seo-stats-card__label">Proven Results</div>
                
                <!-- Stats row -->
                <div class="seo-stats-card__grid">
                  <div class="seo-stats-card__item">
                    <div class="seo-stats-card__number">{{ animatedStats.clients }}+</div>
                    <div class="seo-stats-card__caption">Happy Clients</div>
                  </div>
                  <div class="seo-stats-card__item">
                    <div class="seo-stats-card__number">{{ animatedStats.keywords ? (animatedStats.keywords / 100).toFixed(1) + 'M' : '0' }}+</div>
                    <div class="seo-stats-card__caption">Keywords Ranked</div>
                  </div>
                  <div class="seo-stats-card__item">
                    <div class="seo-stats-card__number">{{ animatedStats.trafficIncrease }}%</div>
                    <div class="seo-stats-card__caption">Avg. Traffic Increase</div>
                  </div>
                  <div class="seo-stats-card__item">
                    <div class="seo-stats-card__number">{{ animatedStats.retention }}%</div>
                    <div class="seo-stats-card__caption">Client Retention</div>
                  </div>
                </div>

                <!-- Graph Area -->
                <div class="seo-stats-card__graph-box">
                  <div class="seo-stats-card__graph">
                    <!-- Dynamic growth line and bar elements -->
                    <div class="seo-stats-card__bars">
                      <div v-for="(h, idx) in [25, 40, 35, 60, 55, 80, 100]" :key="idx"
                           class="seo-stats-card__bar-item"
                           :class="{ 'in-view': statsInView }"
                           :style="{ height: h + '%', transitionDelay: (idx * 0.1) + 's' }"></div>
                    </div>
                    
                    <svg viewBox="0 0 300 120" class="seo-stats-card__line-svg">
                      <!-- Curve drawing on viewport entry -->
                      <path d="M10 100 Q 60 85, 110 60 T 210 50 T 290 20" fill="none" stroke="#3b82f6" stroke-width="4" stroke-linecap="round"
                            class="stats-line" :class="{ 'in-view': statsInView }"/>
                      <circle cx="290" cy="20" r="5" fill="#3b82f6" opacity="0.8"/>
                    </svg>
                  </div>
                </div>

                <!-- Text block -->
                <div class="seo-stats-card__message">
                  <h4 class="seo-stats-card__message-title">More Rankings. More Traffic. More Business.</h4>
                  <p class="seo-stats-card__message-desc">We help you stay ahead of the competition and achieve top rankings that drive real business growth.</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 6 – CTA BANNER
      ═══════════════════════════════════════════════════════ -->
      <section class="seo-section bg-white pt-4 pb-16">
        <div class="seo-container">
          <div class="seo-cta-banner cta-banner-anim" data-rev>
            <!-- Abstract background shape overlay -->
            <div class="seo-cta-banner__illustration">
              <svg viewBox="0 0 400 180" class="w-full h-full opacity-10">
                <path d="M50 150 L100 120 L150 130 L200 80 L250 90 L300 40 L350 50" stroke="#fff" stroke-width="6" fill="none" stroke-linecap="round"/>
                <circle cx="350" cy="50" r="8" fill="#fff"/>
              </svg>
            </div>

            <div class="seo-cta-banner__content">
              <div class="seo-cta-banner__left">
                <h2 class="seo-cta-banner__title">Ready to Rank Higher & Get More Traffic?</h2>
                <p class="seo-cta-banner__subtitle">Let's create an SEO strategy that drives real results for your business.</p>
              </div>
              <div class="seo-cta-banner__right">
                <Link href="/contact-us" class="seo-cta-button">
                  Get a Free SEO Audit
                  <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  </PublicLayout>
</template>

<style scoped>
/* ── Global Styles & Design System ───────────────────────── */
.seo-page {
  font-family: 'Inter', system-ui, sans-serif;
  color: #1e293b;
  overflow-x: hidden;
  background-color: #ffffff;
}

.seo-container {
  width: 100%;
  max-width: 1320px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 20px;
  padding-right: 20px;
}

.seo-section {
  padding-top: 56px;
  padding-bottom: 56px;
}
@media (min-width: 768px) {
  .seo-section {
    padding-top: 88px;
    padding-bottom: 88px;
  }
}

.seo-section-header {
  text-align: center;
  margin-bottom: 48px;
}
.seo-section-title {
  font-size: 32px;
  font-weight: 700;
  color: #0f172a;
  line-height: 1.15;
  letter-spacing: -0.02em;
}
@media (min-width: 768px) {
  .seo-section-title {
    font-size: 42px;
  }
}
.seo-section-sub {
  font-size: 15px;
  color: #64748b;
  margin-top: 12px;
  max-width: 580px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
}

/* Common Brand Colors */
.font-green { color: #10b981; }
.font-blue { color: #2563eb; }
.font-purple { color: #7c3aed; }

/* ═══════════════════════════════════════════════════════════
   SECTION 1 – HERO
   ═══════════════════════════════════════════════════════════ */
.seo-hero {
  background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 100%);
  position: relative;
  overflow: hidden;
  padding-top: 100px;
  padding-bottom: 56px;
}
@media (min-width: 1024px) {
  .seo-hero {
    padding-top: 140px;
    padding-bottom: 96px;
  }
}

/* Subtle background effects */
.seo-hero__bg-dots {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(rgba(37, 99, 235, 0.04) 1.5px, transparent 1.5px);
  background-size: 24px 24px;
  pointer-events: none;
}
.seo-hero__glow-1 {
  position: absolute;
  top: -150px;
  right: -100px;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
  pointer-events: none;
}
.seo-hero__glow-2 {
  position: absolute;
  bottom: -100px;
  left: -150px;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
  pointer-events: none;
}

/* Hero structural setup */
.seo-hero__wrapper {
  display: grid;
  grid-template-columns: 1fr;
  gap: 48px;
  align-items: center;
}
@media (min-width: 1024px) {
  .seo-hero__wrapper {
    grid-template-columns: 45% 55%;
  }
}

.seo-hero__content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

/* Breadcrumbs */
.seo-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #94a3b8;
  margin-bottom: 24px;
  flex-wrap: wrap;
}
.seo-breadcrumb__link {
  color: #94a3b8;
  text-decoration: none;
  transition: color 0.2s;
}
.seo-breadcrumb__link:hover {
  color: #2563eb;
}
.seo-breadcrumb__sep {
  color: #cbd5e1;
}
.seo-breadcrumb__active {
  color: #64748b;
  font-weight: 500;
}

/* Pill badge styling */
.seo-hero__badge-wrap {
  margin-bottom: 24px;
}
.seo-badge {
  display: inline-flex;
  align-items: center;
  background-color: rgba(37, 99, 235, 0.07);
  border: 1.5px solid rgba(37, 99, 235, 0.15);
  color: #2563eb;
  font-size: 14px;
  font-weight: 600;
  padding: 6px 16px;
  border-radius: 100px;
}

/* Hero Typography scale */
.seo-hero__title {
  font-size: 38px;
  font-weight: 700;
  line-height: 1.1;
  color: #0f172a;
  letter-spacing: -0.03em;
  margin-bottom: 18px;
}
@media (min-width: 768px) {
  .seo-hero__title {
    font-size: 54px;
  }
}
@media (min-width: 1280px) {
  .seo-hero__title {
    font-size: 64px;
  }
}
.seo-hero__title--highlight {
  color: #2563eb;
}

.seo-hero__desc {
  font-size: 16px;
  line-height: 1.65;
  color: #64748b;
  max-width: 550px;
  margin-bottom: 32px;
}

/* Benefits Row */
.seo-benefits {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  width: 100%;
  margin-bottom: 40px;
}
@media (max-width: 480px) {
  .seo-benefits {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}
.seo-benefit-item {
  display: flex;
  align-items: center;
  gap: 10px;
}
.seo-benefit-item__icon-wrap {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background-color: rgba(37, 99, 235, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.seo-benefit-item__label {
  font-size: 15px;
  font-weight: 600;
  color: #334155;
}

/* Hero CTA Buttons */
.seo-hero__btns {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  width: 100%;
}
.seo-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  font-size: 15px;
  font-weight: 700;
  height: 56px;
  padding: 0 28px;
  border-radius: 14px;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  text-decoration: none;
}
.seo-btn--primary {
  background-color: #2563eb;
  color: #ffffff;
  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
}
.seo-btn--primary:hover {
  transform: scale(1.03);
  box-shadow: 0 20px 50px rgba(37, 99, 235, 0.35);
}
.seo-btn--outline {
  background-color: #ffffff;
  color: #2563eb;
  border: 1.5px solid rgba(37, 99, 235, 0.15);
}
.seo-btn--outline:hover {
  background-color: rgba(37, 99, 235, 0.03);
  border-color: #2563eb;
  transform: scale(1.03);
  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.1);
}
.seo-btn__circle {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(37, 99, 235, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Hero Dashboard Widgets Stack */
.seo-hero__visual {
  width: 100%;
}
.seo-dashboard-stack {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 100%;
}

.seo-db-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  animation: floatCard 6s ease-in-out infinite alternate;
}
.seo-db-card:hover {
  box-shadow: 0 20px 50px rgba(37, 99, 235, 0.15);
}

/* Animations for floating cards */
@keyframes floatCard {
  0% { transform: translateY(0px); }
  100% { transform: translateY(-10px); }
}

.seo-db-card--rankings {
  animation-delay: 0s;
}
.seo-db-card--half {
  flex: 1;
}
.seo-db-card--half:nth-child(1) {
  animation-delay: 0.5s;
}
.seo-db-card--half:nth-child(2) {
  animation-delay: 1s;
}

.seo-db-card__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}
.seo-db-card__brand {
  display: flex;
  align-items: center;
  gap: 8px;
}
.seo-google-g {
  width: 24px;
  height: 24px;
  background-color: #f1f5f9;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  color: #4285F4;
  font-size: 14px;
}
.seo-db-card__brand-title {
  font-size: 14px;
  font-weight: 600;
  color: #64748b;
}

.seo-db-card__body-split {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 24px;
}
.seo-db-card__stat-group {
  display: flex;
  flex-direction: column;
}
.seo-db-card__number-highlight {
  font-size: 44px;
  font-weight: 800;
  line-height: 1;
}
.seo-db-card__number-sub {
  font-size: 14px;
  color: #64748b;
  font-weight: 500;
  margin-top: 4px;
  margin-bottom: 8px;
}
.seo-db-card__trend-pill {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  font-size: 12px;
  font-weight: 700;
  color: #10b981;
  background-color: rgba(16, 185, 129, 0.08);
  padding: 4px 8px;
  border-radius: 100px;
}

.seo-db-card__chart-wrap {
  flex: 1;
  max-width: 200px;
  height: 80px;
}
.seo-svg-chart {
  width: 100%;
  height: 100%;
}

.line-drawing-anim {
  stroke-dasharray: 400;
  stroke-dashoffset: 400;
  animation: drawLine 2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
@keyframes drawLine {
  to { stroke-dashoffset: 0; }
}

/* Bottom Row of Dashboard widgets */
.seo-dashboard-row {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
@media (min-width: 640px) {
  .seo-dashboard-row {
    flex-direction: row;
  }
}

.seo-db-card__title {
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 16px;
}

/* Donut Widget */
.seo-donut-widget {
  display: flex;
  align-items: center;
  gap: 20px;
}
.seo-donut-chart {
  position: relative;
  width: 80px;
  height: 80px;
  flex-shrink: 0;
}
.seo-donut-svg {
  transform: rotate(-90deg);
  width: 100%;
  height: 100%;
}
.donut-circle-anim {
  animation: drawDonutCircle 2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
@keyframes drawDonutCircle {
  from { stroke-dashoffset: 251.2; }
}
.seo-donut-center {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.seo-donut-number {
  font-size: 14px;
  font-weight: 800;
  color: #0f172a;
}
.seo-donut-legend {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.seo-legend-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  font-weight: 600;
  color: #64748b;
}
.seo-legend-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
}
.seo-legend-dot.bg-blue { background-color: #3b82f6; }
.seo-legend-dot.bg-lightblue { background-color: #60a5fa; }
.seo-legend-dot.bg-orange { background-color: #f59e0b; }
.seo-legend-dot.bg-gray { background-color: #94a3b8; }

/* Bar Widget */
.seo-bar-widget {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.seo-bar-header {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
}
.seo-bar-number {
  font-size: 24px;
  font-weight: 800;
  color: #0f172a;
}
.seo-bar-trend {
  font-size: 11px;
  font-weight: 700;
  color: #10b981;
}
.seo-bar-chart {
  display: flex;
  align-items: flex-end;
  gap: 6px;
  height: 48px;
}
.seo-bar-item {
  flex: 1;
  background-color: #3b82f6;
  border-radius: 3px 3px 0 0;
  transform-origin: bottom;
  transform: scaleY(0);
  animation: growBar 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes growBar {
  to { transform: scaleY(1); }
}

/* Mini Pills Cards underneath Dashboard */
.seo-pills-row {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
  margin-top: 10px;
}
@media (min-width: 640px) {
  .seo-pills-row {
    grid-template-columns: repeat(3, 1fr);
  }
}
.seo-pill-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 16px;
  padding: 14px 18px;
  display: flex;
  align-items: center;
  gap: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  transition: transform 0.3s ease, border-color 0.3s ease;
}
.seo-pill-card:hover {
  transform: translateY(-4px);
}
.seo-pill-card.pill-aeo:hover { border-color: rgba(16,185,129,0.3); }
.seo-pill-card.pill-geo:hover { border-color: rgba(37,99,235,0.3); }
.seo-pill-card.pill-seo:hover { border-color: rgba(124,58,237,0.3); }

.seo-pill-card__icon-wrap {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.pill-aeo .seo-pill-card__icon-wrap { background-color: rgba(16, 185, 129, 0.08); }
.pill-geo .seo-pill-card__icon-wrap { background-color: rgba(37, 99, 235, 0.08); }
.pill-seo .seo-pill-card__icon-wrap { background-color: rgba(124, 58, 237, 0.08); }

.seo-pill-card__info {
  display: flex;
  flex-direction: column;
}
.seo-pill-card__title {
  font-size: 14px;
  font-weight: 800;
  line-height: 1.2;
}
.seo-pill-card__desc {
  font-size: 11px;
  color: #64748b;
  margin-top: 1px;
  font-weight: 500;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 2 – SEO PROCESS
   ═══════════════════════════════════════════════════════════ */
.seo-process-grid {
  display: flex;
  flex-direction: column;
  gap: 24px;
}
@media (min-width: 1024px) {
  .seo-process-grid {
    flex-direction: row;
    gap: 0;
  }
}

.seo-process-item {
  flex: 1;
  display: flex;
  align-items: center;
  position: relative;
}

.seo-process-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  text-align: center;
  width: 100%;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
  position: relative;
}
.seo-process-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 50px rgba(37, 99, 235, 0.15);
  border-color: rgba(37, 99, 235, 0.2);
}

.seo-process-card__icon-wrap {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background-color: rgba(37, 99, 235, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
  transition: transform 0.3s ease;
}
.seo-process-card:hover .seo-process-card__icon-wrap {
  transform: scale(1.1);
}

.seo-process-card__number {
  position: absolute;
  top: 16px;
  right: 20px;
  font-size: 12px;
  font-weight: 800;
  color: rgba(37, 99, 235, 0.25);
  letter-spacing: 1px;
}

.seo-process-card__title {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}
.seo-process-card__desc {
  font-size: 13px;
  color: #64748b;
  line-height: 1.6;
}

.seo-process-arrow {
  display: none;
  position: absolute;
  right: -13px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 2;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background-color: #ffffff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.06);
  border: 1.5px solid #f1f5f9;
  align-items: center;
  justify-content: center;
}
@media (min-width: 1024px) {
  .seo-process-arrow {
    display: flex;
  }
}

/* ═══════════════════════════════════════════════════════════
   SECTION 3 – COMPREHENSIVE SEO SERVICES
   ═══════════════════════════════════════════════════════ */
.seo-services-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
}
@media (min-width: 640px) {
  .seo-services-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media (min-width: 1024px) {
  .seo-services-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 1280px) {
  .seo-services-grid {
    grid-template-columns: repeat(5, 1fr);
  }
}

.seo-service-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  height: 100%;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.seo-service-card:hover {
  transform: translateY(-8px);
  border-color: rgba(37, 99, 235, 0.25);
  box-shadow: 0 20px 50px rgba(37, 99, 235, 0.15);
}

.seo-service-card__icon-wrap {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background-color: rgba(37, 99, 235, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 18px;
  flex-shrink: 0;
}

.seo-service-card__title {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
  line-height: 1.3;
}
.seo-service-card__desc {
  font-size: 13px;
  color: #64748b;
  line-height: 1.6;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 4 – SEO TOOLS WE USE
   ═══════════════════════════════════════════════════════ */
.seo-tabs {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  margin-bottom: 40px;
}
.seo-tab {
  font-size: 13.5px;
  font-weight: 600;
  padding: 8px 18px;
  border-radius: 100px;
  background-color: #ffffff;
  color: #64748b;
  border: 1.5px solid #f1f5f9;
  cursor: pointer;
  transition: all 0.25s ease;
}
.seo-tab:hover {
  border-color: #2563eb;
  color: #2563eb;
}
.seo-tab--active {
  background-color: #2563eb;
  color: #ffffff;
  border-color: #2563eb;
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
}

.seo-tools-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}
@media (min-width: 640px) {
  .seo-tools-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 768px) {
  .seo-tools-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
@media (min-width: 1024px) {
  .seo-tools-grid {
    grid-template-columns: repeat(6, 1fr);
  }
}

.seo-tool-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 16px;
  padding: 20px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.seo-tool-card:hover {
  transform: scale(1.03);
  box-shadow: 0 12px 30px rgba(37, 99, 235, 0.12);
  border-color: rgba(37, 99, 235, 0.25);
}
.seo-tool-card__logo {
  display: flex;
  align-items: center;
  justify-content: center;
}
.seo-tool-card__name {
  font-size: 13.5px;
  font-weight: 600;
  color: #334155;
  line-height: 1.2;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 5 – WHY CHOOSE US
   ═══════════════════════════════════════════════════════ */
.seo-why-us__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 56px;
  align-items: center;
}
@media (min-width: 1024px) {
  .seo-why-us__grid {
    grid-template-columns: 45% 55%;
  }
}

.seo-why-us__content {
  display: flex;
  flex-direction: column;
}
.seo-why-us__title {
  font-size: 32px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 32px;
}
@media (min-width: 768px) {
  .seo-why-us__title {
    font-size: 42px;
  }
}

.seo-why-us__features {
  display: flex;
  flex-direction: column;
  gap: 28px;
}
.seo-why-us__feature-item {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}
.seo-why-us__feature-icon {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background-color: rgba(37, 99, 235, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 2px;
}
.seo-why-us__feature-title {
  font-size: 18px;
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 6px;
}
.seo-why-us__feature-desc {
  font-size: 15px;
  color: #64748b;
  line-height: 1.6;
}

.seo-why-us__visual {
  width: 100%;
}

/* Premium Stats Card on Right */
.seo-stats-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 24px;
  padding: 32px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
}

.seo-stats-card__label {
  font-size: 14px;
  font-weight: 700;
  color: #2563eb;
  margin-bottom: 24px;
}

.seo-stats-card__grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
  margin-bottom: 32px;
}
@media (min-width: 640px) {
  .seo-stats-card__grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
.seo-stats-card__item {
  display: flex;
  flex-direction: column;
}
.seo-stats-card__number {
  font-size: 32px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.1;
}
.seo-stats-card__caption {
  font-size: 12px;
  color: #64748b;
  font-weight: 600;
  margin-top: 4px;
}

/* Graph Area in Stats Card */
.seo-stats-card__graph-box {
  background-color: #f8fafc;
  border-radius: 16px;
  padding: 24px;
  border: 1.5px solid #f1f5f9;
  margin-bottom: 24px;
}
.seo-stats-card__graph {
  position: relative;
  height: 120px;
  width: 100%;
}

.seo-stats-card__bars {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: flex-end;
  gap: 12px;
  z-index: 1;
}
.seo-stats-card__bar-item {
  flex: 1;
  background-color: rgba(37, 99, 235, 0.08);
  border-radius: 4px 4px 0 0;
  transform-origin: bottom;
  transform: scaleY(0);
  transition: transform 1.5s cubic-bezier(0.16, 1, 0.3, 1);
}
.seo-stats-card__bar-item.in-view {
  transform: scaleY(1);
}

.seo-stats-card__line-svg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  z-index: 2;
  overflow: visible;
}
.stats-line {
  stroke-dasharray: 400;
  stroke-dashoffset: 400;
  transition: stroke-dashoffset 2s cubic-bezier(0.4, 0, 0.2, 1);
}
.stats-line.in-view {
  stroke-dashoffset: 0;
}

/* Message block */
.seo-stats-card__message {
  display: flex;
  flex-direction: column;
}
.seo-stats-card__message-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}
.seo-stats-card__message-desc {
  font-size: 14px;
  color: #64748b;
  line-height: 1.6;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 6 – CTA BANNER
   ═══════════════════════════════════════════════════════ */
.seo-cta-banner {
  position: relative;
  border-radius: 24px;
  padding: 40px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(37, 99, 235, 0.15);
}
@media (min-width: 768px) {
  .seo-cta-banner {
    padding: 48px 64px;
  }
}

.seo-cta-banner__illustration {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 300px;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

/* Animating gradient */
.cta-banner-anim {
  background: linear-gradient(135deg, #2563eb, #3b82f6, #1d4ed8, #2563eb);
  background-size: 300% 300%;
  animation: gradientShift 12s ease infinite;
}
@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.seo-cta-banner__content {
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  gap: 28px;
  align-items: flex-start;
}
@media (min-width: 1024px) {
  .seo-cta-banner__content {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

.seo-cta-banner__left {
  max-width: 600px;
}
.seo-cta-banner__title {
  font-size: 28px;
  font-weight: 700;
  color: #ffffff;
  line-height: 1.2;
  margin-bottom: 8px;
}
@media (min-width: 768px) {
  .seo-cta-banner__title {
    font-size: 34px;
  }
}
.seo-cta-banner__subtitle {
  font-size: 15px;
  color: rgba(255, 255, 255, 0.85);
  line-height: 1.6;
}

.seo-cta-banner__right {
  flex-shrink: 0;
}
.seo-cta-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  font-size: 15px;
  font-weight: 700;
  height: 56px;
  padding: 0 32px;
  border-radius: 14px;
  background-color: #ffffff;
  color: #2563eb;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  text-decoration: none;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}
.seo-cta-button:hover {
  transform: scale(1.05);
  box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
</style>
