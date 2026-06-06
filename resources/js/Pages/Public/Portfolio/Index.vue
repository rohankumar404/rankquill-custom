<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  portfolios: { type: Object, required: true },
  categories: { type: Array, required: true },
  selectedCategory: { type: String, default: null }
})

const filterCategory = (slug) => {
  router.get('/portfolio', slug ? { category: slug } : {}, { preserveState: true })
}

const gradients = [
  'from-indigo-600 to-purple-700',
  'from-purple-600 to-pink-700',
  'from-cyan-600 to-indigo-700',
  'from-emerald-600 to-cyan-700',
]
</script>

<template>
  <Head>
    <title>Portfolio & Case Studies — RankQuill</title>
    <meta name="description" content="Explore our premium case studies, conversion metrics, and development results for enterprise clients worldwide." />
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
          <span class="text-slate-300">Portfolio</span>
        </div>
        <span class="section-label mb-4 inline-block">Case Studies</span>
        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight mb-6">
          Results That<br /><span class="gradient-text">Speak Volumes</span>
        </h1>
        <p class="text-slate-400 text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
          Deep-dive case studies showing how we transform challenges into measurable digital success stories.
        </p>
      </div>
    </section>

    <!-- Filter Tabs + Grid -->
    <section class="py-20 bg-white dark:bg-slate-950">
      <div class="section-wrapper">

        <!-- Category Filters -->
        <div v-if="categories.length" class="flex flex-wrap items-center justify-center gap-2 mb-12">
          <button
            @click="filterCategory(null)"
            class="text-sm font-semibold px-5 py-2.5 rounded-full transition-all duration-200 border"
            :class="!selectedCategory
              ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-600/20'
              : 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-indigo-500/30 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white dark:bg-slate-900'"
          >
            All Projects
          </button>
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="filterCategory(cat.slug)"
            class="text-sm font-semibold px-5 py-2.5 rounded-full transition-all duration-200 border"
            :class="selectedCategory === cat.slug
              ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-600/20'
              : 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-indigo-500/30 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white dark:bg-slate-900'"
          >
            {{ cat.name }}
          </button>
        </div>

        <!-- Empty state -->
        <div v-if="portfolios.data.length === 0"
          class="text-center py-24 border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
          <div class="w-20 h-20 rounded-3xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
          <h3 class="font-bold text-slate-900 dark:text-white text-xl mb-2">No projects yet</h3>
          <p class="text-slate-500 text-sm">Case studies will appear here once added.</p>
        </div>

        <!-- Portfolio Grid -->
        <div v-else class="grid md:grid-cols-2 gap-6">
          <article
            v-for="(project, idx) in portfolios.data"
            :key="project.id"
            class="group bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden card-hover flex flex-col"
          >
            <!-- Thumbnail -->
            <div class="relative h-52 overflow-hidden" :class="`bg-gradient-to-br ${gradients[idx % gradients.length]}`">
              <div class="absolute inset-0 hero-grid opacity-20"></div>

              <!-- Client & Date overlay -->
              <div class="absolute inset-0 flex flex-col justify-between p-6">
                <div class="flex items-start justify-between">
                  <span class="bg-white/20 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full border border-white/30 uppercase tracking-wider">
                    {{ project.client_name }}
                  </span>
                  <span v-if="project.completed_at" class="text-white/60 text-xs">
                    {{ new Date(project.completed_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short' }) }}
                  </span>
                </div>

                <!-- Metrics preview in thumbnail -->
                <div v-if="project.metrics?.length" class="flex gap-4">
                  <div v-for="(m, mi) in project.metrics.slice(0, 3)" :key="mi"
                    class="bg-white/15 backdrop-blur-sm rounded-xl px-3 py-2 border border-white/20">
                    <div class="text-[10px] text-white/70 font-medium">{{ m.label }}</div>
                    <div class="text-sm font-black text-white">{{ m.value }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Content -->
            <div class="p-7 flex-1 flex flex-col">
              <div class="flex-1 space-y-3 mb-5">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight text-xl">
                  <Link :href="`/portfolio/${project.slug}`">{{ project.project_title }}</Link>
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-3">
                  {{ project.challenge || 'Strategic optimization and scaling case study showing metrics-driven organic growth transformations.' }}
                </p>
              </div>

              <!-- Footer row -->
              <div class="flex items-center justify-between pt-5 border-t border-slate-100 dark:border-slate-800">
                <div class="flex flex-wrap gap-1.5">
                  <span
                    v-for="cat in project.categories?.slice(0, 3)"
                    :key="cat.id"
                    class="text-[10px] bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 px-2.5 py-1 rounded-lg font-medium"
                  >
                    {{ cat.name }}
                  </span>
                </div>
                <Link
                  :href="`/portfolio/${project.slug}`"
                  class="flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex-shrink-0 group-hover:gap-2 transition-all"
                >
                  Case Study
                  <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </Link>
              </div>
            </div>
          </article>
        </div>

        <!-- Pagination -->
        <div v-if="portfolios.last_page > 1" class="flex items-center justify-center gap-2 mt-12">
          <Link
            v-for="(link, i) in portfolios.links"
            :key="i"
            :href="link.url || '#'"
            v-html="link.label"
            class="min-w-[40px] h-10 flex items-center justify-center px-3 rounded-xl text-sm font-medium transition-all border"
            :class="link.active
              ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-600/20'
              : link.url
                ? 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-indigo-500/30 hover:text-indigo-600 dark:hover:text-indigo-400'
                : 'border-slate-100 dark:border-slate-900 text-slate-300 dark:text-slate-700 cursor-not-allowed'"
          />
        </div>
      </div>
    </section>

    <!-- Bottom CTA -->
    <section class="py-20 bg-slate-50 dark:bg-[#070b14]">
      <div class="section-wrapper">
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-12 sm:p-16 text-center overflow-hidden">
          <div class="absolute inset-0 hero-grid opacity-20"></div>
          <div class="relative z-10 max-w-xl mx-auto space-y-6">
            <h2 class="text-4xl font-black text-white tracking-tight">Ready to Become<br />Our Next Success Story?</h2>
            <p class="text-indigo-100 text-lg">Let's build something extraordinary together.</p>
            <Link href="/contact-us" class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-8 py-4 rounded-full hover:bg-indigo-50 transition-all hover:scale-[1.02] shadow-xl text-base">
              Start Your Project →
            </Link>
          </div>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>
