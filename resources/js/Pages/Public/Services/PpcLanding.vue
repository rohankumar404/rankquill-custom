<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, computed } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
  service: { type: Object, required: true }
})

// ── PPC Tools Data & Filters ─────────────────────────────────
const activeTab = ref('Ad Platforms')

const toolTabs = [
  'Ad Platforms',
  'Keyword Research',
  'Bid Management',
  'Analytics & Tracking',
  'Competitor Research',
  'Creative & Automation',
  'Reporting'
]

const tools = [
  { name: 'Google Ads', categories: ['Ad Platforms', 'Bid Management'], color: '#4285F4' },
  { name: 'Microsoft Advertising', categories: ['Ad Platforms'], color: '#00A4EF' },
  { name: 'Meta Ads Manager', categories: ['Ad Platforms'], color: '#0668E1' },
  { name: 'YouTube Ads', categories: ['Ad Platforms'], color: '#FF0000' },
  { name: 'LinkedIn Campaign Manager', categories: ['Ad Platforms'], color: '#0077B5' },
  { name: 'TikTok Ads Manager', categories: ['Ad Platforms'], color: '#FE2C55' },
  { name: 'Amazon Ads', categories: ['Ad Platforms'], color: '#FF9900' },
  { name: 'Pinterest Ads', categories: ['Ad Platforms'], color: '#BD081C' },
  { name: 'Google Keyword Planner', categories: ['Keyword Research'], color: '#34A853' },
  { name: 'SEMrush', categories: ['Keyword Research', 'Competitor Research'], color: '#FF642D' },
  { name: 'Ahrefs', categories: ['Keyword Research', 'Competitor Research'], color: '#FF7A59' },
  { name: 'SpyFu', categories: ['Keyword Research', 'Competitor Research'], color: '#4E88F5' },
  { name: 'WordStream', categories: ['Bid Management'], color: '#1B9A59' },
  { name: 'AdEspresso', categories: ['Bid Management'], color: '#314455' },
  { name: 'Optmyzr', categories: ['Bid Management'], color: '#FF4E00' },
  { name: 'Kenshoo', categories: ['Bid Management'], color: '#142C4C' },
  { name: 'Google Analytics', categories: ['Analytics & Tracking'], color: '#E37400' },
  { name: 'Google Tag Manager', categories: ['Analytics & Tracking'], color: '#2488FF' },
  { name: 'Looker Studio', categories: ['Analytics & Tracking', 'Reporting'], color: '#4285F4' },
  { name: 'Supermetrics', categories: ['Analytics & Tracking', 'Reporting'], color: '#E53E3E' },
  { name: 'Zapier', categories: ['Creative & Automation'], color: '#FF4F00' },
  { name: 'CallRail', categories: ['Analytics & Tracking'], color: '#FF8800' },
  { name: 'Hotjar', categories: ['Analytics & Tracking'], color: '#FF2C6D' },
  { name: 'Unbounce', categories: ['Creative & Automation'], color: '#007FFF' },
  { name: 'Screaming Frog', categories: ['Creative & Automation'], color: '#448B2C' },
  { name: 'Adalysis', categories: ['Creative & Automation'], color: '#0D7BFF' },
  { name: 'Moat', categories: ['Competitor Research'], color: '#2B3D52' },
  { name: 'Revealbot', categories: ['Bid Management', 'Creative & Automation'], color: '#5C33F6' },
  { name: 'PPC Ninja', categories: ['Bid Management'], color: '#FF2E93' },
  { name: 'Automate', categories: ['Creative & Automation'], color: '#262D3D' },
  { name: 'Rules.io', categories: ['Creative & Automation'], color: '#00A699' },
  { name: 'Google Merchant Center', categories: ['Creative & Automation'], color: '#FF9900' }
]

const filteredTools = computed(() => {
  return tools.filter(t => t.categories.includes(activeTab.value))
})

const getToolLogo = (name) => {
  const logos = {
    'Google Ads': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#4285F4" opacity="0.15"/><path d="M12 5v14M7 9h10" stroke="#4285F4" stroke-width="1.5"/></svg>`,
    'Microsoft Advertising': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00A4EF" opacity="0.15"/><path d="M6 6h12v12H6z" stroke="#00A4EF" stroke-width="1.5"/></svg>`,
    'Meta Ads Manager': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#0668E1" opacity="0.15"/><path d="M7 12a3 3 0 016 0 3 3 0 01-6 0z" stroke="#0668E1" stroke-width="1.5"/></svg>`,
    'YouTube Ads': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF0000" opacity="0.15"/><path d="M8 8l10 4-10 4V8z" fill="#FF0000"/></svg>`,
    'LinkedIn Campaign Manager': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#0077B5" opacity="0.15"/><path d="M7 7h10v10H7z" stroke="#0077B5" stroke-width="1.5"/></svg>`,
    'TikTok Ads Manager': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FE2C55" opacity="0.15"/><path d="M9 12a3 3 0 106 0" stroke="#FE2C55" stroke-width="1.5"/></svg>`,
    'Amazon Ads': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF9900" opacity="0.15"/><path d="M6 14s3-4 6-4 6 4 6 4" stroke="#FF9900" stroke-width="1.5"/></svg>`,
    'Pinterest Ads': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#BD081C" opacity="0.15"/><circle cx="12" cy="12" r="5" stroke="#BD081C" stroke-width="1.5"/></svg>`,
    'Google Keyword Planner': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#34A853" opacity="0.15"/><path d="M8 8h8v8H8z" stroke="#34A853" stroke-width="1.5"/></svg>`,
    'SEMrush': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF642D" opacity="0.15"/><circle cx="12" cy="12" r="4" stroke="#FF642D" stroke-width="1.5"/></svg>`,
    'Ahrefs': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF7A59" opacity="0.15"/><path d="M7 15l5-7 5 7" stroke="#FF7A59" stroke-width="1.5"/></svg>`,
    'SpyFu': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#4E88F5" opacity="0.15"/><path d="M12 6l3 6H9l3-6z" fill="#4E88F5"/></svg>`,
    'WordStream': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#1B9A59" opacity="0.15"/><path d="M7 12h10M12 7v10" stroke="#1B9A59" stroke-width="1.5"/></svg>`,
    'AdEspresso': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#314455" opacity="0.15"/><rect x="8" y="8" width="8" height="8" rx="1" stroke="#314455" stroke-width="1.5"/></svg>`,
    'Optmyzr': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF4E00" opacity="0.15"/><path d="M7 16l5-8 5 8H7z" fill="#FF4E00"/></svg>`,
    'Kenshoo': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#142C4C" opacity="0.15"/><circle cx="12" cy="12" r="5" stroke="#142C4C" stroke-width="1.5"/></svg>`,
    'Google Analytics': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#E37400" opacity="0.15"/><path d="M8 16v-4M12 16V8M16 16v-6" stroke="#E37400" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    'Google Tag Manager': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#2488FF" opacity="0.15"/><path d="M7 9l5-4 5 4-5 10-5-10z" stroke="#2488FF" stroke-width="1.5"/></svg>`,
    'Looker Studio': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#4285F4" opacity="0.15"/><path d="M6 12h12M12 6v12" stroke="#4285F4" stroke-width="1.5"/></svg>`,
    'Supermetrics': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#E53E3E" opacity="0.15"/><path d="M7 16V8h10v8H7z" stroke="#E53E3E" stroke-width="1.5"/></svg>`,
    'Zapier': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF4F00" opacity="0.15"/><path d="M8 12l4-4 4 4-4 4-4-4z" fill="#FF4F00"/></svg>`,
    'CallRail': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF8800" opacity="0.15"/><path d="M8 8a4 4 0 018 0" stroke="#FF8800" stroke-width="1.5"/></svg>`,
    'Hotjar': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF2C6D" opacity="0.15"/><path d="M12 7c-2 0-3 3-3 5s1 5 3 5 3-3 3-5-1-5-3-5z" stroke="#FF2C6D" stroke-width="1.5"/></svg>`,
    'Unbounce': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#007FFF" opacity="0.15"/><circle cx="12" cy="12" r="4" stroke="#007FFF" stroke-width="1.5"/></svg>`,
    'Screaming Frog': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#448B2C" opacity="0.15"/><path d="M9 12h6M12 9l3 3-3 3" stroke="#448B2C" stroke-width="1.5"/></svg>`,
    'Adalysis': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#0D7BFF" opacity="0.15"/><path d="M7 16V8l10 8V8" stroke="#0D7BFF" stroke-width="1.5"/></svg>`,
    'Moat': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#2B3D52" opacity="0.15"/><path d="M7 12a5 5 0 0110 0" stroke="#2B3D52" stroke-width="1.5"/></svg>`,
    'Revealbot': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#5C33F6" opacity="0.15"/><circle cx="12" cy="12" r="5" stroke="#5C33F6" stroke-width="1.5"/></svg>`,
    'PPC Ninja': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF2E93" opacity="0.15"/><path d="M7 9l5-4 5 4v6l-5 4-5-4V9z" stroke="#FF2E93" stroke-width="1.5"/></svg>`,
    'Automate': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#262D3D" opacity="0.15"/><path d="M9 12h6M12 9l3 3-3 3" stroke="#262D3D" stroke-width="1.5"/></svg>`,
    'Rules.io': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00A699" opacity="0.15"/><path d="M7 12h10" stroke="#00A699" stroke-width="1.5"/></svg>`,
    'Google Merchant Center': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF9900" opacity="0.15"/><path d="M6 14s3-4 6-4 6 4 6 4" stroke="#FF9900" stroke-width="1.5"/></svg>`
  }
  return logos[name] || logos['Google Ads']
}

