<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  service: { type: Object, required: true }
})

// ── Interactive State for Tech Tabs ───────────────────────────
const activeTab = ref('Models & LLMs')
const tabs = ['Models & LLMs', 'Frameworks & Agents', 'Vector Databases', 'Dev & Integrations']

const techData = {
  'Models & LLMs': [
    { name: 'GPT-4o', bg: '#10a37f' },
    { name: 'Claude 3.5 Sonnet', bg: '#d97706' },
    { name: 'Gemini 1.5 Pro', bg: '#1a73e8' },
    { name: 'Llama 3', bg: '#044eab' },
    { name: 'Mistral Large', bg: '#ff5a1f' },
    { name: 'DeepSeek', bg: '#0052cc' }
  ],
  'Frameworks & Agents': [
    { name: 'LangChain', bg: '#1c1c1c' },
    { name: 'LlamaIndex', bg: '#4e2a84' },
    { name: 'LangGraph', bg: '#13b5a2' },
    { name: 'CrewAI', bg: '#e11d48' },
    { name: 'AutoGen', bg: '#2563eb' }
  ],
  'Vector Databases': [
    { name: 'Pinecone', bg: '#121214' },
    { name: 'Qdrant', bg: '#ff2d55' },
    { name: 'ChromaDB', bg: '#0052cc' },
    { name: 'pgvector', bg: '#336791' },
    { name: 'Weaviate', bg: '#1c1c28' }
  ],
  'Dev & Integrations': [
    { name: 'Hugging Face', bg: '#ffbb00' },
    { name: 'OpenAI API', bg: '#10a37f' },
    { name: 'AWS Bedrock', bg: '#ff9900' },
    { name: 'GCP Vertex AI', bg: '#4285f4' },
    { name: 'Vercel AI SDK', bg: '#000000' }
  ]
}

// Custom SVGs for Tech Logos
const svgLogos = {
  'GPT-4o': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#10a37f" opacity="0.15"/><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 14h-2v-2h2zm0-4h-2V7h2z" fill="#10a37f"/></svg>`,
  'Claude 3.5 Sonnet': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#d97706" opacity="0.15"/><path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 15a3 3 0 1 1 3-3 3 3 0 0 1-3 3z" fill="#d97706"/></svg>`,
  'Gemini 1.5 Pro': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#1a73e8" opacity="0.15"/><path d="M12 2L4 12l8 10 8-10L12 2zm0 15a5 5 0 1 1 5-5 5 5 0 0 1-5 5z" fill="#1a73e8"/></svg>`,
  'Llama 3': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#044eab" opacity="0.15"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="12" fill="#044eab" text-anchor="middle">L3</text></svg>`,
  'Mistral Large': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#ff5a1f" opacity="0.15"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="12" fill="#ff5a1f" text-anchor="middle">M</text></svg>`,
  'DeepSeek': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#0052cc" opacity="0.15"/><path d="M12 2L2 12h20L12 2z" fill="#0052cc"/></svg>`,
  'LangChain': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#1c1c1c" opacity="0.15"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11" fill="#1c1c1c" text-anchor="middle">LC</text></svg>`,
  'LlamaIndex': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#4e2a84" opacity="0.15"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="11" fill="#4e2a84" text-anchor="middle">LI</text></svg>`,
  'LangGraph': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#13b5a2" opacity="0.15"/><circle cx="8" cy="12" r="3" fill="#13b5a2"/><circle cx="16" cy="12" r="3" fill="#13b5a2"/><path d="M8 12h8" stroke="#13b5a2" stroke-width="2"/></svg>`,
  'CrewAI': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#e11d48" opacity="0.15"/><path d="M12 4l8 8-8 8-8-8zM12 9a3 3 0 1 0 3 3 3 3 0 0 0-3-3z" fill="#e11d48"/></svg>`,
  'AutoGen': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#2563eb" opacity="0.15"/><path d="M12 2L2 7v10l10 5 10-5V7zm-2 11H8V9h2zm4 2h-2v-4h2z" fill="#2563eb"/></svg>`,
  'Pinecone': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#121214" opacity="0.15"/><path d="M12 3l8 6v6l-8 6-8-6V9zM12 9a3 3 0 1 0 3 3 3 3 0 0 0-3-3z" fill="#121214"/></svg>`,
  'Qdrant': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#ff2d55" opacity="0.15"/><circle cx="12" cy="12" r="6" stroke="#ff2d55" stroke-width="2"/></svg>`,
  'ChromaDB': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#0052cc" opacity="0.15"/><circle cx="12" cy="12" r="5" fill="#0052cc"/><circle cx="12" cy="12" r="2" fill="#fff"/></svg>`,
  'pgvector': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#336791" opacity="0.15"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="10" fill="#336791" text-anchor="middle">PG</text></svg>`,
  'Weaviate': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#1c1c28" opacity="0.15"/><text x="12" y="16.5" font-family="system-ui" font-weight="900" font-size="10" fill="#1c1c28" text-anchor="middle">WV</text></svg>`,
  'Hugging Face': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#ffbb00" opacity="0.15"/><text x="12" y="17.5" font-size="16" text-anchor="middle">🤗</text></svg>`,
  'OpenAI API': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#10a37f" opacity="0.15"/><circle cx="12" cy="12" r="5" stroke="#10a37f" stroke-width="2"/></svg>`,
  'AWS Bedrock': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#ff9900" opacity="0.15"/><path d="M12 2l9 5v10l-9 5-9-5V7z" stroke="#ff9900" stroke-width="2" fill="none"/></svg>`,
  'GCP Vertex AI': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#4285f4" opacity="0.15"/><path d="M12 2L2 22h20z" fill="#4285f4"/></svg>`,
  'Vercel AI SDK': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#000000" opacity="0.15"/><path d="M12 2L1 22h22z" fill="#000000"/></svg>`
}

