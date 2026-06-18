<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({ service: { type: Object, required: true } })

// ── Interactive State ─────────────────────────────────────────
const activeTab = ref('Design & Illustration')
const tabs = [
  'Design & Illustration',
  'UI/UX & Prototyping',
  'Photo Editing',
  'Motion Graphics & Animation',
  '3D & Visual Design',
  'Collaboration & Others'
]

// ── Stats Count-Up ────────────────────────────────────────────
const animatedStats = ref({
  projects: 0,
  clients: 0,
  satisfaction: 0
})

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

// ── IntersectionObserver Scroll Reveal ──────────────────────────
const observers = []
onMounted(() => {
  // Reveal elements on scroll
  document.querySelectorAll('[data-rev]').forEach(el => {
    const delay = parseFloat(el.dataset.delay || 0)
    el.style.opacity = '0'
    el.style.transform = 'translateY(30px)'
    el.style.transition = `opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s, transform 0.7s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s`
    
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.opacity = '1'
          e.target.style.transform = 'translateY(0)'
          obs.unobserve(e.target)
        }
      })
    }, { threshold: 0.08 })
    obs.observe(el)
    observers.push(obs)
  })

  // Start Hero animations immediately
  animateValue(animatedStats.value, 'projects', 0, 1000, 2200)
  animateValue(animatedStats.value, 'clients', 0, 500, 2200)
  animateValue(animatedStats.value, 'satisfaction', 0, 98, 2200)
})

onUnmounted(() => {
  observers.forEach(obs => obs.disconnect())
})

// ── Software Tools & Categorization ───────────────────────────
const tools = [
  // Photo Editing
  { name: 'Adobe Photoshop', categories: ['Photo Editing', 'Design & Illustration'] },
  { name: 'Affinity Photo', categories: ['Photo Editing'] },
  { name: 'GIMP', categories: ['Photo Editing'] },
  { name: 'Lightroom', categories: ['Photo Editing'] },
  
  // Design & Illustration
  { name: 'Adobe Illustrator', categories: ['Design & Illustration'] },
  { name: 'Adobe InDesign', categories: ['Design & Illustration'] },
  { name: 'CorelDRAW', categories: ['Design & Illustration'] },
  { name: 'Affinity Designer', categories: ['Design & Illustration'] },
  { name: 'Sketch', categories: ['Design & Illustration', 'UI/UX & Prototyping'] },
  { name: 'Canva', categories: ['Design & Illustration', 'Collaboration & Others'] },
  { name: 'Procreate', categories: ['Design & Illustration'] },

  // UI/UX
  { name: 'Figma', categories: ['UI/UX & Prototyping', 'Design & Illustration'] },
  { name: 'Adobe XD', categories: ['UI/UX & Prototyping'] },
  { name: 'Framer', categories: ['UI/UX & Prototyping'] },
  { name: 'InVision', categories: ['UI/UX & Prototyping', 'Collaboration & Others'] },
  { name: 'Zeplin', categories: ['UI/UX & Prototyping', 'Collaboration & Others'] },
  { name: 'Marvel', categories: ['UI/UX & Prototyping'] },

  // Motion Graphics
  { name: 'After Effects', categories: ['Motion Graphics & Animation'] },
  { name: 'Premiere Pro', categories: ['Motion Graphics & Animation'] },
  { name: 'LottieFiles', categories: ['Motion Graphics & Animation', 'UI/UX & Prototyping'] },

  // 3D & Visual Design
  { name: 'Cinema 4D', categories: ['3D & Visual Design', 'Motion Graphics & Animation'] },
  { name: 'Blender', categories: ['3D & Visual Design'] },
  { name: '3ds Max', categories: ['3D & Visual Design'] },
  { name: 'Maya', categories: ['3D & Visual Design'] },
  { name: 'ZBrush', categories: ['3D & Visual Design'] },

  // Collaboration
  { name: 'Miro', categories: ['Collaboration & Others'] },
  { name: 'Notion', categories: ['Collaboration & Others'] },
  { name: 'Trello', categories: ['Collaboration & Others'] },
  { name: 'Slack', categories: ['Collaboration & Others'] },
  { name: 'Google Workspace', categories: ['Collaboration & Others'] }
]

const filteredTools = computed(() => {
  return tools.filter(t => t.categories.includes(activeTab.value))
})

const getToolLogo = (name) => {
  const logos = {
    'Adobe Photoshop': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00C8FF" opacity="0.15"/><rect x="6" y="6" width="12" height="12" rx="2" stroke="#00C8FF" stroke-width="1.5"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#00C8FF" text-anchor="middle">Ps</text></svg>`,
    'Adobe Illustrator': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF9A00" opacity="0.15"/><rect x="6" y="6" width="12" height="12" rx="2" stroke="#FF9A00" stroke-width="1.5"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#FF9A00" text-anchor="middle">Ai</text></svg>`,
    'Adobe InDesign': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF249B" opacity="0.15"/><rect x="6" y="6" width="12" height="12" rx="2" stroke="#FF249B" stroke-width="1.5"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#FF249B" text-anchor="middle">Id</text></svg>`,
    'Adobe XD': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF2BC2" opacity="0.15"/><rect x="6" y="6" width="12" height="12" rx="2" stroke="#FF2BC2" stroke-width="1.5"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#FF2BC2" text-anchor="middle">Xd</text></svg>`,
    'Figma': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#F24E1E" opacity="0.15"/><path d="M9 8.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm0 3a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm0-6a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm3 3a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm0-3a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" fill="#F24E1E"/></svg>`,
    'Sketch': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FDB300" opacity="0.15"/><path d="M12 7l4 4-4 6-4-6z" fill="#FDB300"/></svg>`,
    'CorelDRAW': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#39B54A" opacity="0.15"/><circle cx="12" cy="12" r="5" stroke="#39B54A" stroke-width="1.5"/></svg>`,
    'Affinity Designer': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00A8FF" opacity="0.15"/><path d="M12 7l4 7H8l4-7z" stroke="#00A8FF" stroke-width="1.5"/></svg>`,
    'Affinity Photo': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#E428FF" opacity="0.15"/><circle cx="12" cy="12" r="4" stroke="#E428FF" stroke-width="1.5"/></svg>`,
    'Canva': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00C4CC" opacity="0.15"/><text x="12" y="15" font-family="system-ui" font-weight="900" font-size="9" fill="#00C4CC" text-anchor="middle">C</text></svg>`,
    'Procreate': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#E43E82" opacity="0.15"/><path d="M8 12c1-3 5-3 6 0s-5 3-6 0z" stroke="#E43E82" stroke-width="1.5"/></svg>`,
    'GIMP': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#C09060" opacity="0.15"/><path d="M9 14h6m-3-4v8" stroke="#C09060" stroke-width="1.5"/></svg>`,
    'Lightroom': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#31A8FF" opacity="0.15"/><rect x="6" y="6" width="12" height="12" rx="2" stroke="#31A8FF" stroke-width="1.5"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#31A8FF" text-anchor="middle">Lr</text></svg>`,
    'After Effects': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#997FFF" opacity="0.15"/><rect x="6" y="6" width="12" height="12" rx="2" stroke="#997FFF" stroke-width="1.5"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#997FFF" text-anchor="middle">Ae</text></svg>`,
    'Premiere Pro': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#EA77FF" opacity="0.15"/><rect x="6" y="6" width="12" height="12" rx="2" stroke="#EA77FF" stroke-width="1.5"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#EA77FF" text-anchor="middle">Pr</text></svg>`,
    'Cinema 4D': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#007FFF" opacity="0.15"/><circle cx="12" cy="12" r="5" stroke="#007FFF" stroke-width="1.5"/></svg>`,
    'Blender': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#EA7600" opacity="0.15"/><ellipse cx="12" cy="12" rx="4" ry="2" stroke="#EA7600" stroke-width="1.5"/></svg>`,
    '3ds Max': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#06B6D4" opacity="0.15"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#06B6D4" text-anchor="middle">3d</text></svg>`,
    'Maya': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#35B5E5" opacity="0.15"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#35B5E5" text-anchor="middle">My</text></svg>`,
    'ZBrush': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#E5C158" opacity="0.15"/><path d="M7 16V8l10 8V8" stroke="#E5C158" stroke-width="1.5"/></svg>`,
    'LottieFiles': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#00F0D0" opacity="0.15"/><path d="M8 8l8 8M16 8l-8 8" stroke="#00F0D0" stroke-width="1.5"/></svg>`,
    'InVision': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FF3366" opacity="0.15"/><text x="12" y="14.5" font-family="system-ui" font-weight="900" font-size="7.5" fill="#FF3366" text-anchor="middle">in</text></svg>`,
    'Framer': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FFFFFF" opacity="0.15"/><path d="M8 7h8l-8 5h8l-8 5" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
    'Zeplin': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#EE6723" opacity="0.15"/><path d="M12 8c-2 0-3 1.5-3 3s3 5 3 5 3-3.5 3-5-1-3-3-3z" stroke="#EE6723" stroke-width="1.5"/></svg>`,
    'Marvel': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#0066FF" opacity="0.15"/><path d="M7 16V8l5 6 5-6v8" stroke="#0066FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
    'Miro': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#050038" opacity="0.15"/><circle cx="12" cy="12" r="4" stroke="#050038" stroke-width="1.5"/></svg>`,
    'Notion': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#FFFFFF" opacity="0.15"/><text x="12" y="16" font-family="system-ui" font-weight="900" font-size="9.5" fill="#FFFFFF" text-anchor="middle">N</text></svg>`,
    'Trello': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#0079BF" opacity="0.15"/><rect x="7" y="7" width="10" height="10" rx="1.5" stroke="#0079BF" stroke-width="1.5"/></svg>`,
    'Slack': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#4A154B" opacity="0.15"/><path d="M9 12h6M12 9v6" stroke="#4A154B" stroke-width="1.5"/></svg>`,
    'Google Workspace': `<svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#4285F4" opacity="0.15"/><path d="M12 7l4.5 4.5L12 16l-4.5-4.5L12 7z" fill="#4285F4"/></svg>`
  }
  return logos[name] || logos['Adobe Photoshop']
}
</script>

