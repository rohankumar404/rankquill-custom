<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
  services: { type: Object, required: true }
})

const serviceColors = [
  { bg: 'bg-indigo-500/10', text: 'text-indigo-500', border: 'border-indigo-500/20', gradient: 'from-indigo-600 to-indigo-500' },
  { bg: 'bg-emerald-500/10', text: 'text-emerald-500', border: 'border-emerald-500/20', gradient: 'from-emerald-600 to-emerald-500' },
  { bg: 'bg-purple-500/10', text: 'text-purple-500', border: 'border-purple-500/20', gradient: 'from-purple-600 to-purple-500' },
  { bg: 'bg-orange-500/10', text: 'text-orange-500', border: 'border-orange-500/20', gradient: 'from-orange-600 to-orange-500' },
  { bg: 'bg-pink-500/10', text: 'text-pink-500', border: 'border-pink-500/20', gradient: 'from-pink-600 to-pink-500' },
  { bg: 'bg-cyan-500/10', text: 'text-cyan-500', border: 'border-cyan-500/20', gradient: 'from-cyan-600 to-cyan-500' },
]

const getColor = (idx) => serviceColors[idx % serviceColors.length]
</script>

<template>
  <Head>
    <title>Our Services — RankQuill Digital Agency</title>
    <meta name="description" content="Explore our premium enterprise services including Web Development, App Development, SEO, PPC, Graphic Design, and AI Solutions." />
  </Head>

  <PublicLayout>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-[#070b14] py-28 sm:py-36">
      <div class="absolute inset-0 hero-grid opacity-60"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="section-wrapper relative z-10 text-center">
        <div class="flex items-center justify-center gap-2 text-sm text-slate-500 mb-6">
          <Link href="/" class="hover:text-slate-300 transition-colors">Home</Link>
          <span>/</span>
          <span class="text-slate-300">Services</span>
        </div>
        <span class="section-label mb-4 inline-block">What We Do</span>
        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight mb-6">
          Services That<br /><span class="gradient-text">Drive Results</span>
        </h1>
        <p class="text-slate-400 text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
          Enterprise-grade digital solutions engineered for measurable growth and lasting market authority.
        </p>
      </div>
    </section>

    <!-- Services Grid -->
    <section class="py-24 bg-white dark:bg-slate-950">
      <div class="section-wrapper">

        <div v-if="services.data.length === 0" class="text-center py-24">
          <div class="w-20 h-20 rounded-3xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          </div>
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No services yet</h3>
          <p class="text-slate-500 text-sm">Services will appear here once added from the admin panel.</p>
        </div>

        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <article
            v-for="(service, idx) in services.data"
            :key="service.id"
            class="group relative bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 card-hover flex flex-col overflow-hidden"
          >
            <!-- Hover gradient line -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

            <!-- Icon -->
            <div :class="`w-14 h-14 rounded-2xl ${getColor(idx).bg} border ${getColor(idx).border} flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110`">
              <svg class="w-7 h-7" :class="getColor(idx).text" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 space-y-3 mb-6">
              <div class="flex items-start justify-between gap-3">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">
                  <Link :href="`/services/${service.slug}`">{{ service.title }}</Link>
                </h3>
                <div v-if="service.price_starting_at" class="flex-shrink-0 text-right">
                  <div class="text-[10px] text-slate-400 uppercase tracking-wider">From</div>
                  <div class="text-sm font-bold text-slate-900 dark:text-white">${{ parseFloat(service.price_starting_at).toLocaleString() }}</div>
                </div>
              </div>

              <p v-if="service.tagline" :class="`text-xs font-bold uppercase tracking-widest ${getColor(idx).text}`">{{ service.tagline }}</p>

              <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                {{ service.description || 'Professional enterprise-grade solutions tailored to your business goals.' }}
              </p>
            </div>

            <!-- Features List -->
            <ul v-if="service.features?.length" class="space-y-2.5 mb-6 pt-4 border-t border-slate-100 dark:border-slate-800">
              <li v-for="(feat, fIdx) in service.features.slice(0, 4)" :key="fIdx" class="flex items-center gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                <div class="w-4 h-4 rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center flex-shrink-0">
                  <svg class="w-2.5 h-2.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                {{ feat }}
              </li>
            </ul>

            <!-- CTA -->
            <Link :href="`/services/${service.slug}`"
              class="flex items-center justify-between px-5 py-3 rounded-2xl bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 group-hover:border-indigo-500/30 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-500/10 transition-all duration-300 text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
              Explore Details
              <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </Link>
          </article>
        </div>
      </div>
    </section>

    <!-- Bottom CTA -->
    <section class="py-20 bg-slate-50 dark:bg-[#070b14]">
      <div class="section-wrapper">
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-12 sm:p-16 text-center overflow-hidden">
          <div class="absolute inset-0 hero-grid opacity-20"></div>
          <div class="relative z-10 max-w-xl mx-auto space-y-6">
            <h2 class="text-4xl font-black text-white tracking-tight">Not Sure Which Service<br />Is Right for You?</h2>
            <p class="text-indigo-100">Book a free 30-minute strategy call and we'll recommend the perfect solution.</p>
            <Link href="/contact-us" class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-8 py-4 rounded-full hover:bg-indigo-50 transition-all hover:scale-[1.02] shadow-xl text-base">
              Book Free Strategy Call →
            </Link>
          </div>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>