const getLogoSvg = (name) => {
  return svgLogos[name] || `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="#6B7280"><rect width="24" height="24" rx="4"/><text x="12" y="15" font-family="system-ui" font-weight="900" font-size="8" fill="#fff" text-anchor="middle">${name.slice(0,3)}</text></svg>`
}

// ── Interactive Timeline / Process ───────────────────────────
const processSteps = [
  { num: '1. Discover & Audit', title: 'Data Audit & Planning', desc: 'Identify data sources, evaluate latency/accuracy targets, and layout structural workflows.' },
  { num: '2. Embedding & KB', title: 'Knowledge Base Setup', desc: 'Convert documentation into vector embeddings and load them into a dedicated secure database.' },
  { num: '3. Prompt & Scaffolding', title: 'Agent Engineering', desc: 'Build private system instructions, design agent memory loops, and engineer optimal prompt pipelines.' },
  { num: '4. Safety & Guardrails', title: 'Security Auditing', desc: 'Set semantic filtering guardrails to block hallucinations and prevent payload/prompt injections.' },
  { num: '5. Deploy & Optimize', title: 'Launch & Integration', desc: 'Integrate the UI widgets or API endpoints, and establish continuous log monitoring dashboards.' }
]

// ── Animated Stats & IntersectionObserver ─────────────────────
const animatedStats = ref({
  accuracy: 0,
  tokens: 0, // In Millions (e.g. 15 for 15M)
  response: 0,
  retention: 0
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

const observers = []

onMounted(() => {
  // Scroll reveal animation
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

  // Why Choose Us Stats Count-Up
  const statsEl = document.querySelector('.ai-why-us__stats-container')
  if (statsEl) {
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          statsInView.value = true
          if (!statsObserved.value) {
            statsObserved.value = true
            animateValue(animatedStats.value, 'accuracy', 0, 99.4, 2000, 1)
            animateValue(animatedStats.value, 'tokens', 0, 15, 2000, 0)
            animateValue(animatedStats.value, 'response', 0, 85, 2000, 0)
            animateValue(animatedStats.value, 'retention', 0, 96, 2000, 0)
          }
        }
      })
    }, { threshold: 0.1 })
    obs.observe(statsEl)
    observers.push(obs)
  }
})

onUnmounted(() => {
  observers.forEach(obs => obs.disconnect())
})
</script>

