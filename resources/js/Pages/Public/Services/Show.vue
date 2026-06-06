<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  service: { type: Object, required: true }
})

const schemaJson = props.service.seo?.schema_markup
  ? JSON.stringify(props.service.seo.schema_markup)
  : null
</script>

<template>
  <Head>
    <title>{{ service.seo?.meta_title || service.title }} — RankQuill</title>
    <meta name="description" :content="service.seo?.meta_description || service.tagline || ''" />
    <meta name="robots" :content="service.seo?.robots || 'index, follow'" />
    <meta property="og:title" :content="service.seo?.og_title || service.title" />
    <meta property="og:description" :content="service.seo?.og_description || service.tagline || ''" />
    <script v-if="schemaJson" type="application/ld+json" v-html="schemaJson"></script>
  </Head>

  <PublicLayout>

    <!-- ======= HERO ======= -->
    <section class="relative overflow-hidden bg-[#070b14] py-28 sm:py-36">
      <div class="absolute inset-0 hero-grid opacity-60"></div>
      <div class="absolute top-1/2 -translate-y-1/2 left-0 right-0 flex justify-center pointer-events-none">
        <div class="w-[700px] h-[400px] bg-indigo-600/10 rounded-full blur-3xl"></div>
      </div>

      <div class="section-wrapper relative z-10">
        <!-- Breadcrumb -->
        <div class="flex items-center justify-center gap-2 text-sm text-slate-500 mb-8">
          <Link href="/" class="hover:text-slate-300 transition-colors">Home</Link>
          <span>/</span>
          <Link href="/services" class="hover:text-slate-300 transition-colors">Services</Link>
          <span>/</span>
          <span class="text-slate-300">{{ service.title }}</span>
        </div>

        <div class="max-w-4xl mx-auto text-center space-y-6">
          <span class="section-label">{{ service.tagline || 'Service Details' }}</span>
          <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight">
            {{ service.title }}
          </h1>
          <p class="text-slate-400 text-lg sm:text-xl leading-relaxed max-w-3xl mx-auto">
            {{ service.description }}
          </p>

          <div class="flex flex-wrap gap-4 justify-center pt-2">
            <Link href="/contact-us" class="btn-primary px-8 py-4 text-base">
              Get Started Today
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </Link>
            <div v-if="service.price_starting_at" class="flex items-center gap-2 px-6 py-4 rounded-full border border-slate-700 text-slate-300">
              <span class="text-sm">Starting from</span>
              <span class="font-black text-white text-lg">${{ parseFloat(service.price_starting_at).toLocaleString() }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= FEATURES LIST (Top-level service features) ======= -->
    <section v-if="service.features?.length" class="py-20 bg-white dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800">
      <div class="section-wrapper">
        <div class="section-header">
          <span class="section-label">What's Included</span>
          <h2 class="section-title">Key <span class="gradient-text">Deliverables</span></h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="(feat, idx) in service.features" :key="idx"
            class="flex items-start gap-4 p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-500/30 transition-colors">
            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm text-slate-700 dark:text-slate-300 font-medium leading-relaxed">{{ feat }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= DYNAMIC CONTENT BLOCKS ======= -->
    <div v-if="service.content?.length">
      <div v-for="(section, idx) in service.content" :key="idx">

        <!-- Hero block -->
        <section v-if="section.type === 'hero'" class="py-20 bg-slate-50 dark:bg-[#070b14]">
          <div class="section-wrapper">
            <div class="grid md:grid-cols-2 gap-12 items-center">
              <div class="space-y-6">
                <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">{{ section.data.title }}</h2>
                <p class="text-slate-500 dark:text-slate-400 leading-relaxed">{{ section.data.subtitle }}</p>
                <a v-if="section.data.primary_cta_url" :href="section.data.primary_cta_url" class="btn-primary inline-flex">
                  {{ section.data.primary_cta_text || 'Learn More' }}
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
              </div>
              <div v-if="section.data.image" class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-3xl blur-3xl opacity-10"></div>
                <img :src="section.data.image" alt="Service illustration" class="relative rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl w-full object-cover aspect-video" />
              </div>
            </div>
          </div>
        </section>

        <!-- Features block -->
        <section v-else-if="section.type === 'features'" class="py-20 bg-white dark:bg-slate-950">
          <div class="section-wrapper">
            <div v-if="section.data.title" class="section-header">
              <h2 class="section-title">{{ section.data.title }}</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="(item, i) in section.data.items" :key="i"
                class="group bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 card-hover">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-5">
                  <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ item.title }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ item.description }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- FAQs block -->
        <section v-else-if="section.type === 'faqs'" class="py-20 bg-slate-50 dark:bg-[#070b14]">
          <div class="section-wrapper">
            <div class="section-header">
              <span class="section-label">FAQ</span>
              <h2 class="section-title">{{ section.data.title || 'Common Questions' }}</h2>
            </div>
            <div class="max-w-3xl mx-auto space-y-4">
              <details v-for="faq in section.data.resolved_items" :key="faq.id"
                class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden hover:border-indigo-500/30 transition-colors">
                <summary class="flex items-center justify-between gap-4 p-6 cursor-pointer list-none">
                  <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ faq.question }}</h3>
                  <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-6 pb-6"><p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ faq.answer }}</p></div>
              </details>
            </div>
          </div>
        </section>

        <!-- Testimonials block -->
        <section v-else-if="section.type === 'testimonials'" class="py-20 bg-white dark:bg-slate-950">
          <div class="section-wrapper">
            <div class="section-header"><h2 class="section-title">{{ section.data.title || 'Client Feedback' }}</h2></div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="t in section.data.resolved_items" :key="t.id"
                class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 card-hover">
                <div class="flex gap-1 mb-4">
                  <svg v-for="s in 5" :key="s" class="w-4 h-4" :class="s <= (t.rating||5) ? 'text-amber-400 fill-current' : 'text-slate-200 dark:text-slate-700'" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300 italic leading-relaxed mb-5">"{{ t.testimonial }}"</p>
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs">{{ t.client_name?.charAt(0) }}</div>
                  <div>
                    <div class="text-xs font-bold text-slate-900 dark:text-white">{{ t.client_name }}</div>
                    <div class="text-[10px] text-slate-400">{{ t.client_position }}, {{ t.client_company }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- CTA block -->
        <section v-else-if="section.type === 'cta'" class="py-20 bg-slate-50 dark:bg-[#070b14]">
          <div class="section-wrapper">
            <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-12 text-center overflow-hidden">
              <div class="absolute inset-0 hero-grid opacity-20"></div>
              <div class="relative z-10 max-w-xl mx-auto space-y-5">
                <h2 class="text-4xl font-black text-white tracking-tight">{{ section.data.title }}</h2>
                <p v-if="section.data.subtitle" class="text-indigo-100">{{ section.data.subtitle }}</p>
                <a v-if="section.data.button_url" :href="section.data.button_url" class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-8 py-4 rounded-full hover:bg-indigo-50 transition-all hover:scale-[1.02] shadow-xl">
                  {{ section.data.button_text }}
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
              </div>
            </div>
          </div>
        </section>

        <!-- Rich content block -->
        <section v-else-if="section.type === 'content'" class="py-16 bg-white dark:bg-slate-950">
          <div class="section-wrapper">
            <div class="max-w-3xl mx-auto prose prose-slate dark:prose-invert prose-lg prose-headings:font-black prose-a:text-indigo-600 dark:prose-a:text-indigo-400" v-html="section.data.body"></div>
          </div>
        </section>

      </div>
    </div>

    <!-- ======= PRICING ======= -->
    <section v-if="service.pricing?.length" class="py-24 bg-white dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800">
      <div class="section-wrapper">
        <div class="section-header">
          <span class="section-label">Investment</span>
          <h2 class="section-title">Flexible <span class="gradient-text">Pricing Plans</span></h2>
          <p class="section-subtitle">Choose the package that fits your goals and scale as you grow.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
          <div v-for="(plan, idx) in service.pricing" :key="idx"
            class="relative flex flex-col rounded-3xl p-8 border transition-all duration-300"
            :class="idx === 1
              ? 'border-indigo-500 bg-gradient-to-b from-indigo-600/5 to-purple-600/5 dark:from-indigo-500/10 dark:to-purple-500/5 shadow-2xl shadow-indigo-500/10 scale-[1.02]'
              : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900'">

            <!-- Popular badge -->
            <div v-if="idx === 1" class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">
              Most Popular ⭐
            </div>

            <div class="mb-6">
              <h3 class="text-base font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">{{ plan.name }}</h3>
              <div class="flex items-baseline gap-1">
                <span class="text-5xl font-black text-slate-900 dark:text-white">${{ parseFloat(plan.price).toLocaleString() }}</span>
                <span class="text-slate-400 text-sm">/ {{ plan.interval || 'month' }}</span>
              </div>
              <p v-if="plan.description" class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mt-3">{{ plan.description }}</p>
            </div>

            <ul v-if="plan.features?.length" class="space-y-3 mb-8 flex-1">
              <li v-for="(feat, fIdx) in plan.features" :key="fIdx" class="flex items-start gap-3 text-sm text-slate-600 dark:text-slate-300">
                <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                  :class="idx === 1 ? 'bg-indigo-500/10 text-indigo-500' : 'bg-emerald-500/10 text-emerald-500'">
                  <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                {{ feat }}
              </li>
            </ul>

            <Link href="/contact-us" class="block w-full text-center py-3.5 rounded-2xl text-sm font-bold transition-all duration-300"
              :class="idx === 1 ? 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/20' : 'bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200'">
              Get Started →
            </Link>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= FALLBACK FAQs ======= -->
    <section v-if="(!service.content || service.content.every(s => s.type !== 'faqs')) && service.faqs?.length" class="py-20 bg-slate-50 dark:bg-[#070b14]">
      <div class="section-wrapper">
        <div class="section-header">
          <span class="section-label">FAQ</span>
          <h2 class="section-title">Common <span class="gradient-text">Questions</span></h2>
        </div>
        <div class="max-w-3xl mx-auto space-y-4">
          <details v-for="faq in service.faqs" :key="faq.id"
            class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden hover:border-indigo-500/30 transition-colors">
            <summary class="flex items-center justify-between gap-4 p-6 cursor-pointer list-none">
              <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ faq.question }}</h3>
              <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="px-6 pb-6"><p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ faq.answer }}</p></div>
          </details>
        </div>
      </div>
    </section>

    <!-- ======= BOTTOM CTA ======= -->
    <section class="py-20 bg-white dark:bg-slate-950">
      <div class="section-wrapper">
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-12 sm:p-16 overflow-hidden">
          <div class="absolute inset-0 hero-grid opacity-20"></div>
          <div class="relative z-10 max-w-2xl mx-auto text-center space-y-6">
            <h2 class="text-4xl font-black text-white tracking-tight">Ready to Scale Your {{ service.title }}?</h2>
            <p class="text-indigo-100 text-lg">Let's discuss your project goals and build a custom strategy for you.</p>
            <div class="flex flex-wrap gap-4 justify-center">
              <Link href="/contact-us" class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-8 py-4 rounded-full hover:bg-indigo-50 transition-all hover:scale-[1.02] shadow-xl">
                Start Your Project →
              </Link>
              <Link href="/services" class="inline-flex items-center gap-2 border border-white/30 text-white font-bold px-8 py-4 rounded-full hover:bg-white/10 transition-all">
                ← All Services
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>
