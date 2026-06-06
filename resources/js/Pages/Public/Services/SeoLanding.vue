<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  service: { type: Object, required: true }
})

// ── FAQ Accordion ──────────────────────────────────────────────
const openFaq = ref(null)
const toggleFaq = (i) => { openFaq.value = openFaq.value === i ? null : i }

const faqs = [
  { q: 'How long before I see SEO results?', a: 'Most clients see measurable ranking improvements within 60–90 days. Significant organic traffic gains typically materialise in 4–6 months, with compounding results over 12+ months as domain authority builds.' },
  { q: 'What makes RankQuill SEO different from other agencies?', a: 'We combine AI-powered keyword intelligence, technical precision, and content authority signals. Every campaign is backed by proprietary data models and transparent monthly reporting that maps directly to revenue, not just rankings.' },
  { q: 'Do you work with e-commerce and SaaS companies?', a: 'Yes. We specialise in high-complexity verticals including enterprise e-commerce, B2B SaaS, fintech, and healthcare — each requiring distinct schema strategies, entity building, and intent-mapped content architectures.' },
  { q: 'What is included in the monthly retainer?', a: 'Technical audits, on-page optimisation, link acquisition, content strategy, Core Web Vitals monitoring, schema implementation, competitor tracking, and a live analytics dashboard updated weekly.' },
  { q: 'Can SEO work alongside paid advertising?', a: 'Absolutely. Our integrated approach treats paid and organic as complementary channels — paid data informs keyword prioritisation while organic content reduces your long-term CAC dramatically.' },
  { q: 'Do you offer one-time audits?', a: 'Yes. Our Comprehensive SEO Audit is a standalone deliverable that analyses 200+ technical and content signals and produces a prioritised roadmap you can implement independently or with our team.' },
]

// ── Testimonials Rotator ───────────────────────────────────────
const testimonials = [
  { name: 'Sarah Chen', role: 'VP Marketing', company: 'TechFlow SaaS', rating: 5, avatar: 'SC', text: 'RankQuill took us from page 4 to position 1 for our primary keyword in under 6 months. Organic leads now make up 67% of our pipeline — our CAC dropped by 44%.' },
  { name: 'Marcus Webb', role: 'CEO', company: 'Nexus Commerce', rating: 5, avatar: 'MW', text: 'The technical SEO work they did on our 80,000-SKU store was extraordinary. Crawl budget optimised, Core Web Vitals all green, and revenue from organic up 312% YoY.' },
  { name: 'Priya Nair', role: 'Head of Growth', company: 'FinEdge', rating: 5, avatar: 'PN', text: 'The content cluster strategy was a game-changer. We own the entire funnel for our category keywords now. 5x increase in qualified trial signups from organic in year one.' },
]
const activeTestimonial = ref(0)
let testimonialTimer = null
const startTestimonialRotator = () => {
  testimonialTimer = setInterval(() => {
    activeTestimonial.value = (activeTestimonial.value + 1) % testimonials.length
  }, 5000)
}
const stopTestimonialRotator = () => clearInterval(testimonialTimer)

// ── Scroll Animations (Intersection Observer) ─────────────────
const observers = []
const revealEls = ref([])

const setupReveal = () => {
  document.querySelectorAll('[data-reveal]').forEach(el => {
    const anim = el.dataset.reveal
    const delay = el.dataset.delay ? parseFloat(el.dataset.delay) : 0
    el.style.opacity = '0'
    el.style.transition = `opacity 0.7s ease ${delay}s, transform 0.7s ease ${delay}s`
    if (anim === 'up')    el.style.transform = 'translateY(40px)'
    if (anim === 'left')  el.style.transform = 'translateX(-50px)'
    if (anim === 'right') el.style.transform = 'translateX(50px)'
    if (anim === 'scale') el.style.transform = 'scale(0.88)'

    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.opacity = '1'
          e.target.style.transform = 'none'
          obs.unobserve(e.target)
        }
      })
    }, { threshold: 0.15 })
    obs.observe(el)
    observers.push(obs)
  })
}

// ── Parallax ──────────────────────────────────────────────────
const parallaxEl = ref(null)
const handleParallax = () => {
  if (!parallaxEl.value) return
  const rect = parallaxEl.value.getBoundingClientRect()
  const offset = window.innerHeight - rect.top
  if (offset > 0) {
    parallaxEl.value.style.transform = `translateY(${offset * 0.08}px)`
  }
}

onMounted(() => {
  setupReveal()
  startTestimonialRotator()
  window.addEventListener('scroll', handleParallax, { passive: true })
})

onUnmounted(() => {
  observers.forEach(o => o.disconnect())
  stopTestimonialRotator()
  window.removeEventListener('scroll', handleParallax)
})

// ── Stats ─────────────────────────────────────────────────────
const stats = [
  { value: '3.8x', label: 'Avg Organic Traffic Growth', sub: 'in first 12 months', color: 'purple' },
  { value: '94%', label: 'Client Retention Rate', sub: 'year-over-year', color: 'white' },
  { value: '#1', label: 'Rankings Achieved', sub: 'across 500+ keywords', color: 'img' },
  { value: '250M+', label: 'Impressions Delivered', sub: 'for client campaigns', color: 'white' },
  { value: '48h', label: 'Onboarding to Live', sub: 'fastest in the industry', color: 'purple' },
  { value: '4.9★', label: 'Average Rating', sub: 'from 200+ clients', color: 'white' },
]