<template>
  <Head>
    <title>{{ service.seo?.meta_title || 'Enterprise AI Solutions & Chat Agents' }} — RankQuill</title>
    <meta name="description" :content="service.seo?.meta_description || 'Automate customer support queries and system operations using secure large language model workflows.'" />
    <meta name="robots" content="index, follow" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  </Head>

  <PublicLayout>
    <div class="ai-page select-none selection:bg-purple-600 selection:text-white">

      <!-- ═══════════════════════════════════════════════════
           HERO SECTION
      ═══════════════════════════════════════════════════ -->
      <section class="ai-hero">
        <div class="ai-hero__glow-1"></div>
        <div class="ai-hero__glow-2"></div>
        
        <div class="ai-container relative z-10">
          <!-- Breadcrumbs -->
          <nav class="ai-breadcrumb mb-6 lg:mb-8" aria-label="Breadcrumb">
            <Link href="/" class="ai-breadcrumb__link">Home</Link>
            <span class="ai-breadcrumb__sep">
              <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
            <Link href="/services" class="ai-breadcrumb__link">Services</Link>
            <span class="ai-breadcrumb__sep">
              <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
            <span class="ai-breadcrumb__active">AI Solutions</span>
          </nav>

          <div class="ai-hero__grid">
            <!-- Hero Left info -->
            <div class="ai-hero__left flex flex-col items-start text-left">
              <div class="ai-pill mb-5">
                <svg class="w-4 h-4 text-purple-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                AI Solutions
              </div>

              <h1 class="ai-hero__heading mb-5">
                Intelligent Agents<br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600">Built for Enterprise</span>
              </h1>

              <p class="ai-hero__para mb-6">
                Automate front-line workflows, integrate custom memory retrieval loops, and build private Large Language Model agents optimized for your internal database operations.
              </p>

              <!-- Hero Feature Row -->
              <div class="ai-features-row mb-8">
                <div v-for="feat in service.features || ['Custom LLM integrations', 'Agentic support widgets', 'Semantic database search', 'Python workflow scripts']" :key="feat" class="ai-feature-item">
                  <span class="ai-feature-item__icon-bg">
                    <svg class="w-3.5 h-3.5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                  </span>
                  <span class="ai-feature-item__label">{{ feat }}</span>
                </div>
              </div>

              <!-- Hero CTA Buttons -->
              <div class="ai-hero__btns">
                <Link href="/contact-us" class="ai-btn ai-btn--primary">
                  <span>Get a Free Quote</span>
                  <span class="ai-btn__circle">
                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                  </span>
                </Link>
                <Link href="/portfolio" class="ai-btn ai-btn--outline">
                  <span>View Our Work</span>
                  <span class="ai-btn__circle-play">
                    <svg class="w-3.5 h-3.5 text-purple-500 fill-purple-500" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </span>
                </Link>
              </div>
            </div>

            <!-- Hero Mockup Dashboard Right -->
            <div class="ai-hero__right">
              <div class="ai-mockup-wrap">
                <div class="ai-dashboard">
                  <div class="ai-dashboard__header">
                    <div class="ai-browser-dots">
                      <span class="ai-browser-dot ai-browser-dot--red"></span>
                      <span class="ai-browser-dot ai-browser-dot--yellow"></span>
                      <span class="ai-browser-dot ai-browser-dot--green"></span>
                    </div>
                    <div class="ai-browser-address">agent-console.rankquill.ai</div>
                  </div>

                  <div class="ai-dashboard__body">
                    <!-- Dashboard Sidebar -->
                    <div class="ai-db-sidebar">
                      <div class="ai-sidebar-item ai-sidebar-item--active">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                        Overview
                      </div>
                      <div class="ai-sidebar-item">Memory</div>
                      <div class="ai-sidebar-item">Knowledge</div>
                      <div class="ai-sidebar-item">Analytics</div>
                    </div>

                    <!-- Dashboard Main -->
                    <div class="ai-db-main">
                      <!-- Chat Widget Mockup -->
                      <div class="ai-chat-card">
                        <div class="ai-chat-header">
                          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                          <span>support_agent_v3.2</span>
                        </div>
                        <div class="ai-chat-bubble ai-chat-bubble--bot">
                          <strong>Agent:</strong> Connected successfully. Vector search query active. How can I assist you today?
                        </div>
                        <div class="ai-chat-bubble ai-chat-bubble--user">
                          <strong>User:</strong> How do I connect the API token?
                        </div>
                        <div class="ai-chat-bubble ai-chat-bubble--bot">
                          <strong>Agent:</strong> You can load the token in your <code>.env</code> file under <code>API_SECRET_KEY</code>.
                        </div>
                        <div class="ai-chat-input">
                          <span>Enter message...</span>
                        </div>
                      </div>

                      <!-- Interactive Stats floating inside Dashboard -->
                      <div class="ai-db-stats">
                        <div class="ai-stat-mini">
                          <span class="ai-stat-mini__lbl">Accuracy</span>
                          <span class="ai-stat-mini__val text-emerald-500">99.4%</span>
                        </div>
                        <div class="ai-stat-mini">
                          <span class="ai-stat-mini__lbl">Latency</span>
                          <span class="ai-stat-mini__val text-indigo-400">124ms</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Floating Neon Badges -->
                <div class="ai-float-badge ai-float-badge--llm">
                  <span class="w-3 h-3 rounded-full bg-purple-500 shadow-[0_0_10px_#8b5cf6]"></span>
                  <span>LLM Connected</span>
                </div>

                <div class="ai-float-badge ai-float-badge--vectors">
                  <span class="w-3 h-3 rounded-full bg-blue-500 shadow-[0_0_10px_#3b82f6]"></span>
                  <span>3.5M Vectors Indexed</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           OUR PROCESS SECTION
      ═══════════════════════════════════════════════════ -->
      <section class="ai-section bg-white">
        <div class="ai-container">
          <div data-rev class="ai-section-header mb-12">
            <h2 class="ai-section-title">Our AI Integration Process</h2>
            <p class="ai-section-sub">A structured, secure, and production-tested timeline to deploy Large Language Models into your company workflow.</p>
          </div>
          
          <div class="ai-process-grid">
            <div v-for="(step, i) in processSteps" :key="step.title" class="ai-process-item">
              <div data-rev :data-delay="(i * 0.08).toFixed(2)" class="ai-process-card">
                <div class="ai-process-card__icon-wrap">
                  <!-- Custom SVG Icons based on index -->
                  <svg v-if="i === 0" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                  </svg>
                  <svg v-else-if="i === 1" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                  </svg>
                  <svg v-else-if="i === 2" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                  </svg>
                  <svg v-else-if="i === 3" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                  <svg v-else-if="i === 4" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                </div>
                <h3 class="ai-process-card__title">{{ step.num }}</h3>
                <h4 class="ai-process-card__subtitle text-purple-500 font-bold mb-2">{{ step.title }}</h4>
                <p class="ai-process-card__desc">{{ step.desc }}</p>
              </div>
              
              <div v-if="i < processSteps.length - 1" class="ai-process-arrow">
                <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           TECHNOLOGIES & TOOLS SECTION
      ═══════════════════════════════════════════════════ -->
      <section class="ai-section bg-slate-50 border-t border-b border-slate-100">
        <div class="ai-container">
          <div data-rev class="ai-section-header mb-10">
            <h2 class="ai-section-title">Models, Orchestration &amp; Frameworks We Use</h2>
            <p class="ai-section-sub">We deploy the most robust systems in modern machine intelligence to create scalable enterprise tools.</p>
          </div>

          <!-- Tabs Pill Navigation Menu -->
          <div data-rev class="ai-tabs-wrapper mb-10">
            <button v-for="tab in tabs" :key="tab"
              @click="activeTab = tab"
              class="ai-tab-btn"
              :class="activeTab === tab ? 'ai-tab-btn--active' : ''">
              {{ tab }}
            </button>
          </div>

          <!-- Tech Cards Grid -->
          <div class="ai-tech-grid">
            <div v-for="(t, i) in techData[activeTab]" :key="t.name"
              data-rev :data-delay="(i * 0.03).toFixed(2)"
              class="ai-tech-card">
              <div class="ai-tech-card__logo" v-html="getLogoSvg(t.name)"></div>
              <div class="ai-tech-card__name">{{ t.name }}</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           SECTION 5 – WHY CHOOSE US
      ═══════════════════════════════════════════════════ -->
      <section class="ai-section bg-white ai-why-us__stats-container">
        <div class="ai-container">
          <div class="ai-why-us__grid">
            
            <!-- Left Side: Core Values -->
            <div class="ai-why-us__content" data-rev>
              <h2 class="ai-why-us__title">Why Choose Our AI Solutions?</h2>
              
              <div class="ai-why-us__features">
                <div class="ai-why-us__feature-item">
                  <div class="ai-why-us__feature-icon">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <div>
                    <h3 class="ai-why-us__feature-title">Data Privacy &amp; Local Hosting</h3>
                    <p class="ai-why-us__feature-desc">We build closed-loop data retrieval workflows ensuring your proprietary trade data is never shared with public model trainers.</p>
                  </div>
                </div>

                <div class="ai-why-us__feature-item">
                  <div class="ai-why-us__feature-icon">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <div>
                    <h3 class="ai-why-us__feature-title">Context-Aware Memory Loops</h3>
                    <p class="ai-why-us__feature-desc">Our agent structures save conversational threads contextually, allowing deep historical lookups on user queries.</p>
                  </div>
                </div>

                <div class="ai-why-us__feature-item">
                  <div class="ai-why-us__feature-icon">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <div>
                    <h3 class="ai-why-us__feature-title">Hybrid Agent/Human Escrow</h3>
                    <p class="ai-why-us__feature-desc">Create seamless hand-offs. The AI handles basic actions, while automatically mapping complex transactions to live human teams.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Side: Stats Panel -->
            <div class="ai-why-us__visual" data-rev data-delay="0.2">
              <div class="ai-stats-card">
                <div class="ai-stats-card__label">Performance Metrics</div>
                
                <div class="ai-stats-card__grid">
                  <div class="ai-stats-card__item">
                    <div class="ai-stats-card__number">{{ animatedStats.accuracy }}%</div>
                    <div class="ai-stats-card__caption">Query Resolve Accuracy</div>
                  </div>
                  <div class="ai-stats-card__item">
                    <div class="ai-stats-card__number">{{ animatedStats.tokens }}M+</div>
                    <div class="ai-stats-card__number-sub">Tokens / month</div>
                    <div class="ai-stats-card__caption">API Logs Volume</div>
                  </div>
                  <div class="ai-stats-card__item">
                    <div class="ai-stats-card__number">{{ animatedStats.response }}%</div>
                    <div class="ai-stats-card__caption">Avg. Cost Reduction</div>
                  </div>
                  <div class="ai-stats-card__item">
                    <div class="ai-stats-card__number">{{ animatedStats.retention }}%</div>
                    <div class="ai-stats-card__caption">CSAT Rating Improvement</div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           DYNAMIC CMS CONTENT BLOCKS (SUPPORT)
      ═══════════════════════════════════════════════════ -->
      <div v-if="service.content?.length">
        <div v-for="(section, idx) in service.content" :key="idx">

          <!-- FAQs Block -->
          <section v-if="section.type === 'faqs'" class="ai-section bg-slate-50 border-t border-b border-slate-100">
            <div class="ai-container">
              <div class="ai-section-header mb-12">
                <span class="ai-section-label">FAQ</span>
                <h2 class="ai-section-title">{{ section.data.title || 'Common Questions' }}</h2>
              </div>
              <div class="max-w-3xl mx-auto space-y-4">
                <details v-for="faq in section.data.resolved_items" :key="faq.id"
                  class="group bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-purple-500/30 transition-colors">
                  <summary class="flex items-center justify-between gap-4 p-6 cursor-pointer list-none">
                    <h3 class="text-base font-semibold text-slate-900">{{ faq.question }}</h3>
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                  </summary>
                  <div class="px-6 pb-6"><p class="text-sm text-slate-500 leading-relaxed">{{ faq.answer }}</p></div>
                </details>
              </div>
            </div>
          </section>

          <!-- Testimonials Block -->
          <section v-else-if="section.type === 'testimonials'" class="ai-section bg-white">
            <div class="ai-container">
              <div class="ai-section-header mb-12"><h2 class="ai-section-title">{{ section.data.title || 'Client Feedback' }}</h2></div>
              <div class="ai-testimonials-grid">
                <div v-for="t in section.data.resolved_items" :key="t.id"
                  class="bg-slate-50 border border-slate-200 rounded-3xl p-8 transition-all hover:scale-[1.02]">
                  <div class="flex gap-1 mb-4">
                    <svg v-for="s in 5" :key="s" class="w-4 h-4" :class="s <= (t.rating||5) ? 'text-amber-400 fill-current' : 'text-slate-200'" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                  </div>
                  <p class="text-sm text-slate-600 italic leading-relaxed mb-5">"{{ t.testimonial }}"</p>
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs">{{ t.client_name?.charAt(0) }}</div>
                    <div>
                      <div class="text-xs font-bold text-slate-900">{{ t.client_name }}</div>
                      <div class="text-[10px] text-slate-400">{{ t.client_position }}, {{ t.client_company }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════
           INVESTMENT / PRICING PLAN SECTION
      ═══════════════════════════════════════════════════ -->
      <section v-if="service.pricing?.length" class="ai-section bg-slate-50 border-t border-slate-100">
        <div class="ai-container">
          <div class="ai-section-header mb-12">
            <span class="ai-section-label">Investment</span>
            <h2 class="ai-section-title">Flexible <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">Pricing Packages</span></h2>
            <p class="ai-section-sub">Deploy safe LLM logic configured specifically for your support channels.</p>
          </div>
          
          <div class="ai-pricing-grid">
            <div v-for="(plan, idx) in service.pricing" :key="idx"
              class="relative flex flex-col rounded-3xl p-8 border transition-all duration-300 bg-white"
              :class="idx === 0
                ? 'border-purple-500 shadow-xl shadow-purple-500/5 scale-[1.02]'
                : 'border-slate-200'">
              
              <div class="mb-6">
                <h3 class="text-base font-bold text-slate-500 uppercase tracking-widest mb-3">{{ plan.name }}</h3>
                <div class="flex items-baseline gap-1">
                  <span class="text-5xl font-black text-slate-900">${{ parseFloat(plan.price).toLocaleString() }}</span>
                  <span class="text-slate-400 text-sm">/ {{ plan.interval || 'one-time' }}</span>
                </div>
                <p v-if="plan.description" class="text-sm text-slate-500 leading-relaxed mt-3">{{ plan.description }}</p>
              </div>

              <ul v-if="plan.features?.length" class="space-y-3 mb-8 flex-1">
                <li v-for="(feat, fIdx) in plan.features" :key="fIdx" class="flex items-start gap-3 text-sm text-slate-600">
                  <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 bg-purple-500/10 text-purple-500">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  {{ feat }}
                </li>
              </ul>

              <Link href="/contact-us" class="block w-full text-center py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 bg-purple-600 hover:bg-purple-500 text-white shadow-lg shadow-purple-600/10">
                Get Started →
              </Link>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           FALLBACK FAQS (IF CMS NOT LOADED)
      ═══════════════════════════════════════════════════ -->
      <section v-if="(!service.content || service.content.every(s => s.type !== 'faqs')) && service.faqs?.length" class="ai-section bg-slate-50 border-t border-slate-100">
        <div class="ai-container">
          <div class="ai-section-header mb-12">
            <span class="ai-section-label">FAQ</span>
            <h2 class="ai-section-title">Frequently Asked Questions</h2>
          </div>
          <div class="max-w-3xl mx-auto space-y-4">
            <details v-for="faq in service.faqs" :key="faq.id"
              class="group bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-purple-500/30 transition-colors">
              <summary class="flex items-center justify-between gap-4 p-6 cursor-pointer list-none">
                <h3 class="text-base font-semibold text-slate-900">{{ faq.question }}</h3>
                <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="px-6 pb-6"><p class="text-sm text-slate-500 leading-relaxed">{{ faq.answer }}</p></div>
            </details>
          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════
           CTA BANNER SECTION
      ═══════════════════════════════════════════════════ -->
      <section class="ai-section bg-slate-50 ai-section-cta">
        <div class="ai-container">
          <div data-rev class="ai-cta-banner">
            <div class="ai-cta-banner__left">
              <h2 class="ai-cta-banner__title">Ready to Automate Your Business?</h2>
              <p class="ai-cta-banner__sub">Let's build a dedicated, secure AI workflow engine tailored for your client needs.</p>
            </div>
            <div class="ai-cta-banner__right">
              <Link href="/contact-us" class="ai-cta-btn">
                <span>Talk to an Architect</span>
                <span class="ai-cta-btn__circle">
                  <svg class="w-3.5 h-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                  </svg>
                </span>
              </Link>
            </div>
          </div>
        </div>
      </section>

    </div>
  </PublicLayout>
</template>

<style scoped>
/* ── Global Styles & Design System ───────────────────────── */
.ai-page {
  font-family: 'Inter', system-ui, sans-serif;
  color: #1e293b;
  overflow-x: hidden;
  background-color: #ffffff;
}

.ai-container {
  width: 100%;
  max-width: 1320px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 20px;
  padding-right: 20px;
}

.ai-section {
  padding-top: 56px;
  padding-bottom: 56px;
}
@media (min-width: 768px) {
  .ai-section {
    padding-top: 88px;
    padding-bottom: 88px;
  }
}

.ai-section-header {
  text-align: center;
}
.ai-section-title {
  font-size: 28px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.2;
  letter-spacing: -0.025em;
}
@media (min-width: 768px) {
  .ai-section-title {
    font-size: 38px;
  }
}
.ai-section-sub {
  font-size: 15px;
  color: #64748b;
  margin-top: 12px;
  max-width: 580px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
}

/* ═══════════════════════════════════════════════════════════
   HERO SECTION
   ═══════════════════════════════════════════════════════════ */
.ai-hero {
  background: linear-gradient(180deg, #faf5ff 0%, #ffffff 100%);
  position: relative;
  overflow: hidden;
  padding-top: 100px;
  padding-bottom: 56px;
}
@media (min-width: 1024px) {
  .ai-hero {
    padding-top: 140px;
    padding-bottom: 96px;
  }
}

.ai-hero__glow-1 {
  position: absolute;
  top: -150px;
  right: -100px;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(168,85,247,0.07) 0%, rgba(255,255,255,0) 70%);
  pointer-events: none;
}
.ai-hero__glow-2 {
  position: absolute;
  bottom: -100px;
  left: -150px;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99,102,241,0.04) 0%, rgba(255,255,255,0) 70%);
  pointer-events: none;
}

