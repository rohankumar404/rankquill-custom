<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

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

  <PublicLayout>
    <main class="max-w-7xl mx-auto px-6 py-20 space-y-16">
      <div class="space-y-4 text-center max-w-3xl mx-auto">
        <span class="text-xs font-bold tracking-widest text-indigo-600 dark:text-indigo-400 uppercase">Growth & Tech Solutions</span>
        <h1 class="text-4xl md:text-6xl font-extrabold text-slate-950 dark:text-white tracking-tight leading-tight">
          Services We Offer
        </h1>
        <p class="text-slate-500 dark:text-slate-400 font-light leading-relaxed text-base md:text-lg">
          We leverage state-of-the-art framework methodologies, intelligent automation pipelines, and modern designs to drive high conversion capabilities for global brands.
        </p>
      </div>

      <div v-if="services.data.length === 0" class="border border-slate-200 dark:border-slate-950 p-16 text-center text-slate-400 dark:text-slate-500 rounded-3xl">
        No services configured yet.
      </div>

      <div v-else class="grid md:grid-cols-3 gap-8">
        <article v-for="service in services.data" :key="service.id" class="group border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900/20 hover:bg-slate-50 dark:hover:bg-slate-900/40 rounded-2xl overflow-hidden flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300 relative">
          <div class="p-8 space-y-6">
            <!-- Starting Price Badge -->
            <div class="flex justify-between items-start">
              <span class="inline-block bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 text-xs font-semibold py-1 px-3 rounded-full">
                {{ service.tagline || 'Premium' }}
              </span>
              <div v-if="service.price_starting_at" class="text-right">
                <div class="text-xs text-slate-405 dark:text-slate-500">Starting from</div>
                <div class="text-lg font-bold text-slate-950 dark:text-white">${{ parseFloat(service.price_starting_at).toLocaleString() }}</div>
              </div>
            </div>

            <div class="space-y-3">
              <h3 class="text-2xl font-bold text-slate-950 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                <Link :href="`/services/${service.slug}`">
                  {{ service.title }}
                </Link>
              </h3>
              <p class="text-sm text-slate-500 dark:text-slate-400 font-light leading-relaxed">
                {{ service.description || 'Professional design and development solutions tailored to meet enterprise-level performance and business targets.' }}
              </p>
            </div>

            <!-- Features List -->
            <ul v-if="service.features && service.features.length > 0" class="space-y-2.5 pt-4 border-t border-slate-100 dark:border-slate-900/60">
              <li v-for="(feat, idx) in service.features.slice(0, 4)" :key="idx" class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 dark:text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ feat }}</span>
              </li>
            </ul>
          </div>

          <div class="p-8 pt-0">
            <Link :href="`/services/${service.slug}`" class="block w-full text-center bg-slate-100 hover:bg-indigo-600 text-slate-800 hover:text-white dark:bg-slate-900/80 dark:hover:bg-indigo-600 dark:text-white font-medium py-3 rounded-xl transition-all duration-300 border border-slate-200 dark:border-slate-800 group-hover:border-indigo-500/20 text-sm">
              Explore Details
            </Link>
          </div>
        </article>
      </div>
    </main>
  </PublicLayout>
</template>