const services = [
  { icon: 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', title: 'Keyword Research', desc: 'AI-powered intent mapping across 50M+ keyword signals.' },
  { icon: 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1', title: 'Link Building', desc: 'Editorial backlinks from DA 60+ authoritative publishers.' },
  { icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', title: 'Content Marketing', desc: 'Topical authority clusters that own entire SERPs.' },
  { icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', title: 'Analytics & Reporting', desc: 'Revenue-mapped dashboards updated in real time.' },
  { icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', title: 'Technical SEO', desc: 'Core Web Vitals, crawl optimisation, and schema markup.' },
  { icon: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', title: 'Local & Global SEO', desc: 'Geo-targeted strategies from city to continent.' },
]

const process = [
  { step: '01', title: 'Discovery & Audit', desc: 'Deep-dive technical analysis, competitor mapping, and opportunity scoring across 200+ ranking factors.', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
  { step: '02', title: 'Strategy Architecture', desc: 'Custom roadmap with content clusters, entity mapping, technical priorities, and 90-day milestone targets.', icon: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7' },
  { step: '03', title: 'Implementation', desc: 'Technical fixes deployed, on-page optimised, new content published, and link acquisition campaigns launched.', icon: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4' },
  { step: '04', title: 'Optimisation Loop', desc: 'Weekly data analysis, A/B testing, algorithm-aligned pivots, and content refresh cycles keep rankings climbing.', icon: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15' },
  { step: '05', title: 'Growth Reporting', desc: 'Monthly executive reports mapping organic performance directly to revenue, pipeline, and business KPIs.', icon: 'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' },
]

const whyUs = [
  { icon: 'M13 10V3L4 14h7v7l9-11h-7z', title: 'AI-First Research', desc: 'Proprietary models process 50M+ signals to uncover opportunities competitors miss entirely.' },
  { icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', title: 'Guaranteed SLAs', desc: 'Every engagement backed by contractual deliverable timelines and escalation protocols.' },
  { icon: 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3', title: 'Ethical Only', desc: 'Zero black-hat tactics. Every strategy built for long-term algorithmic resilience and trust signals.' },
  { icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', title: 'Dedicated Strategists', desc: 'Named account managers who know your industry, your competitors, and your growth targets.' },
]

const blogPosts = [
  { date: 'May 2025', tag: 'Technical SEO', title: 'Core Web Vitals 2025: The Complete Ranking Impact Guide', img: 'bg-gradient-to-br from-violet-600 to-purple-700' },
  { date: 'Apr 2025', tag: 'Content Strategy', title: 'Topical Authority: How to Own an Entire SERP Ecosystem', img: 'bg-gradient-to-br from-indigo-600 to-blue-700' },
  { date: 'Mar 2025', tag: 'Link Building', title: 'Digital PR for SEO: Building Links That Move Rankings', img: 'bg-gradient-to-br from-fuchsia-600 to-pink-700' },
]
</script>

<template>
  <Head>
    <title>{{ service.seo?.meta_title || 'SEO Optimization Services' }} — RankQuill</title>
    <meta name="description" :content="service.seo?.meta_description || 'Premium SEO services that drive measurable organic growth, rankings, and revenue for enterprise brands.'" />
    <meta name="robots" content="index, follow" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  </Head>

  <PublicLayout>
    <div style="font-family: 'Plus Jakarta Sans', Inter, sans-serif;">

      <!-- ═══════════════════════════════════════════════════════
           SECTION 1 · HERO
      ═══════════════════════════════════════════════════════ -->
      <section class="relative overflow-hidden bg-white" style="padding: 120px 0;">
        <!-- Subtle grid bg -->
        <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(#7c3aed08 1px, transparent 1px); background-size: 32px 32px;"></div>
        <!-- Lavender glow -->
        <div class="absolute top-0 right-0 w-[700px] h-[700px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(139,92,246,0.08) 0%, transparent 70%);"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(99,102,241,0.06) 0%, transparent 70%);"></div>

        <div class="max-w-[1320px] mx-auto px-6 relative z-10">
          <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- LEFT -->
            <div class="space-y-8">
              <div data-reveal="up" data-delay="0" class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full border text-sm font-semibold" style="background: rgba(139,92,246,0.08); border-color: rgba(139,92,246,0.2); color: #7c3aed;">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: #7c3aed;"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2" style="background: #7c3aed;"></span>
                </span>
                #1 Ranked SEO Agency — 200+ Enterprise Clients
              </div>

              <div data-reveal="up" data-delay="0.1">
                <h1 style="font-size: clamp(42px,5.5vw,72px); font-weight: 800; line-height: 1.08; letter-spacing: -1.5px; color: #0f0f1a;">
                  Dominate Search.<br/>
                  <span style="background: linear-gradient(135deg, #7c3aed, #6366f1, #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Drive Real Revenue.</span>
                </h1>
              </div>

              <p data-reveal="up" data-delay="0.2" style="font-size: 18px; line-height: 1.75; color: #64748b; max-width: 480px;">
                Enterprise-grade SEO engineered for measurable growth — AI-powered research, technical precision, and content authority that compounds month over month.
              </p>

              <div data-reveal="up" data-delay="0.35" class="flex flex-wrap gap-4">
                <Link href="/contact-us" class="inline-flex items-center gap-2.5 font-bold text-white rounded-full transition-all duration-300 hover:scale-[1.03]" style="background: linear-gradient(135deg, #7c3aed, #6366f1); padding: 16px 32px; font-size: 16px; box-shadow: 0 8px 32px rgba(124,58,237,0.35);">
                  Start Your SEO Campaign
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </Link>
                <a href="#process" class="inline-flex items-center gap-2 font-semibold rounded-full border transition-all duration-300 hover:border-purple-300" style="padding: 16px 28px; font-size: 15px; color: #374151; border-color: #e5e7eb;">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                  See Our Process
                </a>
              </div>

              <!-- Trust row -->
              <div data-reveal="up" data-delay="0.45" class="flex flex-wrap items-center gap-6 pt-2">
                <div class="flex -space-x-2">
                  <div v-for="(c,i) in ['from-violet-400 to-purple-600','from-indigo-400 to-blue-600','from-fuchsia-400 to-pink-600','from-cyan-400 to-indigo-600']" :key="i" :class="`w-9 h-9 rounded-full border-2 border-white bg-gradient-to-br ${c}`"></div>
                </div>
                <div class="text-sm text-slate-500"><span class="font-bold text-slate-900">200+</span> enterprises trust us</div>
                <div class="flex items-center gap-1">
                  <svg v-for="s in 5" :key="s" class="w-4 h-4 fill-current" style="color: #f59e0b;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                  <span class="text-xs text-slate-400 ml-1">4.9/5 · 200 reviews</span>
                </div>
              </div>
            </div>

            <!-- RIGHT — hero visual -->
            <div data-reveal="scale" data-delay="0.1" class="relative hidden lg:block">
              <!-- Main card -->
              <div class="relative rounded-3xl overflow-hidden" style="background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #e0e7ff 100%); padding: 48px 40px; min-height: 480px;">
                <!-- Decorative circles -->
                <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full" style="background: rgba(139,92,246,0.12);"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 rounded-full" style="background: rgba(99,102,241,0.1);"></div>

                <!-- SEO Rank Illustration -->
                <div class="relative z-10 space-y-6">
                  <!-- SERP mockup -->
                  <div class="rounded-2xl bg-white p-5 shadow-xl" style="box-shadow: 0 20px 60px rgba(0,0,0,0.08);">
                    <div class="text-xs text-slate-400 mb-3 font-medium">🔍  best seo agency for saas</div>
                    <div v-for="(r,i) in ['#1 · RankQuill — Premium SEO', '#2 · SEO Agency Pro', '#3 · Digital Marketers']" :key="i"
                      class="flex items-center gap-3 p-2.5 rounded-xl mb-1.5 transition-all"
                      :style="i===0 ? 'background: linear-gradient(135deg,rgba(124,58,237,0.08),rgba(99,102,241,0.06)); border: 1px solid rgba(124,58,237,0.15);' : 'background: #f8fafc;'">
                      <span class="text-xs font-black" :style="i===0 ? 'color:#7c3aed' : 'color:#94a3b8'">{{ i+1 }}</span>
                      <div>
                        <div class="text-xs font-semibold" :style="i===0 ? 'color:#1e1b4b' : 'color:#64748b'">{{ r.split('·')[1].trim() }}</div>
                        <div v-if="i===0" class="text-[10px] font-medium" style="color:#7c3aed">rankquill.com/seo</div>
                      </div>
                      <span v-if="i===0" class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full text-white" style="background:#7c3aed;">★ Top</span>
                    </div>
                  </div>

                  <!-- Two metric cards -->
                  <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl p-4 text-center" style="background: linear-gradient(135deg,#7c3aed,#6366f1);">
                      <div class="text-3xl font-black text-white">+312%</div>
                      <div class="text-[11px] text-purple-200 mt-1">Organic Traffic</div>
                    </div>
                    <div class="rounded-2xl p-4 text-center bg-white" style="box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                      <div class="text-3xl font-black" style="color:#7c3aed;">4.8x</div>
                      <div class="text-[11px] text-slate-400 mt-1">SEO ROI</div>
                    </div>
                  </div>

                  <!-- Bar chart sparkline -->
                  <div class="rounded-2xl bg-white p-4" style="box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <div class="flex items-end gap-1.5 h-12">
                      <div v-for="(h, i) in [20,35,28,45,38,60,55,72,68,85,78,100]" :key="i"
                        class="flex-1 rounded-t transition-all duration-500"
                        :style="`height: ${h}%; background: ${i >= 9 ? 'linear-gradient(180deg,#7c3aed,#a855f7)' : '#e0e7ff'};`"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                      <span class="text-[10px] text-slate-400">Jan</span>
                      <span class="text-[10px] font-bold" style="color:#7c3aed;">+100% ↑</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Floating cards -->
              <div class="absolute -top-6 -left-8 rounded-2xl px-4 py-3 shadow-2xl animate-float" style="background: #fff; animation-duration: 4s;">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg,#7c3aed,#6366f1);">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                  </div>
                  <div>
                    <div class="text-[11px] font-black text-slate-900">Rankings ↑</div>
                    <div class="text-[10px] text-slate-400">Page 1 in 60 days</div>
                  </div>
                </div>
              </div>

              <div class="absolute -bottom-4 -right-6 rounded-2xl px-4 py-3 shadow-2xl" style="animation: floatB 4.5s ease-in-out infinite alternate; background: linear-gradient(135deg,#7c3aed,#6366f1);">
                <div class="text-[11px] font-bold text-white">Core Web Vitals</div>
                <div class="flex gap-1 mt-1">
                  <span v-for="m in ['LCP ✓','FID ✓','CLS ✓']" :key="m" class="text-[9px] font-semibold px-1.5 py-0.5 rounded-md text-purple-200" style="background: rgba(255,255,255,0.15);">{{ m }}</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 2 · STATISTICS GRID
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 80px 0; background: #faf9ff;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
            <div v-for="(s, i) in stats" :key="i" data-reveal="up" :data-delay="(i*0.08).toFixed(2)"
              class="group rounded-2xl p-6 cursor-default transition-all duration-300 hover:-translate-y-2"
              :style="`
                ${s.color==='purple' ? 'background: linear-gradient(135deg,#7c3aed,#6366f1); color: white;' : ''}
                ${s.color==='white' ? 'background: white; border: 1px solid #ede9fe;' : ''}
                ${s.color==='img' ? 'background: linear-gradient(135deg,#1e1b4b,#2e1065); color: white;' : ''}
                box-shadow: 0 4px 24px rgba(0,0,0,0.06);
                border-radius: 20px;
              `">
              <div class="text-3xl font-black mb-1" :style="s.color==='white' ? 'color:#7c3aed' : 'color:white'">{{ s.value }}</div>
              <div class="text-xs font-semibold mb-0.5" :style="s.color==='white' ? 'color:#1e1b4b' : 'color:rgba(255,255,255,0.9)'">{{ s.label }}</div>
              <div class="text-[10px]" :style="s.color==='white' ? 'color:#94a3b8' : 'color:rgba(255,255,255,0.6)'">{{ s.sub }}</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 3 · AGENCY INTRO (50/50)
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 120px 0; background: white;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Image -->
            <div data-reveal="left" class="relative">
              <div class="rounded-3xl overflow-hidden" style="background: linear-gradient(135deg,#ede9fe,#e0e7ff); padding: 40px; aspect-ratio: 4/3; position: relative;">
                <div class="absolute inset-0 rounded-3xl" style="background: linear-gradient(135deg,#7c3aed08,#6366f108);"></div>
                <!-- Organic traffic growth visualization -->
                <div class="relative h-full flex flex-col justify-between">
                  <div class="text-sm font-bold" style="color:#7c3aed;">📈 Organic Traffic Growth · 12-Month View</div>
                  <div class="flex items-end gap-2 h-32">
                    <div v-for="(h,i) in [15,22,18,30,35,28,48,55,50,70,75,100]" :key="i"
                      class="flex-1 rounded-t-lg"
                      :style="`height:${h}%; background: ${i>=8 ? 'linear-gradient(180deg,#7c3aed,#a855f7)' : 'linear-gradient(180deg,#c4b5fd,#ddd6fe)'};`">
                    </div>
                  </div>
                  <div class="grid grid-cols-3 gap-3">
                    <div v-for="m in [{l:'Sessions',v:'2.4M',c:'#7c3aed'},{l:'Rankings',v:'+847',c:'#6366f1'},{l:'Revenue',v:'+$1.2M',c:'#a855f7'}]" :key="m.l"
                      class="rounded-xl p-3 text-center bg-white" style="box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                      <div class="text-lg font-black" :style="`color:${m.c}`">{{ m.v }}</div>
                      <div class="text-[10px] text-slate-400 mt-0.5">{{ m.l }}</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Badge -->
              <div class="absolute -bottom-5 -right-5 rounded-2xl px-5 py-4 shadow-2xl" style="background: linear-gradient(135deg,#7c3aed,#6366f1);">
                <div class="text-2xl font-black text-white">6+ yrs</div>
                <div class="text-[11px] text-purple-200">Enterprise SEO</div>
              </div>
            </div>

            <!-- Content -->
            <div data-reveal="right" class="space-y-7">
              <div>
                <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background: rgba(124,58,237,0.08); color: #7c3aed; letter-spacing: 2px;">Why RankQuill</span>
                <h2 style="font-size: clamp(32px,3.5vw,48px); font-weight: 800; line-height: 1.15; color: #0f0f1a; letter-spacing: -0.5px;">
                  SEO That Compounds.<br/>Not Just Rankings.
                </h2>
              </div>
              <p style="font-size: 16px; line-height: 1.75; color: #64748b;">
                We build organic growth ecosystems — technical foundations, authority signals, and intent-matched content — that keep working long after the first campaign ends. Our clients don't just rank. They own their category.
              </p>
              <ul class="space-y-4">
                <li v-for="f in ['AI-powered keyword & entity research across 50M+ data signals','Technical SEO: Core Web Vitals, schema, and crawl budget mastery','Content clusters that build topical authority at scale','Editorial link acquisition from DA 60+ publishers']" :key="f"
                  class="flex items-start gap-3">
                  <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5" style="background: linear-gradient(135deg,#7c3aed,#6366f1);">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <span style="font-size: 15px; color: #374151; font-weight: 500;">{{ f }}</span>
                </li>
              </ul>
              <Link href="/contact-us" class="inline-flex items-center gap-2 font-bold rounded-full transition-all duration-300 hover:scale-[1.03]" style="background: linear-gradient(135deg,#7c3aed,#6366f1); color: white; padding: 14px 28px; font-size: 15px; box-shadow: 0 8px 24px rgba(124,58,237,0.3);">
                Get Your Free Audit
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </Link>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 4 · SERVICES AROUND ROCKET
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 120px 0; background: linear-gradient(180deg, #f5f3ff 0%, #ede9fe 100%);">
        <div class="max-w-[1320px] mx-auto px-6">
          <div data-reveal="up" class="text-center mb-16">
            <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background: rgba(124,58,237,0.1); color: #7c3aed; letter-spacing: 2px;">Full-Spectrum SEO</span>
            <h2 style="font-size: clamp(32px,3.5vw,48px); font-weight: 800; color: #0f0f1a; letter-spacing: -0.5px;">Everything Your Rankings Need</h2>
            <p style="font-size: 16px; color: #64748b; margin-top: 12px; max-width: 520px; margin-left: auto; margin-right: auto; line-height: 1.7;">Six interlocking SEO disciplines working in concert to build unstoppable organic momentum.</p>
          </div>

          <!-- Rocket + cards layout -->
          <div class="relative grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            <!-- Left column -->
            <div class="space-y-6">
              <div v-for="(s, i) in services.slice(0,3)" :key="i" data-reveal="left" :data-delay="(i*0.1).toFixed(1)"
                class="group rounded-2xl p-6 bg-white transition-all duration-300 hover:-translate-y-2 hover:shadow-xl cursor-default"
                style="box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg,rgba(124,58,237,0.1),rgba(99,102,241,0.08));">
                  <svg class="w-5 h-5" style="color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="s.icon"/></svg>
                </div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e1b4b; margin-bottom: 6px;">{{ s.title }}</h3>
                <p style="font-size: 13px; color: #64748b; line-height: 1.65;">{{ s.desc }}</p>
              </div>
            </div>

            <!-- Center rocket -->
            <div class="flex justify-center py-8">
              <div class="relative" style="animation: rocketFloat 5s ease-in-out infinite;">
                <!-- Glow -->
                <div class="absolute inset-0 rounded-full blur-3xl" style="background: rgba(124,58,237,0.25); transform: scale(1.3);"></div>
                <!-- Rocket SVG -->
                <svg class="relative" width="200" height="260" viewBox="0 0 200 260" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <!-- Exhaust flame -->
                  <ellipse cx="100" cy="235" rx="18" ry="28" fill="url(#flameGrad)" opacity="0.8" style="animation: flameFlicker 0.4s ease-in-out infinite alternate;"/>
                  <ellipse cx="100" cy="220" rx="10" ry="16" fill="url(#flameGrad2)" opacity="0.9"/>
                  <!-- Body -->
                  <path d="M100 20 C75 40 60 90 60 140 L60 185 C60 185 80 195 100 195 C120 195 140 185 140 185 L140 140 C140 90 125 40 100 20Z" fill="url(#rocketBody)"/>
                  <!-- Window -->
                  <circle cx="100" cy="105" r="22" fill="url(#windowGrad)" stroke="white" stroke-width="3"/>
                  <circle cx="100" cy="105" r="14" fill="white" opacity="0.3"/>
                  <circle cx="95" cy="100" r="5" fill="white" opacity="0.6"/>
                  <!-- Left wing -->
                  <path d="M60 145 L30 185 L60 175 Z" fill="url(#wingGrad)"/>
                  <!-- Right wing -->
                  <path d="M140 145 L170 185 L140 175 Z" fill="url(#wingGrad)"/>
                  <!-- Nose stripe -->
                  <path d="M100 20 C95 30 90 50 88 70 L112 70 C110 50 105 30 100 20Z" fill="white" opacity="0.2"/>
                  <!-- Stars -->
                  <circle cx="40" cy="60" r="3" fill="#a78bfa"/>
                  <circle cx="165" cy="80" r="2" fill="#c4b5fd"/>
                  <circle cx="25" cy="120" r="2" fill="#818cf8"/>
                  <circle cx="175" cy="130" r="3" fill="#a78bfa"/>
                  <circle cx="50" cy="40" r="1.5" fill="#ddd6fe"/>
                  <defs>
                    <linearGradient id="rocketBody" x1="60" y1="20" x2="140" y2="195" gradientUnits="userSpaceOnUse">
                      <stop offset="0%" stop-color="#7c3aed"/>
                      <stop offset="100%" stop-color="#4f46e5"/>
                    </linearGradient>
                    <linearGradient id="wingGrad" x1="0" y1="0" x2="1" y2="1">
                      <stop offset="0%" stop-color="#6d28d9"/>
                      <stop offset="100%" stop-color="#4338ca"/>
                    </linearGradient>
                    <radialGradient id="windowGrad" cx="50%" cy="40%">
                      <stop offset="0%" stop-color="#c4b5fd"/>
                      <stop offset="100%" stop-color="#7c3aed"/>
                    </radialGradient>
                    <linearGradient id="flameGrad" x1="100" y1="207" x2="100" y2="263" gradientUnits="userSpaceOnUse">
                      <stop offset="0%" stop-color="#f59e0b"/>
                      <stop offset="100%" stop-color="#ef4444" stop-opacity="0"/>
                    </linearGradient>
                    <linearGradient id="flameGrad2" x1="100" y1="204" x2="100" y2="236" gradientUnits="userSpaceOnUse">
                      <stop offset="0%" stop-color="#fcd34d"/>
                      <stop offset="100%" stop-color="#f97316" stop-opacity="0.5"/>
                    </linearGradient>
                  </defs>
                </svg>
                <!-- Labels around rocket -->
                <div class="absolute -top-2 left-1/2 -translate-x-1/2 text-xs font-bold px-3 py-1.5 rounded-full text-white shadow-lg" style="background: linear-gradient(135deg,#7c3aed,#6366f1); white-space: nowrap;">🚀 Organic Growth Engine</div>
              </div>
            </div>

            <!-- Right column -->
            <div class="space-y-6">
              <div v-for="(s, i) in services.slice(3,6)" :key="i" data-reveal="right" :data-delay="(i*0.1).toFixed(1)"
                class="group rounded-2xl p-6 bg-white transition-all duration-300 hover:-translate-y-2 hover:shadow-xl cursor-default"
                style="box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg,rgba(124,58,237,0.1),rgba(99,102,241,0.08));">
                  <svg class="w-5 h-5" style="color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="s.icon"/></svg>
                </div>
                <h3 style="font-size: 16px; font-weight: 700; color: #1e1b4b; margin-bottom: 6px;">{{ s.title }}</h3>
                <p style="font-size: 13px; color: #64748b; line-height: 1.65;">{{ s.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 5 · GROWTH PROCESS TIMELINE
      ═══════════════════════════════════════════════════════ -->
      <section id="process" style="padding: 120px 0; background: white;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div data-reveal="up" class="text-center mb-16">
            <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background: rgba(124,58,237,0.08); color: #7c3aed; letter-spacing: 2px;">Our Methodology</span>
            <h2 style="font-size: clamp(32px,3.5vw,48px); font-weight: 800; color: #0f0f1a; letter-spacing: -0.5px;">From Audit to Growth</h2>
          </div>

          <div class="relative">
            <!-- Center vertical line -->
            <div class="hidden lg:block absolute left-1/2 -translate-x-px top-0 bottom-0 w-0.5" style="background: linear-gradient(180deg,#7c3aed,#6366f1,#a855f7);"></div>

            <div v-for="(step, i) in process" :key="i" data-reveal="up" :data-delay="(i*0.1).toFixed(1)"
              class="relative flex flex-col lg:flex-row items-center gap-8 mb-12 last:mb-0"
              :class="i % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'">

              <!-- Card -->
              <div class="lg:w-5/12 rounded-2xl p-7 border transition-all duration-300 hover:shadow-xl"
                style="border-color: #ede9fe; background: white; box-shadow: 0 4px 24px rgba(0,0,0,0.04);">
                <div class="flex items-center gap-3 mb-4">
                  <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg,#7c3aed,#6366f1);">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="step.icon"/></svg>
                  </div>
                  <span class="text-xs font-black" style="color: #c4b5fd; letter-spacing: 2px;">STEP {{ step.step }}</span>
                </div>
                <h3 style="font-size: 20px; font-weight: 700; color: #1e1b4b; margin-bottom: 8px;">{{ step.title }}</h3>
                <p style="font-size: 14px; color: #64748b; line-height: 1.7;">{{ step.desc }}</p>
              </div>

              <!-- Center circle -->
              <div class="hidden lg:flex w-2/12 justify-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-black text-sm z-10 shadow-lg" style="background: linear-gradient(135deg,#7c3aed,#6366f1);">{{ step.step }}</div>
              </div>

              <!-- Empty spacer -->
              <div class="hidden lg:block lg:w-5/12"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 6 · PORTFOLIO SHOWCASE (DARK)
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 120px 0; background: #0f0f1a;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div data-reveal="up" class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-6">
            <div>
              <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background: rgba(124,58,237,0.15); color: #a78bfa; letter-spacing: 2px;">Case Studies</span>
              <h2 style="font-size: clamp(28px,3vw,44px); font-weight: 800; color: white; letter-spacing: -0.5px;">Results That Define Us</h2>
            </div>
            <Link href="/portfolio" class="inline-flex items-center gap-2 font-semibold border rounded-full px-6 py-3 text-sm transition-all hover:border-purple-500 hover:text-purple-400" style="border-color: rgba(255,255,255,0.15); color: rgba(255,255,255,0.6);">
              All Case Studies →
            </Link>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div v-for="(p,i) in [
              {title:'SaaS Brand — 3.8x Traffic Growth',metric:'+312% Organic',tag:'B2B SaaS',grad:'from-violet-800 to-purple-900'},
              {title:'E-commerce — Page 1 for 2,000 SKUs',metric:'+$1.4M Revenue',tag:'E-commerce',grad:'from-indigo-800 to-blue-900'},
              {title:'FinTech — Authority Domain Build',metric:'DA 18→56 in 8mo',tag:'FinTech',grad:'from-fuchsia-800 to-pink-900'},
              {title:'Healthcare — Local SEO Dominance',metric:'#1 in 40 Cities',tag:'Healthcare',grad:'from-slate-700 to-slate-900'},
            ]" :key="i" data-reveal="up" :data-delay="(i*0.08).toFixed(2)"
              class="group relative rounded-2xl overflow-hidden cursor-pointer"
              style="aspect-ratio: 3/4;">
              <div :class="`absolute inset-0 bg-gradient-to-br ${p.grad} transition-transform duration-500 group-hover:scale-110`"></div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
              <!-- Content -->
              <div class="absolute inset-0 p-6 flex flex-col justify-between">
                <span class="inline-block self-start text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full text-white" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);">{{ p.tag }}</span>
                <div>
                  <div class="text-lg font-bold text-white leading-tight mb-2">{{ p.title }}</div>
                  <div class="inline-flex items-center gap-1.5 text-sm font-black px-3 py-1.5 rounded-full transition-all duration-300 translate-y-2 opacity-0 group-hover:opacity-100 group-hover:translate-y-0" style="background: linear-gradient(135deg,#7c3aed,#6366f1); color: white;">
                    {{ p.metric }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 7 · WHY CHOOSE US
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 120px 0; background: white;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div data-reveal="up" class="text-center mb-16">
            <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background: rgba(124,58,237,0.08); color: #7c3aed; letter-spacing: 2px;">The RankQuill Advantage</span>
            <h2 style="font-size: clamp(32px,3.5vw,48px); font-weight: 800; color: #0f0f1a; letter-spacing: -0.5px;">Why Industry Leaders Choose Us</h2>
          </div>
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="(w, i) in whyUs" :key="i" data-reveal="up" :data-delay="(i*0.1).toFixed(1)"
              class="group text-center space-y-4">
              <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl" style="background: linear-gradient(135deg,#7c3aed,#6366f1); box-shadow: 0 8px 24px rgba(124,58,237,0.25);">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="w.icon"/></svg>
              </div>
              <h3 style="font-size: 18px; font-weight: 700; color: #1e1b4b;">{{ w.title }}</h3>
              <p style="font-size: 14px; color: #64748b; line-height: 1.7;">{{ w.desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 8 · TEAM / WORKSPACE (PARALLAX)
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 120px 0; background: #faf9ff; overflow: hidden;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div data-reveal="left" class="space-y-7">
              <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full" style="background: rgba(124,58,237,0.08); color: #7c3aed; letter-spacing: 2px;">Our Team</span>
              <h2 style="font-size: clamp(32px,3.5vw,48px); font-weight: 800; color: #0f0f1a; letter-spacing: -0.5px;">SEO Experts Who Are Also<br/>Business Strategists</h2>
              <p style="font-size: 16px; color: #64748b; line-height: 1.75;">Our team of 40+ SEO professionals spans technical engineering, content strategy, data science, and digital PR — each bringing deep vertical expertise and a relentless focus on revenue outcomes.</p>
              <div class="grid grid-cols-2 gap-5">
                <div v-for="m in [{v:'40+',l:'SEO Specialists'},{v:'15+',l:'Industry Verticals'},{v:'8+',l:'Avg Years Exp.'},{v:'24/7',l:'Campaign Monitoring'}]" :key="m.l"
                  class="rounded-2xl p-5 border" style="border-color: #ede9fe; background: white;">
                  <div class="text-2xl font-black mb-1" style="color:#7c3aed;">{{ m.v }}</div>
                  <div class="text-xs text-slate-500 font-medium">{{ m.l }}</div>
                </div>
              </div>
            </div>
            <div data-reveal="right" class="relative">
              <div ref="parallaxEl" class="rounded-3xl overflow-hidden" style="background: linear-gradient(135deg,#1e1b4b,#2e1065); padding: 48px 40px; aspect-ratio: 4/3; transition: transform 0.1s linear;">
                <!-- Team illustration: abstract data workspace -->
                <div class="space-y-4">
                  <div class="flex items-center gap-3">
                    <div v-for="a in ['RQ','SM','PK','AJ']" :key="a" class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-black text-white" style="background: linear-gradient(135deg,#7c3aed,#6366f1);">{{ a }}</div>
                    <span class="text-xs text-purple-300 ml-2">+ 36 more specialists</span>
                  </div>
                  <div class="rounded-2xl p-5 bg-white/5 border border-white/10 space-y-3">
                    <div class="text-xs text-purple-300 font-bold uppercase tracking-wider">Live Campaign Dashboard</div>
                    <div v-for="c in [{l:'Keyword Rankings',v:87,col:'#a78bfa'},{l:'Backlinks Built',v:64,col:'#818cf8'},{l:'Content Published',v:92,col:'#c084fc'}]" :key="c.l" class="space-y-1">
                      <div class="flex justify-between text-[11px]"><span class="text-purple-300">{{ c.l }}</span><span class="font-bold text-white">{{ c.v }}%</span></div>
                      <div class="h-1.5 rounded-full bg-white/10 overflow-hidden"><div class="h-full rounded-full" :style="`width:${c.v}%; background:${c.col};`"></div></div>
                    </div>
                  </div>
                  <div class="grid grid-cols-2 gap-3">
                    <div v-for="s in [{l:'Campaigns Active',v:'48'},{l:'Avg CTR',v:'+6.2%'}]" :key="s.l" class="rounded-xl p-3 text-center" style="background: rgba(255,255,255,0.06);">
                      <div class="text-xl font-black text-white">{{ s.v }}</div>
                      <div class="text-[10px] text-purple-300 mt-0.5">{{ s.l }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 9 · TESTIMONIALS ROTATOR
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 120px 0; background: white;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div data-reveal="up" class="text-center mb-16">
            <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background: rgba(124,58,237,0.08); color: #7c3aed; letter-spacing: 2px;">Client Love</span>
            <h2 style="font-size: clamp(32px,3.5vw,48px); font-weight: 800; color: #0f0f1a; letter-spacing: -0.5px;">What Our Clients Say</h2>
          </div>

          <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Large avatar -->
            <div data-reveal="left" class="relative">
              <div class="rounded-3xl overflow-hidden" style="aspect-ratio: 4/3; background: linear-gradient(135deg,#7c3aed,#6366f1,#a855f7);">
                <div class="absolute inset-0 flex items-center justify-center">
                  <div class="text-center space-y-4 p-8">
                    <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center text-4xl font-black text-white border-4 border-white/30" style="background: rgba(255,255,255,0.15);">
                      {{ testimonials[activeTestimonial].avatar }}
                    </div>
                    <div class="text-white font-bold text-xl">{{ testimonials[activeTestimonial].name }}</div>
                    <div class="text-purple-200 text-sm">{{ testimonials[activeTestimonial].role }}</div>
                    <div class="text-purple-200 text-sm font-semibold">{{ testimonials[activeTestimonial].company }}</div>
                    <div class="flex justify-center gap-1">
                      <svg v-for="s in testimonials[activeTestimonial].rating" :key="s" class="w-5 h-5 fill-current" style="color:#fcd34d;" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Dot nav -->
              <div class="flex justify-center gap-2 mt-5">
                <button v-for="(t,i) in testimonials" :key="i" @click="activeTestimonial = i; stopTestimonialRotator(); startTestimonialRotator()"
                  class="rounded-full transition-all duration-300"
                  :style="`width: ${activeTestimonial === i ? '24px' : '8px'}; height: 8px; background: ${activeTestimonial === i ? 'linear-gradient(135deg,#7c3aed,#6366f1)' : '#e0e7ff'};`">
                </button>
              </div>
            </div>

            <!-- Review card -->
            <div data-reveal="right">
              <transition name="slide-up" mode="out-in">
                <div :key="activeTestimonial" class="rounded-3xl p-10 border" style="border-color: #ede9fe; background: #faf9ff; box-shadow: 0 8px 40px rgba(124,58,237,0.08);">
                  <svg class="w-12 h-12 mb-6" style="color: #c4b5fd;" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                  <p style="font-size: 17px; line-height: 1.75; color: #374151; font-style: italic; margin-bottom: 32px;">"{{ testimonials[activeTestimonial].text }}"</p>
                  <div class="border-t pt-6" style="border-color: #ede9fe;">
                    <div style="font-size: 16px; font-weight: 700; color: #1e1b4b;">{{ testimonials[activeTestimonial].name }}</div>
                    <div style="font-size: 13px; color: #94a3b8; margin-top: 3px;">{{ testimonials[activeTestimonial].role }} · {{ testimonials[activeTestimonial].company }}</div>
                  </div>
                </div>
              </transition>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 10 · FAQ ACCORDION
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 120px 0; background: #faf9ff;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div data-reveal="up" class="text-center mb-16">
            <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background: rgba(124,58,237,0.08); color: #7c3aed; letter-spacing: 2px;">FAQ</span>
            <h2 style="font-size: clamp(32px,3.5vw,48px); font-weight: 800; color: #0f0f1a; letter-spacing: -0.5px;">Frequently Asked Questions</h2>
          </div>
          <div class="max-w-3xl mx-auto space-y-4">
            <div v-for="(faq, i) in faqs" :key="i" data-reveal="up" :data-delay="(i*0.05).toFixed(2)"
              class="rounded-2xl border overflow-hidden transition-all duration-300"
              :style="`border-color: ${openFaq===i ? '#a78bfa' : '#ede9fe'}; background: white; box-shadow: ${openFaq===i ? '0 8px 32px rgba(124,58,237,0.12)' : '0 2px 12px rgba(0,0,0,0.04)'};`">
              <button @click="toggleFaq(i)" class="w-full flex items-center justify-between gap-4 p-6 text-left">
                <span style="font-size: 16px; font-weight: 600; color: #1e1b4b;">{{ faq.q }}</span>
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-400" :style="`color: #7c3aed; transform: rotate(${openFaq===i ? 180 : 0}deg);`" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="overflow-hidden transition-all duration-400" :style="`max-height: ${openFaq===i ? '300px' : '0'}; opacity: ${openFaq===i ? 1 : 0};`">
                <p class="px-6 pb-6" style="font-size: 15px; line-height: 1.75; color: #64748b;">{{ faq.a }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 11 · CTA (DARK PURPLE GRADIENT)
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 120px 0; background: white;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div class="relative rounded-3xl overflow-hidden" style="background: linear-gradient(135deg,#2e1065,#1e1b4b,#312e81);">
            <!-- Pulsing glow -->
            <div class="absolute inset-0 pointer-events-none" style="animation: ctaGlow 4s ease-in-out infinite;">
              <div class="absolute top-0 left-1/4 w-[400px] h-[400px] rounded-full" style="background: radial-gradient(circle,rgba(124,58,237,0.4) 0%,transparent 70%); filter: blur(60px);"></div>
              <div class="absolute bottom-0 right-1/4 w-[300px] h-[300px] rounded-full" style="background: radial-gradient(circle,rgba(99,102,241,0.3) 0%,transparent 70%); filter: blur(60px);"></div>
            </div>
            <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,0.03) 1px,transparent 1px); background-size: 28px 28px;"></div>

            <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center p-12 sm:p-16 lg:p-20">
              <!-- Left -->
              <div class="space-y-7">
                <div>
                  <h2 style="font-size: clamp(32px,3.5vw,52px); font-weight: 800; color: white; line-height: 1.1; letter-spacing: -0.5px;">
                    Ready to Dominate<br/>Your Search Category?
                  </h2>
                  <p class="mt-4" style="font-size: 17px; color: rgba(196,181,253,0.9); line-height: 1.7;">
                    Book a free 45-minute strategy call. We'll audit your current rankings, identify your biggest opportunities, and build you a custom 90-day SEO roadmap — no obligation.
                  </p>
                </div>
                <div class="flex flex-wrap gap-4">
                  <Link href="/contact-us" class="inline-flex items-center gap-2.5 font-bold rounded-full text-indigo-700 transition-all duration-300 hover:scale-[1.05]" style="background: white; padding: 16px 32px; font-size: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
                    Book Free Strategy Call
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                  </Link>
                  <Link href="/services" class="inline-flex items-center gap-2 font-semibold rounded-full border transition-all hover:border-purple-400 hover:text-purple-300" style="border-color: rgba(255,255,255,0.2); color: rgba(255,255,255,0.7); padding: 16px 24px; font-size: 15px;">
                    Explore All Services
                  </Link>
                </div>
                <!-- Trust signals -->
                <div class="flex flex-wrap gap-4 pt-2">
                  <div v-for="t in ['✅ No long-term contracts','✅ Results in 60 days','✅ Money-back guarantee']" :key="t" class="text-xs font-semibold" style="color: rgba(196,181,253,0.8);">{{ t }}</div>
                </div>
              </div>

              <!-- Right: analytics illustration -->
              <div class="space-y-4">
                <div class="rounded-2xl p-5" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                  <div class="text-xs font-bold text-purple-300 uppercase tracking-wider mb-4">Your Potential Growth</div>
                  <div class="flex items-end gap-1.5 h-20">
                    <div v-for="(h,i) in [20,30,25,40,45,38,60,55,70,80,88,100]" :key="i"
                      class="flex-1 rounded-t"
                      :style="`height:${h}%; background: ${i>=8 ? 'rgba(167,139,250,0.9)' : 'rgba(255,255,255,0.1)'};`">
                    </div>
                  </div>
                  <div class="flex justify-between mt-3">
                    <span class="text-[11px] text-purple-300">Month 1</span>
                    <span class="text-[11px] font-bold text-white">Month 12 → +100%</span>
                  </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                  <div v-for="m in [{v:'3.8x',l:'Traffic Growth'},{v:'94%',l:'Retention'},{v:'$4.2M',l:'Revenue Generated'}]" :key="m.l" class="rounded-xl p-3 text-center" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                    <div class="text-lg font-black text-white">{{ m.v }}</div>
                    <div class="text-[10px] text-purple-300 mt-0.5">{{ m.l }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════
           SECTION 12 · BLOG
      ═══════════════════════════════════════════════════════ -->
      <section style="padding: 80px 0 120px; background: #faf9ff;">
        <div class="max-w-[1320px] mx-auto px-6">
          <div data-reveal="up" class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-6">
            <div>
              <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full mb-4" style="background: rgba(124,58,237,0.08); color: #7c3aed; letter-spacing: 2px;">SEO Knowledge</span>
              <h2 style="font-size: clamp(28px,3vw,40px); font-weight: 800; color: #0f0f1a; letter-spacing: -0.5px;">Fresh SEO Insights</h2>
            </div>
            <Link href="/blog" class="inline-flex items-center gap-2 font-semibold text-sm transition-colors hover:text-purple-600" style="color: #7c3aed;">
              All Articles
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </Link>
          </div>

          <div class="grid md:grid-cols-3 gap-6">
            <div v-for="(post, i) in blogPosts" :key="i" data-reveal="up" :data-delay="(i*0.1).toFixed(1)"
              class="group rounded-2xl overflow-hidden bg-white border transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl cursor-pointer"
              style="border-color: #ede9fe; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
              <!-- Image -->
              <div class="relative h-48 overflow-hidden" :class="post.img">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                <span class="absolute top-4 left-4 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full text-white" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);">{{ post.tag }}</span>
                <span class="absolute bottom-4 right-4 text-[10px] text-white/70">{{ post.date }}</span>
              </div>
              <!-- Content -->
              <div class="p-6 space-y-4">
                <h3 class="font-bold leading-snug transition-colors group-hover:text-purple-700" style="font-size: 16px; color: #1e1b4b;">{{ post.title }}</h3>
                <div class="flex items-center gap-1.5 font-semibold transition-all duration-300 group-hover:gap-3" style="font-size: 13px; color: #7c3aed;">
                  Read Article
                  <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>

    <!-- ── Animation Keyframes ─────────────────────────────────── -->
    <style>
      @keyframes rocketFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
      }
      @keyframes floatB {
        0% { transform: translateY(0px); }
        100% { transform: translateY(-12px); }
      }
      @keyframes flameFlicker {
        0% { transform: scaleX(1) scaleY(1); opacity: 0.8; }
        100% { transform: scaleX(1.15) scaleY(1.1); opacity: 0.6; }
      }
      @keyframes ctaGlow {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
      }
      .slide-up-enter-active, .slide-up-leave-active {
        transition: all 0.4s ease;
      }
      .slide-up-enter-from {
        opacity: 0; transform: translateY(20px);
      }
      .slide-up-leave-to {
        opacity: 0; transform: translateY(-10px);
      }
    </style>

  </PublicLayout>
</template>