/* Breadcrumb */
.ai-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13.5px;
  color: #64748b;
  flex-wrap: wrap;
}
.ai-breadcrumb__link {
  color: #64748b;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}
.ai-breadcrumb__link:hover {
  color: #a855f7;
}
.ai-breadcrumb__sep {
  color: #cbd5e1;
}
.ai-breadcrumb__active {
  color: #0f172a;
  font-weight: 600;
}

/* Hero Pill Badge */
.ai-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: rgba(168, 85, 247, 0.07);
  border: 1px solid rgba(168, 85, 247, 0.15);
  color: #a855f7;
  font-size: 13.5px;
  font-weight: 600;
  padding: 6px 16px;
  border-radius: 100px;
}

/* Typography scale */
.ai-hero__heading {
  font-size: 34px;
  font-weight: 900;
  line-height: 1.15;
  color: #0f172a;
  letter-spacing: -0.03em;
}
@media (min-width: 768px) {
  .ai-hero__heading {
    font-size: 52px;
  }
}
@media (min-width: 1280px) {
  .ai-hero__heading {
    font-size: 60px;
  }
}

.ai-hero__para {
  font-size: 15px;
  line-height: 1.7;
  color: #64748b;
  max-width: 520px;
}
@media (min-width: 768px) {
  .ai-hero__para {
    font-size: 16px;
  }
}

