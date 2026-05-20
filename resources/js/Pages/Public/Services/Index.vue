<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  services: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <Head>
    <title>Our Services - RankQuill</title>
    <meta name="description" content="Explore our premium enterprise services including Web Development, App Development, SEO, PPC, Graphic Design, and AI Solutions." />
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
          <Link href="/services" class="hover:text-indigo-400 transition-colors text-indigo-400">Services</Link>
          <a href="#" class="hover:text-indigo-400 transition-colors">Portfolio</a>
          <Link href="/blog" class="hover:text-indigo-400 transition-colors">Blog</Link>
        </nav>
        <button class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2.5 rounded-full transition-all duration-300 shadow-lg shadow-indigo-600/20 hover:scale-[1.02]">
          Get Started
        </button>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-20 space-y-16">
      <div class="space-y-4 text-center max-w-3xl mx-auto">
        <span class="text-xs font-bold tracking-widest text-indigo-400 uppercase">Growth & Tech Solutions</span>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
          Services We Offer
        </h1>
        <p class="text-slate-400 font-light leading-relaxed text-base md:text-lg">
          We leverage state-of-the-art framework methodologies, intelligent automation pipelines, and modern designs to drive high conversion capabilities for global brands.
        </p>
      </div>

      <div v-if="services.data.length === 0" class="border border-slate-950 p-16 text-center text-slate-500 rounded-3xl">
        No services configured yet.
      </div>

      <div v-else class="grid md:grid-cols-3 gap-8">
        <article v-for="service in services.data" :key="service.id" class="group border border-slate-800/80 bg-slate-900/20 hover:bg-slate-900/40 rounded-2xl overflow-hidden flex flex-col justify-between hover:border-slate-700 transition-all duration-300 relative">
          <div class="p-8 space-y-6">
            <!-- Starting Price Badge -->
            <div class="flex justify-between items-start">
              <span class="inline-block bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-semibold py-1 px-3 rounded-full">
                {{ service.tagline || 'Premium' }}
              </span>
              <div v-if="service.price_starting_at" class="text-right">
                <div class="text-xs text-slate-500">Starting from</div>
                <div class="text-lg font-bold text-white">${{ parseFloat(service.price_starting_at).toLocaleString() }}</div>
              </div>
            </div>

            <div class="space-y-3">
              <h3 class="text-2xl font-bold text-white group-hover:text-indigo-400 transition-colors">
                <Link :href="`/services/${service.slug}`">
                  {{ service.title }}
                </Link>
              </h3>
              <p class="text-sm text-slate-400 font-light leading-relaxed">
                {{ service.description || 'Professional design and development solutions tailored to meet enterprise-level performance and business targets.' }}
              </p>
            </div>

            <!-- Features List -->
            <ul v-if="service.features && service.features.length > 0" class="space-y-2.5 pt-4 border-t border-slate-900/60">
              <li v-for="(feat, idx) in service.features.slice(0, 4)" :key="idx" class="flex items-center gap-2 text-xs text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ feat }}</span>
              </li>
            </ul>
          </div>

          <div class="p-8 pt-0">
            <Link :href="`/services/${service.slug}`" class="block w-full text-center bg-slate-900/80 hover:bg-indigo-600 text-white font-medium py-3 rounded-xl transition-all duration-300 border border-slate-800 group-hover:border-indigo-500/20 text-sm">
              Explore Details
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