<template>
  <Head :title="service.title + ' - Digitally Yours'" />
  <PublicLayout>
    <div class="gd-page">

      <!-- ═══════════════════════════════════════════════════════════
           SECTION 1 – HERO
           ═══════════════════════════════════════════════════════════ -->
      <section class="gd-hero">
        <div class="gd-hero__bg-dots"></div>
        <div class="gd-hero__glow-1"></div>
        <div class="gd-hero__glow-2"></div>

        <div class="gd-container">
          <div class="gd-hero__wrapper">
            
            <!-- Left Column: Content (45%) -->
            <div class="gd-hero__content">
              <!-- Breadcrumb -->
              <div class="gd-breadcrumb">
                <Link href="/" class="gd-breadcrumb__link">Home</Link>
                <span class="gd-breadcrumb__sep">&gt;</span>
                <Link href="/services" class="gd-breadcrumb__link">Services</Link>
                <span class="gd-breadcrumb__sep">&gt;</span>
                <span class="gd-breadcrumb__active">Graphic Designing</span>
              </div>

              <!-- Service Badge -->
              <div class="gd-hero__badge-wrap">
                <span class="gd-badge">
                  <!-- Artist palette SVG -->
                  <svg class="w-3.5 h-3.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 14.7255 3.09032 17.1962 4.85857 19C5.35857 19.5 6 20 6.5 20C7.5 20 8 19 8.5 18C9 17 9.5 15.5 11 15.5H13C14 15.5 15 16.5 15 17.5C15 18.5 14.5 20 13.5 21C13 21.5 12.5 22 12 22Z" />
                    <circle cx="7.5" cy="10.5" r="1.2" fill="currentColor"/>
                    <circle cx="11.5" cy="7.5" r="1.2" fill="currentColor"/>
                    <circle cx="16.5" cy="9.5" r="1.2" fill="currentColor"/>
                    <circle cx="15.5" cy="14.5" r="1.2" fill="currentColor"/>
                  </svg>
                  Graphic Designing
                </span>
              </div>

              <!-- Hero Headline -->
              <h1 class="gd-hero__title">
                Designs That <span class="gd-hero__title--highlight text-indigo-600">Connect.</span><br>
                Creativity That <span class="gd-hero__title--highlight text-indigo-600">Converts.</span>
              </h1>

              <!-- Hero Description -->
              <p class="gd-hero__desc">
                We create stunning, impactful, and brand-driven designs that capture attention, tell your story, and drive real results.
              </p>

              <!-- Benefits Row -->
              <div class="gd-benefits">
                <div class="gd-benefit-item">
                  <span class="gd-benefit-item__icon-wrap">
                    <!-- Creative Grid SVG -->
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                  </span>
                  <span class="gd-benefit-item__label">Creative & Unique</span>
                </div>
                <div class="gd-benefit-item">
                  <span class="gd-benefit-item__icon-wrap">
                    <!-- Brand Focus / Target SVG -->
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <circle cx="12" cy="12" r="9"/>
                      <circle cx="12" cy="12" r="5"/>
                      <circle cx="12" cy="12" r="1"/>
                    </svg>
                  </span>
                  <span class="gd-benefit-item__label">Brand Focused</span>
                </div>
                <div class="gd-benefit-item">
                  <span class="gd-benefit-item__icon-wrap">
                    <!-- Star Badge SVG -->
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 22a10 10 0 100-20 10 10 0 000 20z" />
                    </svg>
                  </span>
                  <span class="gd-benefit-item__label">High Quality</span>
                </div>
                <div class="gd-benefit-item">
                  <span class="gd-benefit-item__icon-wrap">
                    <!-- Clock SVG -->
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </span>
                  <span class="gd-benefit-item__label">On-Time Delivery</span>
                </div>
              </div>

              <!-- CTA Buttons -->
              <div class="gd-hero__btns">
                <Link href="/contact-us" class="gd-btn gd-btn--primary">
                  Get a Free Design Quote
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                  </svg>
                </Link>
                <a href="#portfolio" class="gd-btn gd-btn--outline">
                  View Our Portfolio
                  <span class="gd-btn__circle">
                    <!-- Play icon -->
                    <svg class="w-2.5 h-2.5 text-indigo-600 fill-current ml-0.5" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </span>
                </a>
              </div>
            </div>

            <!-- Right Column: Workspace Scene (55%) -->
            <div class="gd-hero__visual">
              <div class="gd-workspace-wrap">
                
                <!-- Floating Creative Software Badges (Vertical List Left) -->
                <div class="gd-float-software">
                  <!-- Ps -->
                  <div class="gd-software-icon gd-software-icon--ps float-anim-1">
                    <span>Ps</span>
                  </div>
                  <!-- Ai -->
                  <div class="gd-software-icon gd-software-icon--ai float-anim-2">
                    <span>Ai</span>
                  </div>
                  <!-- Id -->
                  <div class="gd-software-icon gd-software-icon--id float-anim-3">
                    <span>Id</span>
                  </div>
                  <!-- Ae -->
                  <div class="gd-software-icon gd-software-icon--ae float-anim-4">
                    <span>Ae</span>
                  </div>
                  <!-- Figma -->
                  <div class="gd-software-icon gd-software-icon--figma float-anim-5">
                    <svg class="w-5 h-5" viewBox="0 0 384 512" fill="currentColor">
                      <path d="M116.5 0C52.2 0 0 52.2 0 116.5S52.2 233 116.5 233c24.6 0 47.4-7.7 66.2-20.7V233c0 64.3 52.2 116.5 116.5 116.5s116.5-52.2 116.5-116.5c0-24.6-7.7-47.4-20.7-66.2H384c24.6 0 47.4-7.7 66.2-20.7v-2.3c0-64.3-52.2-116.5-116.5-116.5S217.3 52.2 217.3 116.5v20c-18.8-13-41.6-20.7-66.2-20.7H116.5zm151.7 448c-32.2 0-58.3-26.1-58.3-58.3v-58.3h58.3c32.2 0 58.3 26.1 58.3 58.3s-26.1 58.3-58.3 58.3z" fill="#F24E1E"/>
                      <path d="M116.5 448c-32.2 0-58.3-26.1-58.3-58.3s26.1-58.3 58.3-58.3h58.3v58.3c0 32.2-26.1 58.3-58.3 58.3z" fill="#A259FF"/>
                      <path d="M116.5 291.7c-32.2 0-58.3-26.1-58.3-58.3s26.1-58.3 58.3-58.3h58.3V291.7H116.5z" fill="#1ABC9C"/>
                      <path d="M116.5 135.3c-32.2 0-58.3-26.1-58.3-58.3S84.3 18.7 116.5 18.7h58.3v116.5H116.5z" fill="#F24E1E"/>
                      <path d="M275 135.3c-32.2 0-58.3-26.1-58.3-58.3S242.8 18.7 275 18.7S333.3 44.8 333.3 77S307.2 135.3 275 135.3z" fill="#FF7262"/>
                    </svg>
                  </div>
                  <!-- Sketch -->
                  <div class="gd-software-icon gd-software-icon--sketch float-anim-6">
                    <svg class="w-5 h-5 text-yellow-500" viewBox="0 0 512 512" fill="currentColor">
                      <path d="M256 22.1L32 165l224 325 224-325L256 22.1z"/>
                    </svg>
                  </div>
                </div>

                <!-- Desktop Monitor Mockup (Centerpiece) -->
                <div class="gd-monitor-mockup float-monitor">
                  <div class="gd-monitor-screen">
                    
                    <!-- Design Software GUI mock -->
                    <div class="gd-screen-gui">
                      <!-- Window header -->
                      <div class="gd-gui-header">
                        <div class="gd-gui-dots">
                          <span class="gd-dot gd-dot--red"></span>
                          <span class="gd-dot gd-dot--yellow"></span>
                          <span class="gd-dot gd-dot--green"></span>
                        </div>
                        <div class="gd-gui-title">DigitallyYours_Poster_V1.fig</div>
                      </div>

                      <div class="gd-gui-body">
                        <!-- Left panel -->
                        <div class="gd-gui-panel gd-gui-panel--left">
                          <div class="gd-tool-group">
                            <span class="gd-tool active">
                              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
                            </span>
                            <span class="gd-tool">T</span>
                            <span class="gd-tool"><span class="w-2.5 h-2.5 border border-slate-400 inline-block"></span></span>
                            <span class="gd-tool"><span class="w-2.5 h-2.5 rounded-full border border-slate-400 inline-block"></span></span>
                            <span class="gd-tool">✏️</span>
                            <span class="gd-tool">📐</span>
                          </div>
                        </div>

                        <!-- Canvas Workspace (Creative Design Showcase) -->
                        <div class="gd-gui-canvas">
                          <div class="gd-poster-art">
                            <div class="gd-poster-bg-grid"></div>
                            
                            <!-- Glowing Bulb & Creative Waves -->
                            <div class="gd-poster-content">
                              <div class="gd-bulb-glow"></div>
                              <svg class="w-16 h-16 text-yellow-400 filter drop-shadow-[0_0_15px_rgba(250,204,21,0.6)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8M12 3a7 7 0 00-7 7c0 2.22 1.02 4.2 2.61 5.5A2.96 2.96 0 019 18h6a2.96 2.96 0 011.39-2.5A7 7 0 0019 10a7 7 0 00-7-7z"/>
                              </svg>
                              <div class="gd-poster-title">Designing Ideas</div>
                              <div class="gd-poster-sub">for a better tomorrow</div>
                            </div>
                            
                            <!-- Anchor points representation -->
                            <div class="gd-anchor gd-anchor--top"></div>
                            <div class="gd-anchor gd-anchor--left"></div>
                            <div class="gd-anchor gd-anchor--right"></div>
                          </div>
                        </div>

                        <!-- Right panel -->
                        <div class="gd-gui-panel gd-gui-panel--right">
                          <div class="gd-layer-stack">
                            <div class="gd-layer-item">Layers</div>
                            <div class="gd-layer-sub active">Bulb Vector</div>
                            <div class="gd-layer-sub">Glow Radial</div>
                            <div class="gd-layer-sub">Heading Text</div>
                            <div class="gd-layer-sub">Background</div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Bezel & Stand -->
                  <div class="gd-monitor-bezel"></div>
                  <div class="gd-monitor-neck"></div>
                  <div class="gd-monitor-stand"></div>
                </div>

                <!-- Drawing Tablet, Stylus and desk items below monitor -->
                <div class="gd-desk-accessories">
                  <!-- Color Palette fan -->
                  <div class="gd-palette-fan rot-palette">
                    <span class="gd-palette-slice gd-palette-slice--1"></span>
                    <span class="gd-palette-slice gd-palette-slice--2"></span>
                    <span class="gd-palette-slice gd-palette-slice--3"></span>
                    <span class="gd-palette-slice gd-palette-slice--4"></span>
                    <span class="gd-palette-slice gd-palette-slice--5"></span>
                  </div>

                  <!-- Stylus and Drawing Tablet -->
                  <div class="gd-drawing-tablet">
                    <span class="gd-tablet-dot"></span>
                    <span class="gd-stylus-pen"></span>
                  </div>

                  <!-- White Coffee Mug -->
                  <div class="gd-coffee-mug">
                    <div class="gd-mug-logo">D</div>
                  </div>
                </div>

                <!-- Floating Stat Cards (Right Column) -->
                <div class="gd-float-cards">
                  <!-- Stat Card 1 -->
                  <div class="gd-stat-card gd-stat-card--1 float-card-1">
                    <div class="gd-stat-card__info">
                      <div class="gd-stat-card__val">{{ animatedStats.projects }}+</div>
                      <div class="gd-stat-card__label">Projects Completed</div>
                    </div>
                    <!-- Small Sparkline SVG -->
                    <div class="gd-stat-card__spark">
                      <svg viewBox="0 0 100 40" class="w-16 h-8 text-indigo-500 stroke-2 fill-none">
                        <path d="M 0 35 Q 20 20 40 25 T 80 5 T 100 10" stroke="#6366f1" stroke-width="2.5" />
                        <path d="M 0 35 Q 20 20 40 25 T 80 5 T 100 10 L 100 40 L 0 40 Z" fill="rgba(99,102,241,0.08)" stroke="none" />
                      </svg>
                    </div>
                  </div>

                  <!-- Stat Card 2 -->
                  <div class="gd-stat-card gd-stat-card--2 float-card-2">
                    <div class="gd-stat-card__info">
                      <div class="gd-stat-card__val">{{ animatedStats.clients }}+</div>
                      <div class="gd-stat-card__label">Happy Clients</div>
                    </div>
                    <div class="gd-stat-card__avatars">
                      <span class="gd-avatar" style="background-color: #cbd5e1">👨‍💻</span>
                      <span class="gd-avatar" style="background-color: #94a3b8">👩‍🎨</span>
                      <span class="gd-avatar" style="background-color: #64748b">👩‍💼</span>
                      <span class="gd-avatar gd-avatar--plus">+</span>
                    </div>
                  </div>

                  <!-- Stat Card 3 -->
                  <div class="gd-stat-card gd-stat-card--3 float-card-3">
                    <div class="gd-stat-card__info">
                      <div class="gd-stat-card__val">{{ animatedStats.satisfaction }}%</div>
                      <div class="gd-stat-card__label">Client Satisfaction</div>
                    </div>
                    <!-- Heart SVG -->
                    <div class="gd-stat-card__satisfy">
                      <svg class="w-6 h-6 text-red-500 fill-current" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                      </svg>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════════
           SECTION 2 – OUR GRAPHIC DESIGN SERVICES
           ═══════════════════════════════════════════════════════════ -->
      <section class="gd-services" id="services">
        <div class="gd-container">
          <!-- Centered section heading -->
          <div class="gd-section-header" data-rev>
            <h2 class="gd-section-title">Our Graphic Design Services</h2>
            <p class="gd-section-subtitle">Creative solutions for every brand, every platform, and every goal.</p>
          </div>

          <!-- Services Grid (2 rows, 5 columns on desktop) -->
          <div class="gd-services-grid">
            
            <!-- Card 1: Logo Design -->
            <div class="gd-service-card" data-rev data-delay="0.0">
              <div class="gd-service-card__icon-bg">
                <!-- Pen/Vector path tool SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Logo Design</h3>
              <p class="gd-service-card__desc">Unique logos that represent your brand identity.</p>
            </div>

            <!-- Card 2: Brand Identity Design -->
            <div class="gd-service-card" data-rev data-delay="0.1">
              <div class="gd-service-card__icon-bg">
                <!-- Card/Branding template SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Brand Identity Design</h3>
              <p class="gd-service-card__desc">Complete brand kits that build a strong brand presence.</p>
            </div>

            <!-- Card 3: Social Media Design -->
            <div class="gd-service-card" data-rev data-delay="0.2">
              <div class="gd-service-card__icon-bg">
                <!-- Share/Grid SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.8 2.766m0 0a3 3 0 100-2.435l-4.8-2.766m0 0a3 3 0 100 4.87m0 0H4M21 12H17"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Social Media Design</h3>
              <p class="gd-service-card__desc">Engaging posts, stories & ads that boost engagement.</p>
            </div>

            <!-- Card 4: Print Design -->
            <div class="gd-service-card" data-rev data-delay="0.3">
              <div class="gd-service-card__icon-bg">
                <!-- Print document SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h8z"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Print Design</h3>
              <p class="gd-service-card__desc">Brochures, flyers, posters, business cards & more.</p>
            </div>

            <!-- Card 5: Packaging Design -->
            <div class="gd-service-card" data-rev data-delay="0.4">
              <div class="gd-service-card__icon-bg">
                <!-- Box SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Packaging Design</h3>
              <p class="gd-service-card__desc">Creative packaging that sells your product.</p>
            </div>

            <!-- Card 6: UI/UX Design -->
            <div class="gd-service-card" data-rev data-delay="0.0">
              <div class="gd-service-card__icon-bg">
                <!-- Device mock SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">UI/UX Design</h3>
              <p class="gd-service-card__desc">User-centered designs for web & mobile applications.</p>
            </div>

            <!-- Card 7: Marketing Collateral -->
            <div class="gd-service-card" data-rev data-delay="0.1">
              <div class="gd-service-card__icon-bg">
                <!-- Marketing Presentation SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21h8M12 3a9 9 0 110 18 9 9 0 010-18z"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Marketing Collateral</h3>
              <p class="gd-service-card__desc">Designs for presentations, proposals & pitch decks.</p>
            </div>

            <!-- Card 8: Infographic Design -->
            <div class="gd-service-card" data-rev data-delay="0.2">
              <div class="gd-service-card__icon-bg">
                <!-- Charts / Data representation SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Infographic Design</h3>
              <p class="gd-service-card__desc">Visual data storytelling that communicates better.</p>
            </div>

            <!-- Card 9: Illustration Design -->
            <div class="gd-service-card" data-rev data-delay="0.3">
              <div class="gd-service-card__icon-bg">
                <!-- Pencil drawing SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l.5 2.5L12 14l-.5-2.5L9 11zM20.5 7.5a2.121 2.121 0 11-3 3l-10 10L3 21l.5-4.5 10-10 7-7z"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Illustration Design</h3>
              <p class="gd-service-card__desc">Custom illustrations tailored to your brand.</p>
            </div>

            <!-- Card 10: Motion Graphics -->
            <div class="gd-service-card" data-rev data-delay="0.4">
              <div class="gd-service-card__icon-bg">
                <!-- Film roll/play animation SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
              </div>
              <h3 class="gd-service-card__title">Motion Graphics</h3>
              <p class="gd-service-card__desc">Animated visuals that bring your brand to life.</p>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════════
           SECTION 3 – TOOLS & SOFTWARE WE USE
           ═══════════════════════════════════════════════════════════ -->
      <section class="gd-tools bg-slate-50 border-t border-b border-slate-100" id="tools">
        <div class="gd-container">
          
          <div class="gd-section-header" data-rev>
            <h2 class="gd-section-title">Tools & Software We Use</h2>
            <p class="gd-section-subtitle">Industry-leading tools to create stunning, professional, and impactful designs.</p>
          </div>

          <!-- Category filter tabs -->
          <div class="gd-tabs" data-rev>
            <button
              v-for="tab in tabs"
              :key="tab"
              @click="activeTab = tab"
              :class="['gd-tab', activeTab === tab ? 'gd-tab--active' : '']"
            >
              {{ tab }}
            </button>
          </div>

          <!-- Filtered Tools Grid -->
          <div class="gd-tools-grid">
            <div 
              v-for="tool in filteredTools" 
              :key="tool.name"
              class="gd-tool-card"
              data-rev
            >
              <div class="gd-tool-card__logo" v-html="getToolLogo(tool.name)"></div>
              <span class="gd-tool-card__name">{{ tool.name }}</span>
            </div>
          </div>

        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════════
           SECTION 4 – OUR DESIGN PROCESS
           ═══════════════════════════════════════════════════════════ -->
      <section class="gd-process bg-white border-b border-slate-100">
        <div class="gd-container">
          
          <div class="gd-section-header" data-rev>
            <h2 class="gd-section-title">Our Design Process</h2>
            <p class="gd-section-subtitle">A creative and collaborative process that ensures exceptional results.</p>
          </div>

          <!-- Process flex Grid (6 Steps) -->
          <div class="gd-process-grid">
            
            <!-- Step 1 -->
            <div class="gd-process-item" data-rev data-delay="0.1">
              <div class="gd-process-card">
                <div class="gd-process-card__icon-wrap">
                  <!-- Understand / Document SVG -->
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <span class="gd-process-card__number">01</span>
                <h3 class="gd-process-card__title">Understand</h3>
                <p class="gd-process-card__desc">We learn about your brand, goals, and target audience.</p>
              </div>
              <div class="gd-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </div>

            <!-- Step 2 -->
            <div class="gd-process-item" data-rev data-delay="0.2">
              <div class="gd-process-card">
                <div class="gd-process-card__icon-wrap">
                  <!-- Research / Search SVG -->
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                  </svg>
                </div>
                <span class="gd-process-card__number">02</span>
                <h3 class="gd-process-card__title">Research</h3>
                <p class="gd-process-card__desc">We analyze competitors and industry trends.</p>
              </div>
              <div class="gd-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </div>

            <!-- Step 3 -->
            <div class="gd-process-item" data-rev data-delay="0.3">
              <div class="gd-process-card">
                <div class="gd-process-card__icon-wrap">
                  <!-- Conceptualize / Idea SVG -->
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                  </svg>
                </div>
                <span class="gd-process-card__number">03</span>
                <h3 class="gd-process-card__title">Conceptualize</h3>
                <p class="gd-process-card__desc">We brainstorm ideas and create design concepts.</p>
              </div>
              <div class="gd-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </div>

            <!-- Step 4 -->
            <div class="gd-process-item" data-rev data-delay="0.4">
              <div class="gd-process-card">
                <div class="gd-process-card__icon-wrap">
                  <!-- Design / Paint SVG -->
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5A3 3 0 009 13h6a3 3 0 001.5-5.5M7.5 7.5A3 3 0 019 2h6a3 3 0 011.5 5.5m-9 0h9"/>
                  </svg>
                </div>
                <span class="gd-process-card__number">04</span>
                <h3 class="gd-process-card__title">Design</h3>
                <p class="gd-process-card__desc">We craft stunning visuals that represent your brand.</p>
              </div>
              <div class="gd-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </div>

            <!-- Step 5 -->
            <div class="gd-process-item" data-rev data-delay="0.5">
              <div class="gd-process-card">
                <div class="gd-process-card__icon-wrap">
                  <!-- Revise / Refresh SVG -->
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5"/>
                  </svg>
                </div>
                <span class="gd-process-card__number">05</span>
                <h3 class="gd-process-card__title">Revise</h3>
                <p class="gd-process-card__desc">We refine the design based on your feedback.</p>
              </div>
              <div class="gd-process-arrow">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </div>

            <!-- Step 6 -->
            <div class="gd-process-item" data-rev data-delay="0.6">
              <div class="gd-process-card">
                <div class="gd-process-card__icon-wrap">
                  <!-- Deliver / Send SVG -->
                  <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                  </svg>
                </div>
                <span class="gd-process-card__number">06</span>
                <h3 class="gd-process-card__title">Deliver</h3>
                <p class="gd-process-card__desc">We deliver final files in all required formats.</p>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════════
           SECTION 5 – WHY CHOOSE OUR GRAPHIC DESIGN SERVICES
           ═══════════════════════════════════════════════════════════ -->
      <section class="gd-why-us">
        <div class="gd-container">
          
          <div class="gd-section-header" data-rev>
            <h2 class="gd-section-title">Why Choose Our Graphic Design Services?</h2>
            <p class="gd-section-subtitle">We don't just build designs, we build visual experiences that make a difference.</p>
          </div>

          <!-- Feature Grid (6 Cards) -->
          <div class="gd-why-us-grid">
            
            <!-- Card 1: Creative & Original -->
            <div class="gd-why-card" data-rev data-delay="0.0">
              <div class="gd-why-card__icon-bg">
                <!-- Palette SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-1.245c0-.73-.244-1.4-.62-1.93zM9.53 16.122A13.934 13.934 0 0112 15.75c1.353 0 2.667.192 3.91.549a3 3 0 011.606 2.079l.21.626a2.25 2.25 0 002.399 1.488 4.496 4.496 0 00.32-6.529c-.77-.77-1.785-1.263-2.883-1.397L13.5 12"/>
                </svg>
              </div>
              <h3 class="gd-why-card__title">Creative & Original</h3>
              <p class="gd-why-card__desc">100% custom designs tailored to your brand.</p>
            </div>

            <!-- Card 2: Professional Designers -->
            <div class="gd-why-card" data-rev data-delay="0.1">
              <div class="gd-why-card__icon-bg">
                <!-- Designer SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 009 11c0-1.28-.203-2.514-.58-3.674M12 11a13.92 13.92 0 012.753 9.571m3.44-2.04l-.054-.09A13.916 13.916 0 0015 11c0-1.28.203-2.514.58-3.674M12 11a13.94 13.94 0 01-3-3m3 3a13.94 13.94 0 003-3M9 8c0-1.657 1.343-3 3-3s3 1.343 3 3m-6 0a3 3 0 013-3m3 3a3 3 0 00-3-3m-3 3v1.23M12 5V3m0 6.23V11"/>
                </svg>
              </div>
              <h3 class="gd-why-card__title">Professional Designers</h3>
              <p class="gd-why-card__desc">Experienced designers with creative expertise.</p>
            </div>

            <!-- Card 3: Unlimited Revisions -->
            <div class="gd-why-card" data-rev data-delay="0.2">
              <div class="gd-why-card__icon-bg">
                <!-- Revisions Loop SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
              </div>
              <h3 class="gd-why-card__title">Unlimited Revisions</h3>
              <p class="gd-why-card__desc">We work until you're 100% satisfied.</p>
            </div>

            <!-- Card 4: On-Time Delivery -->
            <div class="gd-why-card" data-rev data-delay="0.3">
              <div class="gd-why-card__icon-bg">
                <!-- Delivery Clock SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="gd-why-card__title">On-Time Delivery</h3>
              <p class="gd-why-card__desc">We value your time and always deliver on time.</p>
            </div>

            <!-- Card 5: Affordable Pricing -->
            <div class="gd-why-card" data-rev data-delay="0.4">
              <div class="gd-why-card__icon-bg">
                <!-- Price tag SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.504 11.624l-5.64 5.64A1.5 1.5 0 111.62 15.12l5.64-5.64m2.244 2.144l5.64-5.64a1.5 1.5 0 112.128 2.122l-5.64 5.64m-2.244-2.144L9.504 9.48M19.5 21a3 3 0 100-6 3 3 0 000 6z"/>
                </svg>
              </div>
              <h3 class="gd-why-card__title">Affordable Pricing</h3>
              <p class="gd-why-card__desc">High-quality designs at competitive prices.</p>
            </div>

            <!-- Card 6: Multiple Formats -->
            <div class="gd-why-card" data-rev data-delay="0.5">
              <div class="gd-why-card__icon-bg">
                <!-- Folder files SVG -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/>
                </svg>
              </div>
              <h3 class="gd-why-card__title">Multiple Formats</h3>
              <p class="gd-why-card__desc">Final files in all formats you need.</p>
            </div>

          </div>
        </div>
      </section>

      <!-- ═══════════════════════════════════════════════════════════
           SECTION 6 – CTA BANNER
           ═══════════════════════════════════════════════════════════ -->
      <section class="gd-cta-section" data-rev>
        <div class="gd-container">
          <div class="gd-cta-banner gd-cta-banner-anim">
            
            <!-- Vector curves in background -->
            <div class="gd-cta-banner__bg-vector">
              <svg viewBox="0 0 400 200" fill="none" class="w-full h-full opacity-10 text-white">
                <path d="M 0 100 Q 100 20 200 120 T 400 80" stroke="currentColor" stroke-width="6" fill="none" />
                <path d="M 0 140 Q 120 50 240 160 T 400 120" stroke="currentColor" stroke-width="3" fill="none" />
              </svg>
            </div>

            <div class="gd-cta-banner__left">
              <h2 class="gd-cta-banner__title">Ready to Bring Your Brand to Life with Stunning Designs?</h2>
              <p class="gd-cta-banner__sub">Let's create something extraordinary together.</p>
            </div>

            <div class="gd-cta-banner__right">
              <Link href="/contact-us" class="gd-cta-btn">
                Get a Free Design Quote
                <span class="gd-cta-btn__circle">
                  <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
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
/* ═══════════════════════════════════════════════════════════
   GLOBAL STYLES & LAYOUT SYSTEM
   ═══════════════════════════════════════════════════════════ */