/* Features List */
.ai-features-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  width: 100%;
}
@media (max-width: 480px) {
  .ai-features-row {
    grid-template-columns: 1fr;
  }
}
.ai-feature-item {
  display: flex;
  align-items: center;
  gap: 10px;
}
.ai-feature-item__icon-bg {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background-color: rgba(168, 85, 247, 0.07);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.ai-feature-item__label {
  font-size: 14.5px;
  font-weight: 600;
  color: #334155;
}

/* Hero CTA Buttons */
.ai-hero__btns {
  display: flex;
  flex-wrap: wrap;
  gap: 14px;
  width: 100%;
}
.ai-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  font-size: 14.5px;
  font-weight: 700;
  padding: 13px 24px;
  border-radius: 14px;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  text-decoration: none;
}
.ai-btn--primary {
  background-color: #a855f7;
  color: #ffffff;
  box-shadow: 0 10px 30px rgba(168, 85, 247, 0.2);
}
.ai-btn--primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(168, 85, 247, 0.35);
}
.ai-btn--outline {
  background-color: #ffffff;
  color: #a855f7;
  border: 1.5px solid rgba(168, 85, 247, 0.15);
}
.ai-btn--outline:hover {
  background-color: rgba(168, 85, 247, 0.03);
  border-color: #a855f7;
  transform: translateY(-2px);
}
.ai-btn__circle {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
}
.ai-btn__circle-play {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(168, 85, 247, 0.07);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Hero Grid */
.ai-hero__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 48px;
  align-items: center;
}
@media (min-width: 1024px) {
  .ai-hero__grid {
    grid-template-columns: 48% 52%;
  }
}