// ── Interactive Counter States ───────────────────────────────
const animatedStats = ref({
  clicks: 0,
  conversions: 0,
  cost: 0,
  roas: 0,
  // Section 5 Stats
  campaigns: 0,
  spend: 0,
  roasIncrease: 0,
  cplReduction: 0
})

const statsObserved = ref(false)
const statsInView = ref(false)

const animateValue = (obj, key, start, end, duration, decimals = 0) => {
  let startTimestamp = null
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp
    const progress = Math.min((timestamp - startTimestamp) / duration, 1)
    const currentVal = progress * (end - start) + start
    obj[key] = parseFloat(currentVal.toFixed(decimals))
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

  // Hero KPI animations start immediately
  animateValue(animatedStats.value, 'clicks', 0, 24.5, 2000, 1)
  animateValue(animatedStats.value, 'conversions', 0, 1.85, 2000, 2)
  animateValue(animatedStats.value, 'cost', 25.00, 12.45, 2000, 2)
  animateValue(animatedStats.value, 'roas', 0, 4.7, 2000, 1)

  // Why Choose Us Stats Observer
  const statsEl = document.querySelector('.ppc-why-us__stats-container')
  if (statsEl) {
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          statsInView.value = true
          if (!statsObserved.value) {
            statsObserved.value = true
            animateValue(animatedStats.value, 'campaigns', 0, 250, 2000)
            animateValue(animatedStats.value, 'spend', 0, 10, 2000)
            animateValue(animatedStats.value, 'roasIncrease', 0, 3.2, 2000, 1)
            animateValue(animatedStats.value, 'cplReduction', 0, 45, 2000)
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
    <title>{{ service.seo?.meta_title || 'PPC Management & Pay-Per-Click Advertising' }} — RankQuill</title>
    <meta name="description" :content="service.seo?.meta_description || 'Maximize your advertising ROI with our data-driven pay-per-click management services. Certified search, shopping, display, and social partners.'" />
    <meta name="robots" content="index, follow" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  </Head>

  <PublicLayout>
    <div class="ppc-page">

      <!-- ═══════════════════════════════════════════════════════
           SECTION 1 – HERO
      ═══════════════════════════════════════════════════════ -->
      <section class="ppc-hero">
        <!-- Background Effects -->
        <div class="ppc-hero__bg-dots"></div>
        <div class="ppc-hero__glow-1"></div>
        <div class="ppc-hero__glow-2"></div>

        <div class="ppc-container">
          <div class="ppc-hero__wrapper">
            
            <!-- Left Content Column (45%) -->
            <div class="ppc-hero__content">
              <!-- Breadcrumb -->
              <div class="ppc-breadcrumb">
                <Link href="/" class="ppc-breadcrumb__link">Home</Link>
                <span class="ppc-breadcrumb__sep">&gt;</span>
                <Link href="/services" class="ppc-breadcrumb__link">Services</Link>
                <span class="ppc-breadcrumb__sep">&gt;</span>
                <span class="ppc-breadcrumb__active">Pay-Per-Click</span>
              </div>

              <!-- Service Badge -->
              <div class="ppc-hero__badge-wrap">
                <span class="ppc-badge">Pay-Per-Click Advertising</span>
              </div>

              <!-- Headline -->
              <h1 class="ppc-hero__title">
                Target Right.<br>
                Pay Smart.<br>
                <span class="ppc-hero__title--highlight">Grow Faster.</span>
              </h1>

              <!-- Description -->
              <p class="ppc-hero__desc">
                Data-driven PPC strategies that deliver high-quality traffic, more leads, and maximum ROI across all advertising platforms.
              </p>

              <!-- Benefits Row -->
              <div class="ppc-benefits">
                <div class="ppc-benefit-item">
                  <span class="ppc-benefit-item__icon-wrap">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                  </span>
                  <span class="ppc-benefit-item__label">High ROI</span>
                </div>
                <div class="ppc-benefit-item">
                  <span class="ppc-benefit-item__icon-wrap">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </span>
                  <span class="ppc-benefit-item__label">Targeted Traffic</span>
                </div>
                <div class="ppc-benefit-item">
                  <span class="ppc-benefit-item__icon-wrap">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </span>
                  <span class="ppc-benefit-item__label">Measurable Results</span>
                </div>
                <div class="ppc-benefit-item">
                  <span class="ppc-benefit-item__icon-wrap">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 11v-1m-3-1h6"/></svg>
                  </span>
                  <span class="ppc-benefit-item__label">Cost Effective</span>
                </div>
              </div>

              <!-- CTA Buttons -->
              <div class="ppc-hero__btns">
                <Link href="/contact-us" class="ppc-btn ppc-btn--primary">
                  Get a Free PPC Audit
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </Link>
                <a href="#packages" class="ppc-btn ppc-btn--outline">
                  View Our PPC Packages
                  <span class="ppc-btn__circle">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                  </span>
                </a>
              </div>
            </div>

            <!-- Right Dashboard Column (55%) -->
            <div class="ppc-hero__visual">
              <div class="ppc-dashboard-stack">
                
                <!-- KPI CARDS (Four Metric Cards) -->
                <div class="ppc-kpis-grid">
                  <div class="ppc-kpi-card">
                    <div class="ppc-kpi-card__top">
                      <span class="ppc-kpi-card__icon bg-blue">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
                      </span>
                      <span class="ppc-kpi-card__title">Clicks</span>
                    </div>
                    <div class="ppc-kpi-card__val font-dark">{{ animatedStats.clicks }}K</div>
                    <span class="ppc-kpi-card__trend font-green">▲ 20%</span>
                  </div>

                  <div class="ppc-kpi-card">
                    <div class="ppc-kpi-card__top">
                      <span class="ppc-kpi-card__icon bg-green">
                        <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                      </span>
                      <span class="ppc-kpi-card__title">Conversions</span>
                    </div>
                    <div class="ppc-kpi-card__val font-dark">{{ animatedStats.conversions }}K</div>
                    <span class="ppc-kpi-card__trend font-green">▲ 35%</span>
                  </div>

                  <div class="ppc-kpi-card">
                    <div class="ppc-kpi-card__top">
                      <span class="ppc-kpi-card__icon bg-indigo">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/></svg>
                      </span>
                      <span class="ppc-kpi-card__title">Cost / Conv.</span>
                    </div>
                    <div class="ppc-kpi-card__val font-dark">${{ animatedStats.cost }}</div>
                    <span class="ppc-kpi-card__trend font-green">▼ 18%</span>
                  </div>

                  <div class="ppc-kpi-card">
                    <div class="ppc-kpi-card__top">
                      <span class="ppc-kpi-card__icon bg-orange">
                        <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                      </span>
                      <span class="ppc-kpi-card__title">ROAS</span>
                    </div>
                    <div class="ppc-kpi-card__val font-dark">{{ animatedStats.roas }}x</div>
                    <span class="ppc-kpi-card__trend font-green">▲ 42%</span>
                  </div>
                </div>

                <!-- MAIN PERFORMANCE CHART & TOP CAMPAIGNS DONUT -->
                <div class="ppc-dashboard-charts">
                  
                  <!-- Main Chart Card -->
                  <div class="ppc-db-card ppc-db-card--main-chart">
                    <div class="ppc-db-card__title">Performance Overview</div>
                    
                    <div class="ppc-chart-container">
                      <svg viewBox="0 0 450 160" class="ppc-svg-chart">
                        <!-- Clicks Line (Blue) -->
                        <path d="M10 120 Q 80 110, 150 70 T 290 85 T 430 40" fill="none" stroke="#6366f1" stroke-width="3" class="line-drawing-anim"/>
                        <!-- Conversions Line (Green) -->
                        <path d="M10 140 Q 80 120, 150 95 T 290 110 T 430 70" fill="none" stroke="#10b981" stroke-width="3" class="line-drawing-anim"/>
                        <!-- Cost Line (Orange) -->
                        <path d="M10 100 Q 80 95, 150 60 T 290 70 T 430 30" fill="none" stroke="#f59e0b" stroke-width="3" class="line-drawing-anim"/>
                        <!-- ROAS Line (Yellow) -->
                        <path d="M10 80 Q 80 75, 150 50 T 290 40 T 430 15" fill="none" stroke="#eab308" stroke-width="3" class="line-drawing-anim"/>
                      </svg>
                      
                      <!-- Dates row -->
                      <div class="ppc-chart-dates">
                        <span>May 1</span>
                        <span>May 8</span>
                        <span>May 15</span>
                        <span>May 22</span>
                        <span>May 29</span>
                      </div>

                      <!-- Legend row -->
                      <div class="ppc-chart-legend">
                        <span class="ppc-legend-tag"><span class="ppc-legend-dot bg-blue"></span>Clicks</span>
                        <span class="ppc-legend-tag"><span class="ppc-legend-dot bg-green"></span>Conversions</span>
                        <span class="ppc-legend-tag"><span class="ppc-legend-dot bg-orange"></span>Cost</span>
                        <span class="ppc-legend-tag"><span class="ppc-legend-dot bg-yellow"></span>ROAS</span>
                      </div>
                    </div>
                  </div>

                  <!-- Donut Chart Card -->
                  <div class="ppc-db-card ppc-db-card--donut">
                    <div class="ppc-db-card__title">Top Campaigns</div>
                    
                    <div class="ppc-donut-layout">
                      <div class="ppc-donut-wrapper">
                        <svg class="ppc-donut-svg" viewBox="0 0 100 100">
                          <!-- Search Ads (40%) -->
                          <circle cx="50" cy="50" r="38" stroke="#6366f1" stroke-width="12" fill="transparent"
                                  stroke-dasharray="238.6" stroke-dashoffset="0" class="donut-segment-1"/>
                          <!-- Display Ads (25%) -->
                          <circle cx="50" cy="50" r="38" stroke="#60a5fa" stroke-width="12" fill="transparent"
                                  stroke-dasharray="238.6" stroke-dashoffset="95.4" class="donut-segment-2"/>
                          <!-- Shopping Ads (23%) -->
                          <circle cx="50" cy="50" r="38" stroke="#10b981" stroke-width="12" fill="transparent"
                                  stroke-dasharray="238.6" stroke-dashoffset="155.1" class="donut-segment-3"/>
                          <!-- Video Ads (12%) -->
                          <circle cx="50" cy="50" r="38" stroke="#f59e0b" stroke-width="12" fill="transparent"
                                  stroke-dasharray="238.6" stroke-dashoffset="210.0" class="donut-segment-4"/>
                        </svg>
                        <div class="ppc-donut-center-lbl">May 29</div>
                      </div>
                      <div class="ppc-donut-legend">
                        <div class="ppc-donut-legend-item">
                          <span class="ppc-legend-dot bg-blue"></span>
                          <span class="ppc-donut-lbl-txt">Search Ads</span>
                          <span class="ppc-donut-val">40%</span>
                        </div>
                        <div class="ppc-donut-legend-item">
                          <span class="ppc-legend-dot bg-lightblue"></span>
                          <span class="ppc-donut-lbl-txt">Display Ads</span>
                          <span class="ppc-donut-val">25%</span>
                        </div>
                        <div class="ppc-donut-legend-item">
                          <span class="ppc-legend-dot bg-green"></span>
                          <span class="ppc-donut-lbl-txt">Shopping Ads</span>
                          <span class="ppc-donut-val">23%</span>
                        </div>
                        <div class="ppc-donut-legend-item">
                          <span class="ppc-legend-dot bg-orange"></span>
                          <span class="ppc-donut-lbl-txt">Video Ads</span>
                          <span class="ppc-donut-val">12%</span>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- PLATFORM STRIP -->
                <div class="ppc-platform-strip">
                  <div class="ppc-platform-strip__title">We Manage Campaigns Across</div>
                  <div class="ppc-platform-strip__grid">
                    <span class="ppc-plat-badge"><span class="ppc-plat-logo text-blue">G</span>Google Ads</span>
                    <span class="ppc-plat-badge"><span class="ppc-plat-logo text-cyan">M</span>Microsoft Advertising</span>
                    <span class="ppc-plat-badge"><span class="ppc-plat-logo text-blue">∞</span>Meta Ads</span>
                    <span class="ppc-plat-badge"><span class="ppc-plat-logo text-red">▶</span>YouTube Ads</span>
                    <span class="ppc-plat-badge"><span class="ppc-plat-logo text-indigo">in</span>LinkedIn Ads</span>
                    <span class="ppc-plat-badge"><span class="ppc-plat-logo text-pink">d</span>TikTok Ads</span>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 2 – OUR PPC PROCESS
      ═══════════════════════════════════════════════════════ -->
      <section class="ppc-section bg-slate-50 border-t border-b border-slate-100">
        <div class="ppc-container">
          <div class="ppc-section-header" data-rev>
            <h2 class="ppc-section-title">Our PPC Process</h2>
            <p class="ppc-section-sub">A strategic approach to plan, execute, optimize, and scale high-performing campaigns.</p>
          </div>

          <div class="ppc-process-grid">
            
            <!-- Step 1 -->
            <div class="ppc-process-item" data-rev data-delay="0.1">
              <div class="ppc-process-card">
                <div class="ppc-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <span class="ppc-process-card__number">01</span>
                <h3 class="ppc-process-card__title">Research & Analysis</h3>
                <p class="ppc-process-card__desc">We analyze your business, audience, competitors, and market opportunities.</p>
              </div>
              <div class="ppc-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Step 2 -->
            <div class="ppc-process-item" data-rev data-delay="0.2">
              <div class="ppc-process-card">
                <div class="ppc-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <span class="ppc-process-card__number">02</span>
                <h3 class="ppc-process-card__title">Strategy & Planning</h3>
                <p class="ppc-process-card__desc">We create a customized PPC strategy aligned with your goals and budget.</p>
              </div>
              <div class="ppc-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Step 3 -->
            <div class="ppc-process-item" data-rev data-delay="0.3">
              <div class="ppc-process-card">
                <div class="ppc-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                </div>
                <span class="ppc-process-card__number">03</span>
                <h3 class="ppc-process-card__title">Campaign Setup</h3>
                <p class="ppc-process-card__desc">We set up conversion tracking, audiences, ad groups, and compelling ads.</p>
              </div>
              <div class="ppc-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Step 4 -->
            <div class="ppc-process-item" data-rev data-delay="0.4">
              <div class="ppc-process-card">
                <div class="ppc-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <span class="ppc-process-card__number">04</span>
                <h3 class="ppc-process-card__title">Launch & Monitor</h3>
                <p class="ppc-process-card__desc">We launch campaigns and monitor performance in real time.</p>
              </div>
              <div class="ppc-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Step 5 -->
            <div class="ppc-process-item" data-rev data-delay="0.5">
              <div class="ppc-process-card">
                <div class="ppc-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
                </div>
                <span class="ppc-process-card__number">05</span>
                <h3 class="ppc-process-card__title">Optimize</h3>
                <p class="ppc-process-card__desc">We optimize bids, budgets, targeting, and creatives for better results.</p>
              </div>
              <div class="ppc-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </div>
            </div>

            <!-- Step 6 -->
            <div class="ppc-process-item" data-rev data-delay="0.6">
              <div class="ppc-process-card">
                <div class="ppc-process-card__icon-wrap">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="ppc-process-card__number">06</span>
                <h3 class="ppc-process-card__title">Scale & Report</h3>
                <p class="ppc-process-card__desc">We scale successful campaigns and provide transparent reporting.</p>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 3 – PPC SERVICES WE OFFER
      ═══════════════════════════════════════════════════════ -->
      <section class="ppc-section bg-white">
        <div class="ppc-container">
          <div class="ppc-section-header" data-rev>
            <h2 class="ppc-section-title">PPC Services We Offer</h2>
            <p class="ppc-section-sub">Full-funnel PPC solutions to drive targeted traffic, leads, and sales.</p>
          </div>

          <div class="ppc-services-grid">
            
            <!-- Service 1 -->
            <div class="ppc-service-card" data-rev data-delay="0.05">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-blue font-bold">G</span>
              </div>
              <h3 class="ppc-service-card__title">Google Ads (Search)</h3>
              <p class="ppc-service-card__desc">Drive high-intent traffic, leads, and sales with search campaigns.</p>
            </div>

            <!-- Service 2 -->
            <div class="ppc-service-card" data-rev data-delay="0.1">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-orange font-bold">🛒</span>
              </div>
              <h3 class="ppc-service-card__title">Google Shopping Ads</h3>
              <p class="ppc-service-card__desc">Showcase your products to drive direct e-commerce sales and revenue.</p>
            </div>

            <!-- Service 3 -->
            <div class="ppc-service-card" data-rev data-delay="0.15">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-blue font-bold">🖼️</span>
              </div>
              <h3 class="ppc-service-card__title">Google Display Ads</h3>
              <p class="ppc-service-card__desc">Increase brand awareness and reach new audiences across Google's network.</p>
            </div>

            <!-- Service 4 -->
            <div class="ppc-service-card" data-rev data-delay="0.2">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-red font-bold">▶</span>
              </div>
              <h3 class="ppc-service-card__title">YouTube Ads</h3>
              <p class="ppc-service-card__desc">Engage your audience with immersive video advertising and stories.</p>
            </div>

            <!-- Service 5 -->
            <div class="ppc-service-card" data-rev data-delay="0.25">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-cyan font-bold">M</span>
              </div>
              <h3 class="ppc-service-card__title">Microsoft Ads</h3>
              <p class="ppc-service-card__desc">Reach high-quality audiences on Bing with less ad competition.</p>
            </div>

            <!-- Service 6 -->
            <div class="ppc-service-card" data-rev data-delay="0.3">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-indigo font-bold">f</span>
              </div>
              <h3 class="ppc-service-card__title">Facebook & Instagram Ads</h3>
              <p class="ppc-service-card__desc">Drive conversions, leads, and sales across Meta platforms.</p>
            </div>

            <!-- Service 7 -->
            <div class="ppc-service-card" data-rev data-delay="0.35">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-blue font-bold">in</span>
              </div>
              <h3 class="ppc-service-card__title">LinkedIn Ads</h3>
              <p class="ppc-service-card__desc">Generate B2B leads and connect directly with key decision-makers.</p>
            </div>

            <!-- Service 8 -->
            <div class="ppc-service-card" data-rev data-delay="0.4">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-dark font-bold">d</span>
              </div>
              <h3 class="ppc-service-card__title">TikTok Ads</h3>
              <p class="ppc-service-card__desc">Reach and engage the next generation of buyers with visual formats.</p>
            </div>

            <!-- Service 9 -->
            <div class="ppc-service-card" data-rev data-delay="0.45">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-purple font-bold">🔄</span>
              </div>
              <h3 class="ppc-service-card__title">Remarketing Ads</h3>
              <p class="ppc-service-card__desc">Re-engage previous website visitors and increase total conversions.</p>
            </div>

            <!-- Service 10 -->
            <div class="ppc-service-card" data-rev data-delay="0.5">
              <div class="ppc-service-card__icon-wrap">
                <span class="ppc-service-card__icon-lbl text-green font-bold">📞</span>
              </div>
              <h3 class="ppc-service-card__title">Local & Call Ads</h3>
              <p class="ppc-service-card__desc">Drive local maps visits, phone calls, and in-store conversions.</p>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 4 – PPC TOOLS & PLATFORMS
      ═══════════════════════════════════════════════════════ -->
      <section class="ppc-section bg-slate-50 border-t border-b border-slate-100">
        <div class="ppc-container">
          <div class="ppc-section-header" data-rev>
            <h2 class="ppc-section-title">PPC Tools & Platforms We Use</h2>
            <p class="ppc-section-sub">Industry-leading tools to research, manage, track, and optimize PPC campaigns.</p>
          </div>

          <!-- Tabs -->
          <div class="ppc-tabs" data-rev>
            <button v-for="tab in toolTabs" :key="tab"
                    @click="activeTab = tab"
                    :class="['ppc-tab', activeTab === tab ? 'ppc-tab--active' : '']">
              {{ tab }}
            </button>
          </div>

          <!-- Grid -->
          <div class="ppc-tools-grid">
            <div v-for="tool in filteredTools" :key="tool.name" class="ppc-tool-card" data-rev>
              <div class="ppc-tool-card__logo" v-html="getToolLogo(tool.name)"></div>
              <span class="ppc-tool-card__name">{{ tool.name }}</span>
            </div>
          </div>

        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 5 – WHY CHOOSE OUR PPC MANAGEMENT
      ═══════════════════════════════════════════════════════ -->
      <section class="ppc-section bg-white ppc-why-us__stats-container">
        <div class="ppc-container">
          <div class="ppc-why-us__grid">
            
            <!-- Left feature items -->
            <div class="ppc-why-us__content" data-rev>
              <h2 class="ppc-why-us__title">Why Choose Our PPC Management?</h2>
              
              <div class="ppc-why-us__features">
                <div class="ppc-why-us__feature-item">
                  <span class="ppc-why-us__feature-icon">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <div>
                    <h3 class="ppc-why-us__feature-title">Data-Driven Strategy</h3>
                    <p class="ppc-why-us__feature-desc">We use data and insights to maximize ROI and scale accounts safely.</p>
                  </div>
                </div>

                <div class="ppc-why-us__feature-item">
                  <span class="ppc-why-us__feature-icon">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <div>
                    <h3 class="ppc-why-us__feature-title">Continuous Optimization</h3>
                    <p class="ppc-why-us__feature-desc">We optimize campaigns daily for search terms, budgets, and bids.</p>
                  </div>
                </div>

                <div class="ppc-why-us__feature-item">
                  <span class="ppc-why-us__feature-icon">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <div>
                    <h3 class="ppc-why-us__feature-title">Transparent Reporting</h3>
                    <p class="ppc-why-us__feature-desc">Get clear, easy-to-understand reports mapping directly to pipeline value.</p>
                  </div>
                </div>

                <div class="ppc-why-us__feature-item">
                  <span class="ppc-why-us__feature-icon">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <div>
                    <h3 class="ppc-why-us__feature-title">Certified PPC Experts</h3>
                    <p class="ppc-why-us__feature-desc">Our team is fully certified across leading search and social platforms.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right stats card -->
            <div class="ppc-why-us__visual" data-rev data-delay="0.2">
              <div class="ppc-stats-card">
                <div class="ppc-stats-card__label">Proven Results</div>
                
                <div class="ppc-stats-card__grid">
                  <div class="ppc-stats-card__item">
                    <div class="ppc-stats-card__number">{{ animatedStats.campaigns }}+</div>
                    <div class="ppc-stats-card__caption">Campaigns Managed</div>
                  </div>
                  <div class="ppc-stats-card__item">
                    <div class="ppc-stats-card__number">${{ animatedStats.spend }}M+</div>
                    <div class="ppc-stats-card__caption">Ad Spend Managed</div>
                  </div>
                  <div class="ppc-stats-card__item">
                    <div class="ppc-stats-card__number">{{ animatedStats.roasIncrease }}X</div>
                    <div class="ppc-stats-card__caption">Avg. ROAS Increase</div>
                  </div>
                  <div class="ppc-stats-card__item">
                    <div class="ppc-stats-card__number">{{ animatedStats.cplReduction }}%</div>
                    <div class="ppc-stats-card__caption">Avg. Cost Per Lead Reduction</div>
                  </div>
                </div>

                <!-- Graph Area -->
                <div class="ppc-stats-card__graph-box">
                  <div class="ppc-stats-card__graph">
                    <div class="ppc-stats-card__bars">
                      <div v-for="(h, idx) in [20, 35, 45, 60, 50, 85, 100]" :key="idx"
                           class="ppc-stats-card__bar-item"
                           :class="{ 'in-view': statsInView }"
                           :style="{ height: h + '%', transitionDelay: (idx * 0.1) + 's' }"></div>
                    </div>
                    
                    <svg viewBox="0 0 300 120" class="ppc-stats-card__line-svg">
                      <!-- Curve drawing on viewport entry -->
                      <path d="M10 100 Q 60 85, 110 60 T 210 50 T 290 20" fill="none" stroke="#10b981" stroke-width="4" stroke-linecap="round"
                            class="stats-line" :class="{ 'in-view': statsInView }"/>
                      <circle cx="290" cy="20" r="5" fill="#10b981"/>
                    </svg>
                  </div>
                </div>

                <!-- Messages -->
                <div class="ppc-stats-card__message">
                  <h4 class="ppc-stats-card__message-title">Better Traffic. Better Leads. Better ROI.</h4>
                  <p class="ppc-stats-card__message-desc">We combine strategy, creativity, and technology to deliver measurable growth for your business.</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 6 – CERTIFIED PARTNERS
      ═══════════════════════════════════════════════════════ -->
      <section class="ppc-section bg-slate-50 border-t border-b border-slate-100">
        <div class="ppc-container">
          <div class="ppc-section-header" data-rev>
            <h2 class="ppc-section-title">We are Certified Partners</h2>
          </div>

          <div class="ppc-partners-grid">
            <div class="ppc-partner-card" data-rev data-delay="0.05">
              <span class="ppc-partner-logo font-bold text-blue">Google Partner</span>
            </div>
            <div class="ppc-partner-card" data-rev data-delay="0.1">
              <span class="ppc-partner-logo font-bold text-cyan">Microsoft Advertising Partner</span>
            </div>
            <div class="ppc-partner-card" data-rev data-delay="0.15">
              <span class="ppc-partner-logo font-bold text-blue">Meta Business Partner</span>
            </div>
            <div class="ppc-partner-card" data-rev data-delay="0.2">
              <span class="ppc-partner-logo font-bold text-pink">TikTok Marketing Partner</span>
            </div>
            <div class="ppc-partner-card" data-rev data-delay="0.25">
              <span class="ppc-partner-logo font-bold text-indigo">LinkedIn Marketing Partner</span>
            </div>
            <div class="ppc-partner-card" data-rev data-delay="0.3">
              <span class="ppc-partner-logo font-bold text-orange">Google Analytics Certified</span>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 7 – CTA BANNER
      ═══════════════════════════════════════════════════════ -->
      <section class="ppc-section bg-white pt-4 pb-16">
        <div class="ppc-container">
          <div class="ppc-cta-banner cta-banner-anim" data-rev>
            <!-- Background illustration shape overlay -->
            <div class="ppc-cta-banner__illustration">
              <svg viewBox="0 0 400 180" class="w-full h-full opacity-10">
                <path d="M50 150 L100 120 L150 130 L200 80 L250 90 L300 40 L350 50" stroke="#fff" stroke-width="6" fill="none" stroke-linecap="round"/>
                <circle cx="350" cy="50" r="8" fill="#fff"/>
              </svg>
            </div>

            <div class="ppc-cta-banner__content">
              <div class="ppc-cta-banner__left">
                <h2 class="ppc-cta-banner__title">Ready to Get More Leads & Sales with PPC?</h2>
                <p class="ppc-cta-banner__subtitle">Let's create high-performing ad campaigns that deliver real results.</p>
              </div>
              <div class="ppc-cta-banner__right">
                <Link href="/contact-us" class="ppc-cta-button">
                  Get a Free PPC Audit
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
.ppc-page {
  font-family: 'Inter', system-ui, sans-serif;
  color: #1e293b;
  overflow-x: hidden;
  background-color: #ffffff;
}

.ppc-container {
  width: 100%;
  max-width: 1320px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 20px;
  padding-right: 20px;
}

.ppc-section {
  padding-top: 56px;
  padding-bottom: 56px;
}
@media (min-width: 768px) {
  .ppc-section {
    padding-top: 88px;
    padding-bottom: 88px;
  }
}

.ppc-section-header {
  text-align: center;
  margin-bottom: 48px;
}
.ppc-section-title {
  font-size: 32px;
  font-weight: 700;
  color: #0f172a;
  line-height: 1.15;
  letter-spacing: -0.02em;
}
@media (min-width: 768px) {
  .ppc-section-title {
    font-size: 42px;
  }
}
.ppc-section-sub {
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
.font-blue { color: #6366f1; }
.font-purple { color: #7c3aed; }
.font-dark { color: #0f172a; }

/* ═══════════════════════════════════════════════════════════
   SECTION 1 – HERO
   ═══════════════════════════════════════════════════════════ */
.ppc-hero {
  background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 100%);
  position: relative;
  overflow: hidden;
  padding-top: 100px;
  padding-bottom: 56px;
}
@media (min-width: 1024px) {
  .ppc-hero {
    padding-top: 140px;
    padding-bottom: 96px;
  }
}

.ppc-hero__bg-dots {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(rgba(99, 102, 241, 0.04) 1.5px, transparent 1.5px);
  background-size: 24px 24px;
  pointer-events: none;
}
.ppc-hero__glow-1 {
  position: absolute;
  top: -150px;
  right: -100px;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
  pointer-events: none;
}
.ppc-hero__glow-2 {
  position: absolute;
  bottom: -100px;
  left: -150px;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
  pointer-events: none;
}

.ppc-hero__wrapper {
  display: grid;
  grid-template-columns: 1fr;
  gap: 48px;
  align-items: center;
}
@media (min-width: 1024px) {
  .ppc-hero__wrapper {
    grid-template-columns: 45% 55%;
  }
}

.ppc-hero__content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

/* Breadcrumbs */
.ppc-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #94a3b8;
  margin-bottom: 24px;
  flex-wrap: wrap;
}
.ppc-breadcrumb__link {
  color: #94a3b8;
  text-decoration: none;
  transition: color 0.2s;
}
.ppc-breadcrumb__link:hover {
  color: #6366f1;
}
.ppc-breadcrumb__sep {
  color: #cbd5e1;
}
.ppc-breadcrumb__active {
  color: #64748b;
  font-weight: 500;
}

/* Pill badge styling */
.ppc-hero__badge-wrap {
  margin-bottom: 24px;
}
.ppc-badge {
  display: inline-flex;
  align-items: center;
  background-color: rgba(99, 102, 241, 0.07);
  border: 1.5px solid rgba(99, 102, 241, 0.15);
  color: #6366f1;
  font-size: 14px;
  font-weight: 600;
  padding: 6px 16px;
  border-radius: 100px;
}

/* Headline Scale */
.ppc-hero__title {
  font-size: 38px;
  font-weight: 700;
  line-height: 1.1;
  color: #0f172a;
  letter-spacing: -0.03em;
  margin-bottom: 18px;
}
@media (min-width: 768px) {
  .ppc-hero__title {
    font-size: 54px;
  }
}
@media (min-width: 1280px) {
  .ppc-hero__title {
    font-size: 64px;
  }
}
.ppc-hero__title--highlight {
  color: #6366f1;
}

.ppc-hero__desc {
  font-size: 16px;
  line-height: 1.65;
  color: #64748b;
  max-width: 520px;
  margin-bottom: 32px;
}

/* Benefits Row */
.ppc-benefits {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  width: 100%;
  margin-bottom: 40px;
}
@media (max-width: 480px) {
  .ppc-benefits {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}
.ppc-benefit-item {
  display: flex;
  align-items: center;
  gap: 10px;
}
.ppc-benefit-item__icon-wrap {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.ppc-benefit-item__label {
  font-size: 15px;
  font-weight: 600;
  color: #334155;
}

/* CTA Buttons */
.ppc-hero__btns {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  width: 100%;
}
.ppc-btn {
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
.ppc-btn--primary {
  background-color: #6366f1;
  color: #ffffff;
  box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
}
.ppc-btn--primary:hover {
  transform: scale(1.03);
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.35);
}
.ppc-btn--outline {
  background-color: #ffffff;
  color: #6366f1;
  border: 1.5px solid rgba(99, 102, 241, 0.15);
}
.ppc-btn--outline:hover {
  background-color: rgba(99, 102, 241, 0.03);
  border-color: #6366f1;
  transform: scale(1.03);
  box-shadow: 0 10px 30px rgba(99, 102, 241, 0.1);
}
.ppc-btn__circle {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Hero Dashboard Layout */
.ppc-hero__visual {
  width: 100%;
}
.ppc-dashboard-stack {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 100%;
}

.ppc-db-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  animation: floatCard 6s ease-in-out infinite alternate;
}
.ppc-db-card:hover {
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.15);
}

@keyframes floatCard {
  0% { transform: translateY(0px); }
  100% { transform: translateY(-10px); }
}

/* KPI metric cards row */
.ppc-kpis-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}
@media (min-width: 640px) {
  .ppc-kpis-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.ppc-kpi-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 16px;
  padding: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  display: flex;
  flex-direction: column;
}
.ppc-kpi-card__top {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
}
.ppc-kpi-card__icon {
  width: 28px;
  height: 28px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.ppc-kpi-card__icon.bg-blue { background-color: rgba(99, 102, 241, 0.08); }
.ppc-kpi-card__icon.bg-green { background-color: rgba(16, 185, 129, 0.08); }
.ppc-kpi-card__icon.bg-indigo { background-color: rgba(99, 102, 241, 0.08); }
.ppc-kpi-card__icon.bg-orange { background-color: rgba(245, 158, 11, 0.08); }

.ppc-kpi-card__title {
  font-size: 12px;
  font-weight: 700;
  color: #64748b;
}
.ppc-kpi-card__val {
  font-size: 24px;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 6px;
}
.ppc-kpi-card__trend {
  font-size: 11px;
  font-weight: 700;
}

/* Charts Cluster (Line Chart + Donut side-by-side) */
.ppc-dashboard-charts {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
@media (min-width: 1024px) {
  .ppc-dashboard-charts {
    flex-direction: row;
  }
}

.ppc-db-card--main-chart {
  flex: 3;
}
.ppc-db-card--donut {
  flex: 2;
}

.ppc-db-card__title {
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 20px;
}

.ppc-chart-container {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.ppc-chart-dates {
  display: flex;
  justify-content: space-between;
  font-size: 11px;
  color: #94a3b8;
  font-weight: 600;
  padding: 0 10px;
}
.ppc-chart-legend {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  justify-content: center;
  border-top: 1.5px solid #f1f5f9;
  padding-top: 12px;
  margin-top: 4px;
}
.ppc-legend-tag {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  font-weight: 600;
  color: #64748b;
}
.ppc-legend-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}
.ppc-legend-dot.bg-blue { background-color: #6366f1; }
.ppc-legend-dot.bg-lightblue { background-color: #60a5fa; }
.ppc-legend-dot.bg-green { background-color: #10b981; }
.ppc-legend-dot.bg-yellow { background-color: #eab308; }
.ppc-legend-dot.bg-orange { background-color: #f59e0b; }

.line-drawing-anim {
  stroke-dasharray: 400;
  stroke-dashoffset: 400;
  animation: drawLine 2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

/* Donut widget layout */
.ppc-donut-layout {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}
@media (min-width: 640px) {
  .ppc-donut-layout {
    flex-direction: row;
    justify-content: space-around;
  }
}
@media (min-width: 1024px) {
  .ppc-donut-layout {
    flex-direction: column;
    align-items: center;
  }
}
.ppc-donut-wrapper {
  position: relative;
  width: 90px;
  height: 90px;
}
.ppc-donut-svg {
  transform: rotate(-90deg);
  width: 100%;
  height: 100%;
}
.ppc-donut-center-lbl {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 700;
  color: #64748b;
}
.ppc-donut-legend {
  display: flex;
  flex-direction: column;
  gap: 6px;
  width: 100%;
}
.ppc-donut-legend-item {
  display: flex;
  align-items: center;
  width: 100%;
  font-size: 11.5px;
  font-weight: 600;
  color: #64748b;
}
.ppc-donut-lbl-txt {
  margin-left: 8px;
  flex-grow: 1;
}
.ppc-donut-val {
  font-weight: 700;
  color: #0f172a;
}

/* Platform strip below charts */
.ppc-platform-strip {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 16px;
  padding: 18px 24px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.ppc-platform-strip__title {
  font-size: 12px;
  font-weight: 700;
  color: #94a3b8;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}
.ppc-platform-strip__grid {
  display: flex;
  flex-wrap: wrap;
  gap: 12px 18px;
}
.ppc-plat-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 13.5px;
  font-weight: 700;
  color: #334155;
}
.ppc-plat-logo {
  font-size: 15px;
}

/* ═══════════════════════════════════════════════════════
   SECTION 2 – OUR PPC PROCESS
   ═══════════════════════════════════════════════════════ */
.ppc-process-grid {
  display: flex;
  flex-direction: column;
  gap: 24px;
}
@media (min-width: 1024px) {
  .ppc-process-grid {
    flex-direction: row;
    gap: 0;
  }
}

.ppc-process-item {
  flex: 1;
  display: flex;
  align-items: center;
  position: relative;
}

.ppc-process-card {
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
.ppc-process-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.15);
  border-color: rgba(99, 102, 241, 0.2);
}

.ppc-process-card__icon-wrap {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background-color: rgba(99, 102, 241, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
  transition: transform 0.3s ease;
}
.ppc-process-card:hover .ppc-process-card__icon-wrap {
  transform: scale(1.1);
}

.ppc-process-card__number {
  position: absolute;
  top: 16px;
  right: 20px;
  font-size: 12px;
  font-weight: 800;
  color: rgba(99, 102, 241, 0.25);
  letter-spacing: 1px;
}

.ppc-process-card__title {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}
.ppc-process-card__desc {
  font-size: 13px;
  color: #64748b;
  line-height: 1.6;
}

.ppc-process-arrow {
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
  .ppc-process-arrow {
    display: flex;
  }
}

/* ═══════════════════════════════════════════════════════
   SECTION 3 – PPC SERVICES WE OFFER
   ═══════════════════════════════════════════════════════ */
.ppc-services-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
}
@media (min-width: 640px) {
  .ppc-services-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media (min-width: 1024px) {
  .ppc-services-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 1280px) {
  .ppc-services-grid {
    grid-template-columns: repeat(5, 1fr);
  }
}

.ppc-service-card {
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
.ppc-service-card:hover {
  transform: translateY(-8px);
  border-color: rgba(99, 102, 241, 0.25);
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.15);
}

.ppc-service-card__icon-wrap {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background-color: rgba(99, 102, 241, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 18px;
  flex-shrink: 0;
}
.ppc-service-card__icon-lbl {
  font-size: 18px;
}

.ppc-service-card__title {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
  line-height: 1.3;
}
.ppc-service-card__desc {
  font-size: 13px;
  color: #64748b;
  line-height: 1.6;
}

/* ═══════════════════════════════════════════════════════
   SECTION 4 – PPC TOOLS & PLATFORMS
   ═══════════════════════════════════════════════════════ */
.ppc-tabs {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  margin-bottom: 40px;
}
.ppc-tab {
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
.ppc-tab:hover {
  border-color: #6366f1;
  color: #6366f1;
}
.ppc-tab--active {
  background-color: #6366f1;
  color: #ffffff;
  border-color: #6366f1;
  box-shadow: 0 8px 20px rgba(99, 102, 241, 0.2);
}

.ppc-tools-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}
@media (min-width: 640px) {
  .ppc-tools-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 768px) {
  .ppc-tools-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
@media (min-width: 1024px) {
  .ppc-tools-grid {
    grid-template-columns: repeat(6, 1fr);
  }
}

.ppc-tool-card {
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
.ppc-tool-card:hover {
  transform: scale(1.03);
  box-shadow: 0 12px 30px rgba(99, 102, 241, 0.12);
  border-color: rgba(99, 102, 241, 0.25);
}
.ppc-tool-card__logo {
  display: flex;
  align-items: center;
  justify-content: center;
}
.ppc-tool-card__name {
  font-size: 13.5px;
  font-weight: 600;
  color: #334155;
  line-height: 1.2;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 5 – WHY CHOOSE OUR PPC MANAGEMENT
   ═══════════════════════════════════════════════════════ */
.ppc-why-us__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 56px;
  align-items: center;
}
@media (min-width: 1024px) {
  .ppc-why-us__grid {
    grid-template-columns: 45% 55%;
  }
}

.ppc-why-us__content {
  display: flex;
  flex-direction: column;
}
.ppc-why-us__title {
  font-size: 32px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 32px;
}
@media (min-width: 768px) {
  .ppc-why-us__title {
    font-size: 42px;
  }
}

.ppc-why-us__features {
  display: flex;
  flex-direction: column;
  gap: 28px;
}
.ppc-why-us__feature-item {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}
.ppc-why-us__feature-icon {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 2px;
}
.ppc-why-us__feature-title {
  font-size: 18px;
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 6px;
}
.ppc-why-us__feature-desc {
  font-size: 15px;
  color: #64748b;
  line-height: 1.6;
}

.ppc-why-us__visual {
  width: 100%;
}

.ppc-stats-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 24px;
  padding: 32px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
}

.ppc-stats-card__label {
  font-size: 14px;
  font-weight: 700;
  color: #6366f1;
  margin-bottom: 24px;
}

.ppc-stats-card__grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
  margin-bottom: 32px;
}
@media (min-width: 640px) {
  .ppc-stats-card__grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
.ppc-stats-card__item {
  display: flex;
  flex-direction: column;
}
.ppc-stats-card__number {
  font-size: 28px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.1;
}
@media (min-width: 1280px) {
  .ppc-stats-card__number {
    font-size: 32px;
  }
}
.ppc-stats-card__caption {
  font-size: 12px;
  color: #64748b;
  font-weight: 600;
  margin-top: 4px;
}

.ppc-stats-card__graph-box {
  background-color: #f8fafc;
  border-radius: 16px;
  padding: 24px;
  border: 1.5px solid #f1f5f9;
  margin-bottom: 24px;
}
.ppc-stats-card__graph {
  position: relative;
  height: 120px;
  width: 100%;
}

.ppc-stats-card__bars {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: flex-end;
  gap: 12px;
  z-index: 1;
}
.ppc-stats-card__bar-item {
  flex: 1;
  background-color: rgba(99, 102, 241, 0.08);
  border-radius: 4px 4px 0 0;
  transform-origin: bottom;
  transform: scaleY(0);
  transition: transform 1.5s cubic-bezier(0.16, 1, 0.3, 1);
}
.ppc-stats-card__bar-item.in-view {
  transform: scaleY(1);
}

.ppc-stats-card__line-svg {
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

.ppc-stats-card__message {
  display: flex;
  flex-direction: column;
}
.ppc-stats-card__message-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}
.ppc-stats-card__message-desc {
  font-size: 14px;
  color: #64748b;
  line-height: 1.6;
}

/* ═══════════════════════════════════════════════════════
   SECTION 6 – CERTIFIED PARTNERS
   ═══════════════════════════════════════════════════════ */
.ppc-partners-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}
@media (min-width: 768px) {
  .ppc-partners-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 1024px) {
  .ppc-partners-grid {
    grid-template-columns: repeat(6, 1fr);
  }
}

.ppc-partner-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 16px;
  padding: 24px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}
.ppc-partner-card:hover {
  transform: scale(1.03);
  box-shadow: 0 12px 30px rgba(99, 102, 241, 0.12);
  border-color: rgba(99, 102, 241, 0.25);
}
.ppc-partner-logo {
  font-size: 13.5px;
  color: #334155;
  line-height: 1.2;
}

/* ═══════════════════════════════════════════════════════
   SECTION 7 – CTA BANNER
   ═══════════════════════════════════════════════════════ */
.ppc-cta-banner {
  position: relative;
  border-radius: 24px;
  padding: 40px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(99, 102, 241, 0.15);
}
@media (min-width: 768px) {
  .ppc-cta-banner {
    padding: 48px 64px;
  }
}

.ppc-cta-banner__illustration {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 300px;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

.cta-banner-anim {
  background: linear-gradient(135deg, #6366f1, #818cf8, #4f46e5, #6366f1);
  background-size: 300% 300%;
  animation: gradientShift 12s ease infinite;
}

.ppc-cta-banner__content {
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  gap: 28px;
  align-items: flex-start;
}
@media (min-width: 1024px) {
  .ppc-cta-banner__content {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

.ppc-cta-banner__left {
  max-width: 600px;
}
.ppc-cta-banner__title {
  font-size: 28px;
  font-weight: 700;
  color: #ffffff;
  line-height: 1.2;
  margin-bottom: 8px;
}
@media (min-width: 768px) {
  .ppc-cta-banner__title {
    font-size: 34px;
  }
}
.ppc-cta-banner__subtitle {
  font-size: 15px;
  color: rgba(255, 255, 255, 0.85);
  line-height: 1.6;
}

.ppc-cta-banner__right {
  flex-shrink: 0;
}
.ppc-cta-button {
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
  color: #6366f1;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  text-decoration: none;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}
.ppc-cta-button:hover {
  transform: scale(1.05);
  box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
</style>