.gd-page {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  color: #334155;
  background-color: #ffffff;
  overflow-x: hidden;
}

.gd-container {
  width: 100%;
  max-width: 1320px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 20px;
  padding-right: 20px;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 1 – HERO
   ═══════════════════════════════════════════════════════════ */
.gd-hero {
  background: linear-gradient(180deg, #f5f3ff 0%, #ffffff 100%);
  position: relative;
  overflow: hidden;
  padding-top: 100px;
  padding-bottom: 56px;
}
@media (min-width: 1024px) {
  .gd-hero {
    padding-top: 140px;
    padding-bottom: 96px;
  }
}

.gd-hero__bg-dots {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(rgba(99, 102, 241, 0.04) 1.5px, transparent 1.5px);
  background-size: 24px 24px;
  pointer-events: none;
}
.gd-hero__glow-1 {
  position: absolute;
  top: -150px;
  right: -100px;
  width: 600px;
  height: 600px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
  pointer-events: none;
}
.gd-hero__glow-2 {
  position: absolute;
  bottom: -100px;
  left: -150px;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, rgba(255, 255, 255, 0) 70%);
  pointer-events: none;
}

.gd-hero__wrapper {
  display: grid;
  grid-template-columns: 1fr;
  gap: 48px;
  align-items: center;
}
@media (min-width: 1024px) {
  .gd-hero__wrapper {
    grid-template-columns: 45% 55%;
  }
}

.gd-hero__content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  z-index: 10;
}