.ai-hero__right {
  display: flex;
  justify-content: center;
  width: 100%;
}
.ai-mockup-wrap {
  position: relative;
  width: 100%;
  max-width: 520px;
  aspect-ratio: 4/3;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Interactive Dashboard styling */
.ai-dashboard {
  width: 100%;
  background-color: #0d0f17;
  border-radius: 18px;
  border: 1.5px solid rgba(168, 85, 247, 0.15);
  box-shadow: 0 30px 60px rgba(0,0,0,0.25), 0 0 40px rgba(168, 85, 247, 0.1);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  animation: floatLaptop 6s ease-in-out infinite;
}

.ai-dashboard__header {
  background-color: #161824;
  height: 32px;
  display: flex;
  align-items: center;
  padding: 0 16px;
  position: relative;
  border-bottom: 1.5px solid rgba(168, 85, 247, 0.1);
}
.ai-browser-dots {
  display: flex;
  gap: 4px;
  position: absolute;
  left: 16px;
}
.ai-browser-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
}
.ai-browser-dot--red { background-color: #ef4444; }
.ai-browser-dot--yellow { background-color: #f59e0b; }
.ai-browser-dot--green { background-color: #10b981; }

.ai-browser-address {
  font-size: 10.5px;
  color: rgba(255,255,255,0.4);
  background-color: rgba(255,255,255,0.03);
  border-radius: 4px;
  padding: 2px 20px;
  margin: 0 auto;
  min-width: 160px;
  text-align: center;
  font-weight: 500;
  border: 1px solid rgba(255,255,255,0.05);
}

.ai-dashboard__body {
  display: flex;
  flex: 1;
  min-height: 250px;
}

.ai-db-sidebar {
  width: 90px;
  background-color: #0b0c13;
  padding: 16px 8px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  border-right: 1.5px solid rgba(168, 85, 247, 0.1);
}
.ai-sidebar-item {
  font-size: 10.5px;
  color: rgba(255,255,255,0.5);
  font-weight: 600;
  padding: 6px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
}
.ai-sidebar-item--active {
  background-color: rgba(168, 85, 247, 0.1);
  color: #fff;
}

.ai-db-main {
  flex: 1;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

/* Chat Card Mockup */
.ai-chat-card {
  background-color: #131522;
  border-radius: 12px;
  border: 1px solid rgba(255,255,255,0.05);
  padding: 12px;
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.ai-chat-header {
  font-size: 10px;
  color: rgba(255,255,255,0.4);
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 6px;
  border-bottom: 1px solid rgba(255,255,255,0.05);
  padding-bottom: 6px;
}
.ai-chat-bubble {
  font-size: 10px;
  border-radius: 8px;
  padding: 6px 10px;
  max-width: 90%;
  line-height: 1.4;
}
.ai-chat-bubble--bot {
  background-color: rgba(168, 85, 247, 0.1);
  color: rgba(255,255,255,0.9);
  align-self: flex-start;
  border-left: 2px solid #a855f7;
}
.ai-chat-bubble--user {
  background-color: rgba(255,255,255,0.05);
  color: rgba(255,255,255,0.8);
  align-self: flex-end;
}
.ai-chat-input {
  background-color: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 6px;
  padding: 6px 10px;
  font-size: 9px;
  color: rgba(255,255,255,0.3);
  margin-top: auto;
}

.ai-db-stats {
  display: flex;
  gap: 12px;
}
.ai-stat-mini {
  flex: 1;
  background-color: #131522;
  border: 1px solid rgba(255,255,255,0.05);
  border-radius: 10px;
  padding: 8px 12px;
  display: flex;
  flex-direction: column;
}
.ai-stat-mini__lbl {
  font-size: 9px;
  color: rgba(255,255,255,0.4);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.ai-stat-mini__val {
  font-size: 13.5px;
  font-weight: 800;
  margin-top: 2px;
}

/* Floating neon badges */
.ai-float-badge {
  position: absolute;
  background-color: rgba(13, 15, 23, 0.85);
  border: 1px solid rgba(255,255,255,0.1);
  backdrop-filter: blur(10px);
  border-radius: 12px;
  padding: 10px 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 11.5px;
  font-weight: 700;
  color: #fff;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.ai-float-badge--llm {
  top: 10%;
  left: -5%;
  animation: floatLaptop 6s ease-in-out infinite alternate;
}
.ai-float-badge--vectors {
  bottom: 8%;
  right: -5%;
  animation: floatLaptop 7s ease-in-out infinite alternate-reverse;
}

@keyframes floatLaptop {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-8px); }
  100% { transform: translateY(0px); }
}

/* ═══════════════════════════════════════════════════
   OUR PROCESS SECTION
   ═══════════════════════════════════════════════════ */
.ai-process-grid {
  display: flex;
  flex-direction: column;
  gap: 24px;
  width: 100%;
}
@media (min-width: 1024px) {
  .ai-process-grid {
    flex-direction: row;
    align-items: stretch;
    justify-content: space-between;
  }
}

.ai-process-item {
  flex: 1;
  display: flex;
  flex-direction: column;
  position: relative;
}
@media (min-width: 1024px) {
  .ai-process-item {
    flex-direction: row;
    align-items: center;
  }
}

.ai-process-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.02);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  width: 100%;
}
.ai-process-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(168, 85, 247, 0.1);
  border-color: rgba(168, 85, 247, 0.2);
}
.ai-process-card__icon-wrap {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background-color: rgba(168, 85, 247, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
}
.ai-process-card__title {
  font-size: 13.5px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 2px;
}
.ai-process-card__subtitle {
  font-size: 16.5px;
  font-weight: 800;
  color: #0f172a;
}
.ai-process-card__desc {
  font-size: 13px;
  color: #64748b;
  line-height: 1.6;
}

.ai-process-arrow {
  display: none;
}
@media (min-width: 1024px) {
  .ai-process-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 100%;
    margin: 0 8px;
  }
}

/* ═══════════════════════════════════════════════════
   TECHNOLOGIES SECTION
   ═══════════════════════════════════════════════════ */
.ai-tabs-wrapper {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  justify-content: center;
  background-color: rgba(15, 23, 42, 0.03);
  padding: 6px;
  border-radius: 100px;
  max-width: fit-content;
  margin-left: auto;
  margin-right: auto;
  border: 1px solid rgba(15, 23, 42, 0.05);
}
.ai-tab-btn {
  font-size: 13.5px;
  font-weight: 700;
  color: #64748b;
  padding: 8px 18px;
  border-radius: 100px;
  background: none;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}
.ai-tab-btn--active {
  background-color: #ffffff;
  color: #a855f7;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.ai-tech-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}
@media (min-width: 640px) {
  .ai-tech-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 1024px) {
  .ai-tech-grid {
    grid-template-columns: repeat(6, 1fr);
  }
}

.ai-tech-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 16px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.01);
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.ai-tech-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 30px rgba(168, 85, 247, 0.08);
  border-color: rgba(168, 85, 247, 0.15);
}
.ai-tech-card__logo {
  display: flex;
  align-items: center;
  justify-content: center;
}
.ai-tech-card__name {
  font-size: 13.5px;
  font-weight: 700;
  color: #334155;
  text-align: center;
}

/* ═══════════════════════════════════════════════════
   SECTION 5 – WHY CHOOSE US
   ═══════════════════════════════════════════════════ */
.ai-why-us__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 56px;
  align-items: center;
}
@media (min-width: 1024px) {
  .ai-why-us__grid {
    grid-template-columns: 45% 55%;
  }
}

.ai-why-us__content {
  display: flex;
  flex-direction: column;
}
.ai-why-us__title {
  font-size: 32px;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 32px;
  letter-spacing: -0.02em;
}
@media (min-width: 768px) {
  .ai-why-us__title {
    font-size: 42px;
  }
}

.ai-why-us__features {
  display: flex;
  flex-direction: column;
  gap: 28px;
}
.ai-why-us__feature-item {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}
.ai-why-us__feature-icon {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background-color: rgba(168, 85, 247, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 2px;
}
.ai-why-us__feature-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 6px;
}
.ai-why-us__feature-desc {
  font-size: 15px;
  color: #64748b;
  line-height: 1.6;
}

.ai-why-us__visual {
  width: 100%;
}

.ai-stats-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 24px;
  padding: 32px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
}

.ai-stats-card__label {
  font-size: 14px;
  font-weight: 700;
  color: #a855f7;
  margin-bottom: 24px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.ai-stats-card__grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 32px;
}
@media (max-width: 480px) {
  .ai-stats-card__grid {
    grid-template-columns: 1fr;
    gap: 24px;
  }
}