/* Breadcrumbs */
.gd-breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #94a3b8;
  margin-bottom: 24px;
  flex-wrap: wrap;
}
.gd-breadcrumb__link {
  color: #94a3b8;
  text-decoration: none;
  transition: color 0.2s;
}
.gd-breadcrumb__link:hover {
  color: #6366f1;
}
.gd-breadcrumb__sep {
  color: #cbd5e1;
}
.gd-breadcrumb__active {
  color: #64748b;
  font-weight: 500;
}

/* Badge wrap */
.gd-hero__badge-wrap {
  margin-bottom: 24px;
}
.gd-badge {
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

/* H1 title */
.gd-hero__title {
  font-size: 38px;
  font-weight: 700;
  line-height: 1.1;
  color: #0f172a;
  letter-spacing: -0.03em;
  margin-bottom: 18px;
}
@media (min-width: 768px) {
  .gd-hero__title {
    font-size: 54px;
  }
}
@media (min-width: 1280px) {
  .gd-hero__title {
    font-size: 64px;
  }
}
.gd-hero__title--highlight {
  color: #6366f1;
}

.gd-hero__desc {
  font-size: 16px;
  line-height: 1.65;
  color: #64748b;
  max-width: 550px;
  margin-bottom: 32px;
}

/* Benefits */
.gd-benefits {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  width: 100%;
  margin-bottom: 40px;
}
@media (max-width: 480px) {
  .gd-benefits {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}
.gd-benefit-item {
  display: flex;
  align-items: center;
  gap: 10px;
}
.gd-benefit-item__icon-wrap {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.gd-benefit-item__label {
  font-size: 14.5px;
  font-weight: 600;
  color: #334155;
}

/* CTA buttons wrapper */
.gd-hero__btns {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  width: 100%;
}
.gd-btn {
  height: 56px;
  padding: 0 28px;
  border-radius: 14px;
  font-size: 15px;
  font-weight: 700;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.gd-btn--primary {
  background-color: #6366f1;
  color: #ffffff;
  box-shadow: 0 8px 25px rgba(99, 102, 241, 0.25);
}
.gd-btn--primary:hover {
  background-color: #4f46e5;
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(99, 102, 241, 0.35);
}
.gd-btn--outline {
  background-color: transparent;
  border: 1.5px solid #cbd5e1;
  color: #334155;
}
.gd-btn--outline:hover {
  border-color: #6366f1;
  color: #6366f1;
  transform: translateY(-2px);
}
.gd-btn__circle {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.25s;
}
.gd-btn--outline:hover .gd-btn__circle {
  background-color: rgba(99, 102, 241, 0.15);
}

/* Workspace Scene */
.gd-hero__visual {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}

.gd-workspace-wrap {
  width: 100%;
  max-width: 580px;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Floating creative suite icons left side */
.gd-float-software {
  position: absolute;
  left: -20px;
  top: 10%;
  display: flex;
  flex-direction: column;
  gap: 16px;
  z-index: 20;
}
@media (max-width: 640px) {
  .gd-float-software {
    left: 0;
    gap: 10px;
  }
}

.gd-software-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 15px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  transition: transform 0.3s;
}
.gd-software-icon:hover {
  transform: scale(1.15) rotate(5deg) !important;
}

.gd-software-icon--ps { background-color: #001E36; color: #00C8FF; border: 1.5px solid rgba(0,200,255,0.3); }
.gd-software-icon--ai { background-color: #261300; color: #FF9A00; border: 1.5px solid rgba(255,154,0,0.3); }
.gd-software-icon--id { background-color: #200015; color: #FF249B; border: 1.5px solid rgba(255,36,155,0.3); }
.gd-software-icon--ae { background-color: #100026; color: #997FFF; border: 1.5px solid rgba(153,127,255,0.3); }
.gd-software-icon--figma { background-color: #1E1E1E; border: 1.5px solid rgba(255,255,255,0.15); }
.gd-software-icon--sketch { background-color: #2A1D00; border: 1.5px solid rgba(253,179,0,0.3); }

/* Desktop Monitor Mockup */
.gd-monitor-mockup {
  width: 100%;
  background-color: #e2e8f0;
  border-radius: 16px;
  padding: 8px;
  box-shadow: 0 30px 60px rgba(15,23,42,0.15);
  border: 1px solid #cbd5e1;
  position: relative;
  z-index: 10;
}
.gd-monitor-screen {
  width: 100%;
  aspect-ratio: 16 / 10;
  background-color: #1e293b;
  border-radius: 10px;
  overflow: hidden;
  position: relative;
}

/* GUI components inside mockup */
.gd-screen-gui {
  display: flex;
  flex-direction: column;
  height: 100%;
  font-size: 10px;
}
.gd-gui-header {
  height: 24px;
  background-color: #0f172a;
  display: flex;
  align-items: center;
  padding: 0 12px;
  border-bottom: 1px solid #1e293b;
  color: #94a3b8;
  position: relative;
}
.gd-gui-dots {
  display: flex;
  gap: 5px;
}
.gd-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
}
.gd-dot--red { background-color: #ef4444; }
.gd-dot--yellow { background-color: #f59e0b; }
.gd-dot--green { background-color: #10b981; }

.gd-gui-title {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  font-size: 9px;
  font-weight: 500;
}

.gd-gui-body {
  flex: 1;
  display: flex;
  overflow: hidden;
  background-color: #0f172a;
}
.gd-gui-panel {
  width: 48px;
  background-color: #090d16;
  border-color: #1e293b;
  display: flex;
  flex-direction: column;
  padding: 8px 4px;
  gap: 8px;
  flex-shrink: 0;
}
.gd-gui-panel--left {
  border-right: 1px solid #1e293b;
  align-items: center;
}
.gd-gui-panel--right {
  border-left: 1px solid #1e293b;
  width: 64px;
}

.gd-tool-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  align-items: center;
  width: 100%;
}
.gd-tool {
  width: 24px;
  height: 24px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  font-weight: 700;
  cursor: pointer;
}
.gd-tool.active {
  background-color: #6366f1;
  color: #ffffff;
}

.gd-layer-stack {
  display: flex;
  flex-direction: column;
  gap: 4px;
  color: #64748b;
  font-size: 8px;
}
.gd-layer-item {
  font-weight: 700;
  color: #94a3b8;
  margin-bottom: 4px;
}
.gd-layer-sub {
  padding: 3px 6px;
  border-radius: 4px;
  cursor: pointer;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}
.gd-layer-sub.active {
  background-color: rgba(99, 102, 241, 0.15);
  color: #818cf8;
}

.gd-gui-canvas {
  flex: 1;
  background-color: #131924;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  position: relative;
}

/* Poster Artwork inside mockup canvas */
.gd-poster-art {
  width: 80%;
  aspect-ratio: 3 / 4;
  background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
  border-radius: 8px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}
.gd-poster-bg-grid {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
  background-size: 10px 10px;
}
.gd-poster-content {
  position: relative;
  z-index: 10;
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #ffffff;
  padding: 12px;
}
.gd-bulb-glow {
  position: absolute;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(253,224,71,0.4) 0%, rgba(253,224,71,0) 70%);
  top: 10px;
}
.gd-poster-title {
  font-size: 14px;
  font-weight: 800;
  margin-top: 8px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  line-height: 1.1;
  text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.gd-poster-sub {
  font-size: 8px;
  opacity: 0.9;
  margin-top: 2px;
  font-style: italic;
}

.gd-anchor {
  position: absolute;
  width: 6px;
  height: 6px;
  background-color: #6366f1;
  border: 1px solid #ffffff;
  z-index: 12;
}
.gd-anchor--top { top: 10%; left: 50%; transform: translateX(-50%); }
.gd-anchor--left { top: 50%; left: 10%; transform: translateY(-50%); }
.gd-anchor--right { top: 50%; right: 10%; transform: translateY(-50%); }

/* Bezel, Neck & Stand */
.gd-monitor-bezel {
  height: 14px;
  background-color: #cbd5e1;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
  width: 100%;
}
.gd-monitor-neck {
  width: 44px;
  height: 30px;
  background-color: #cbd5e1;
  margin: -1px auto 0;
  position: relative;
  z-index: 5;
  clip-path: polygon(15% 0%, 85% 0%, 100% 100%, 0% 100%);
}
.gd-monitor-stand {
  width: 140px;
  height: 8px;
  background-color: #94a3b8;
  border-radius: 4px;
  margin: 0 auto;
  position: relative;
  z-index: 4;
}

/* Desk Accessories */
.gd-desk-accessories {
  width: 90%;
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-top: -8px;
  position: relative;
  z-index: 15;
}

/* Coffee Mug */
.gd-coffee-mug {
  width: 32px;
  height: 36px;
  background-color: #ffffff;
  border-radius: 0 0 10px 10px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 8px 16px rgba(0,0,0,0.06);
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.gd-coffee-mug::before {
  content: '';
  position: absolute;
  right: -8px;
  top: 8px;
  width: 8px;
  height: 18px;
  border: 2px solid #e2e8f0;
  border-left: none;
  border-radius: 0 8px 8px 0;
}
.gd-mug-logo {
  font-size: 14px;
  font-weight: 900;
  color: #6366f1;
}

/* Drawing Tablet */
.gd-drawing-tablet {
  width: 130px;
  height: 80px;
  background-color: #1e293b;
  border-radius: 8px;
  border: 1.5px solid #334155;
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}
.gd-tablet-dot {
  position: absolute;
  top: 10px;
  left: 10px;
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background-color: #10b981;
}
.gd-stylus-pen {
  width: 80px;
  height: 4px;
  background-color: #0f172a;
  border-radius: 2px;
  transform: rotate(-15deg);
  position: absolute;
  top: 45%;
  box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

/* Color palette fan */
.gd-palette-fan {
  width: 70px;
  height: 70px;
  position: relative;
  margin-left: 20px;
}
.gd-palette-slice {
  position: absolute;
  width: 18px;
  height: 50px;
  bottom: 0;
  left: 50%;
  transform-origin: bottom center;
  border-radius: 4px;
  border: 0.5px solid rgba(255,255,255,0.2);
}
.gd-palette-slice--1 { background-color: #ef4444; transform: rotate(-35deg); }
.gd-palette-slice--2 { background-color: #f59e0b; transform: rotate(-17deg); }
.gd-palette-slice--3 { background-color: #10b981; transform: rotate(0deg); }
.gd-palette-slice--4 { background-color: #3b82f6; transform: rotate(17deg); }
.gd-palette-slice--5 { background-color: #8b5cf6; transform: rotate(35deg); }

/* Floating Stats Cards */
.gd-float-cards {
  position: absolute;
  right: -30px;
  top: 5%;
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 220px;
  z-index: 20;
}
@media (max-width: 1024px) {
  .gd-float-cards {
    right: 0;
  }
}
@media (max-width: 640px) {
  .gd-float-cards {
    position: relative;
    right: auto;
    top: auto;
    margin-top: 32px;
    width: 100%;
    grid-template-columns: 1fr;
    display: grid;
    gap: 16px;
  }
}

.gd-stat-card {
  background-color: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border: 1.5px solid #f1f5f9;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
  border-radius: 20px;
  padding: 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: transform 0.3s, box-shadow 0.3s;
}
.gd-stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.15);
}
.gd-stat-card__info {
  display: flex;
  flex-direction: column;
}
.gd-stat-card__val {
  font-size: 20px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.2;
}
.gd-stat-card__label {
  font-size: 11px;
  color: #64748b;
  margin-top: 2px;
}
.gd-stat-card__spark {
  width: 60px;
  display: flex;
  align-items: center;
}
.gd-stat-card__avatars {
  display: flex;
  align-items: center;
}
.gd-avatar {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  border: 1.5px solid #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  margin-left: -6px;
}
.gd-avatar:first-child {
  margin-left: 0;
}
.gd-avatar--plus {
  background-color: #6366f1;
  color: #ffffff;
  font-weight: 700;
}
.gd-stat-card__satisfy {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background-color: rgba(239, 68, 68, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Animations */
.float-anim-1 { animation: floatY 5s ease-in-out infinite; }
.float-anim-2 { animation: floatY 6s ease-in-out infinite 0.5s; }
.float-anim-3 { animation: floatY 5.5s ease-in-out infinite 1s; }
.float-anim-4 { animation: floatY 7s ease-in-out infinite 1.5s; }
.float-anim-5 { animation: floatY 6.5s ease-in-out infinite 2s; }
.float-anim-6 { animation: floatY 8s ease-in-out infinite 2.5s; }

.float-monitor {
  animation: floatY 7s ease-in-out infinite;
}
.rot-palette {
  animation: rotationPalette 15s linear infinite;
}
.float-card-1 { animation: floatY 6s ease-in-out infinite 0.2s; }
.float-card-2 { animation: floatY 6s ease-in-out infinite 1.2s; }
.float-card-3 { animation: floatY 6s ease-in-out infinite 2.2s; }

@keyframes floatY {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
@keyframes rotationPalette {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* ═══════════════════════════════════════════════════════════
   SECTION 2 – OUR GRAPHIC DESIGN SERVICES
   ═══════════════════════════════════════════════════════════ */
.gd-services {
  padding-top: 80px;
  padding-bottom: 80px;
  background-color: #ffffff;
}
.gd-section-header {
  text-align: center;
  max-width: 650px;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 56px;
}
.gd-section-title {
  font-size: 32px;
  font-weight: 700;
  color: #0f172a;
  letter-spacing: -0.02em;
  margin-bottom: 12px;
}
@media (min-width: 768px) {
  .gd-section-title {
    font-size: 42px;
  }
}
.gd-section-subtitle {
  font-size: 16px;
  color: #64748b;
  line-height: 1.6;
}

.gd-services-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}
@media (min-width: 640px) {
  .gd-services-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media (min-width: 1024px) {
  .gd-services-grid {
    grid-template-columns: repeat(5, 1fr);
  }
}

.gd-service-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.gd-service-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.15);
  border-color: rgba(99, 102, 241, 0.25);
}
.gd-service-card__icon-bg {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background-color: rgba(99, 102, 241, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: transform 0.3s;
}
.gd-service-card:hover .gd-service-card__icon-bg {
  transform: scale(1.1);
}
.gd-service-card__title {
  font-size: 17px;
  font-weight: 700;
  color: #0f172a;
}
.gd-service-card__desc {
  font-size: 13.5px;
  color: #64748b;
  line-height: 1.5;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 3 – TOOLS & SOFTWARE WE USE
   ═══════════════════════════════════════════════════════════ */
.gd-tools {
  padding-top: 80px;
  padding-bottom: 80px;
}
.gd-tabs {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  margin-bottom: 40px;
}
.gd-tab {
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
.gd-tab:hover {
  border-color: #6366f1;
  color: #6366f1;
}
.gd-tab--active {
  background-color: #6366f1;
  color: #ffffff;
  border-color: #6366f1;
  box-shadow: 0 8px 20px rgba(99, 102, 241, 0.2);
}

.gd-tools-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}
@media (min-width: 640px) {
  .gd-tools-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 768px) {
  .gd-tools-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
@media (min-width: 1024px) {
  .gd-tools-grid {
    grid-template-columns: repeat(6, 1fr);
  }
}

.gd-tool-card {
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
.gd-tool-card:hover {
  transform: scale(1.03);
  box-shadow: 0 12px 30px rgba(99, 102, 241, 0.12);
  border-color: rgba(99, 102, 241, 0.25);
}
.gd-tool-card__logo {
  display: flex;
  align-items: center;
  justify-content: center;
}
.gd-tool-card__name {
  font-size: 13.5px;
  font-weight: 600;
  color: #334155;
  line-height: 1.2;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 4 – OUR DESIGN PROCESS
   ═══════════════════════════════════════════════════════════ */
.gd-process {
  padding-top: 80px;
  padding-bottom: 80px;
}
.gd-process-grid {
  display: flex;
  flex-direction: column;
  gap: 24px;
}
@media (min-width: 1024px) {
  .gd-process-grid {
    flex-direction: row;
    gap: 0;
  }
}

.gd-process-item {
  flex: 1;
  display: flex;
  align-items: center;
  position: relative;
}

.gd-process-card {
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
.gd-process-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.15);
  border-color: rgba(99, 102, 241, 0.2);
}

.gd-process-card__icon-wrap {
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
.gd-process-card:hover .gd-process-card__icon-wrap {
  transform: scale(1.1);
}

.gd-process-card__number {
  position: absolute;
  top: 16px;
  right: 20px;
  font-size: 12px;
  font-weight: 800;
  color: rgba(99, 102, 241, 0.25);
  letter-spacing: 1px;
}

.gd-process-card__title {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}
.gd-process-card__desc {
  font-size: 13px;
  color: #64748b;
  line-height: 1.6;
}

.gd-process-arrow {
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
  .gd-process-arrow {
    display: flex;
  }
}

/* ═══════════════════════════════════════════════════════════
   SECTION 5 – WHY CHOOSE OUR GRAPHIC DESIGN SERVICES
   ═══════════════════════════════════════════════════════════ */
.gd-why-us {
  padding-top: 80px;
  padding-bottom: 80px;
  background-color: #f8fafc;
}
.gd-why-us-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}
@media (min-width: 640px) {
  .gd-why-us-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media (min-width: 1024px) {
  .gd-why-us-grid {
    grid-template-columns: repeat(6, 1fr);
  }
}

.gd-why-card {
  background-color: #ffffff;
  border: 1.5px solid #f1f5f9;
  border-radius: 20px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.gd-why-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.15);
  border-color: rgba(99, 102, 241, 0.25);
}
.gd-why-card__icon-bg {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background-color: rgba(99, 102, 241, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: transform 0.3s;
}
.gd-why-card:hover .gd-why-card__icon-bg {
  transform: scale(1.1);
}
.gd-why-card__title {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
}
.gd-why-card__desc {
  font-size: 13.5px;
  color: #64748b;
  line-height: 1.5;
}

/* ═══════════════════════════════════════════════════════════
   SECTION 6 – CTA BANNER
   ═══════════════════════════════════════════════════════════ */
.gd-cta-section {
  padding-top: 40px;
  padding-bottom: 80px;
  background-color: #ffffff;
}
.gd-cta-banner {
  background: linear-gradient(135deg, #6366f1, #818cf8, #4f46e5, #6366f1);
  background-size: 300% 300%;
  border-radius: 24px;
  padding: 40px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(99, 102, 241, 0.18);
  display: flex;
  flex-direction: column;
  gap: 24px;
}
@media (min-width: 768px) {
  .gd-cta-banner {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 48px 64px;
  }
}

.gd-cta-banner-anim {
  animation: bannerGradient 12s ease infinite;
}
@keyframes bannerGradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.gd-cta-banner__bg-vector {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 1;
}

.gd-cta-banner__left {
  position: relative;
  z-index: 2;
  flex: 1;
}
.gd-cta-banner__title {
  font-size: 24px;
  font-weight: 800;
  color: #ffffff;
  line-height: 1.25;
  margin-bottom: 8px;
}
@media (min-width: 768px) {
  .gd-cta-banner__title {
    font-size: 28px;
  }
}
.gd-cta-banner__sub {
  font-size: 15px;
  color: rgba(255, 255, 255, 0.9);
}

.gd-cta-banner__right {
  position: relative;
  z-index: 2;
  flex-shrink: 0;
}
.gd-cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  background-color: #ffffff;
  color: #6366f1;
  font-size: 15px;
  font-weight: 700;
  padding: 14px 28px;
  border-radius: 14px;
  text-decoration: none;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.gd-cta-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.gd-cta-btn__circle {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(99, 102, 241, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