.ai-stats-card__item {
  display: flex;
  flex-direction: column;
}
.ai-stats-card__number {
  font-size: 36px;
  font-weight: 900;
  color: #0f172a;
  line-height: 1;
}
.ai-stats-card__number-sub {
  font-size: 11px;
  font-weight: 700;
  color: #94a3b8;
  margin-top: 2px;
}
.ai-stats-card__caption {
  font-size: 13.5px;
  font-weight: 600;
  color: #64748b;
  margin-top: 6px;
  line-height: 1.4;
}

/* ═══════════════════════════════════════════════════
   PRICING / INVESTMENT
   ═══════════════════════════════════════════════════ */
.ai-pricing-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

/* Testimonials Grid */
.ai-testimonials-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}
@media (min-width: 768px) {
  .ai-testimonials-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* ═══════════════════════════════════════════════════
   CTA BANNER SECTION
   ═══════════════════════════════════════════════════ */
.ai-section-cta {
  padding-bottom: 60px;
}
.ai-cta-banner {
  background: linear-gradient(135deg, #a855f7 0%, #6366f1 50%, #4f46e5 100%);
  border-radius: 24px;
  padding: 40px;
  display: flex;
  flex-direction: column;
  gap: 24px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 20px 45px rgba(168, 85, 247, 0.2);
}
@media (min-width: 768px) {
  .ai-cta-banner {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 48px;
  }
}

.ai-cta-banner__left {
  position: relative;
  z-index: 2;
  flex: 1;
}
.ai-cta-banner__title {
  font-size: 24px;
  font-weight: 800;
  color: #ffffff;
  line-height: 1.25;
  margin-bottom: 8px;
}
@media (min-width: 768px) {
  .ai-cta-banner__title {
    font-size: 28px;
  }
}
.ai-cta-banner__sub {
  font-size: 14.5px;
  color: rgba(255, 255, 255, 0.85);
  line-height: 1.5;
}

.ai-cta-banner__right {
  position: relative;
  z-index: 2;
  flex-shrink: 0;
}
.ai-cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  background-color: #ffffff;
  color: #a855f7;
  font-size: 14.5px;
  font-weight: 700;
  padding: 13px 26px;
  border-radius: 14px;
  text-decoration: none;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.ai-cta-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.ai-cta-btn__circle {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(168, 85, 247, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
