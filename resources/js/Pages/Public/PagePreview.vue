<script setup>
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps({
  page: {
    type: Object,
    required: true
  },
  isPreview: {
    type: Boolean,
    default: false
  }
})
</script>

<template>
  <Head>
    <title>{{ page.seo?.meta_title || page.title }} - Preview Mode</title>
    <meta name="description" :content="page.seo?.meta_description || ''" />
    <meta name="keywords" :content="page.seo?.meta_keywords || ''" />
    <meta name="robots" content="noindex, nofollow" />
  </Head>

  <PublicLayout>
    <!-- Preview Banner -->
    <div v-if="isPreview" class="bg-gradient-to-r from-amber-600 to-amber-500 text-slate-950 font-semibold px-4 py-2.5 text-center text-sm shadow-md flex items-center justify-center gap-2 relative z-20">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
      </svg>
      You are viewing this page in secure Draft Preview Mode. Changes are not live yet.
    </div>

    <!-- Dynamic Builder Sections Renderer -->
    <main class="py-12">
      <div v-for="(section, idx) in page.content" :key="idx" class="relative">
        
        <!-- Hero Section -->
        <section v-if="section.type === 'hero'" class="max-w-7xl mx-auto px-6 py-20 md:py-32 grid md:grid-cols-2 gap-12 items-center">
          <div class="space-y-6">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight text-slate-955 dark:text-white">
              {{ section.data.title }}
            </h1>
            <p class="text-lg md:text-xl text-slate-500 dark:text-slate-400 font-light leading-relaxed">
              {{ section.data.subtitle }}
            </p>
            <div class="pt-4">
              <a :href="section.data.primary_cta_url" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-8 py-4 rounded-full transition-all duration-300 shadow-xl shadow-indigo-600/30 hover:scale-[1.02]">
                {{ section.data.primary_cta_text || 'Learn More' }}
              </a>
            </div>
          </div>
          <div v-if="section.data.image" class="relative">
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-3xl blur-3xl opacity-20 animate-pulse"></div>
            <img :src="section.data.image" alt="Hero Image" class="relative rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl object-cover w-full aspect-video" />
          </div>
        </section>

        <!-- CTA Section -->
        <section v-else-if="section.type === 'cta'" class="max-w-5xl mx-auto px-6 py-16 my-16 bg-gradient-to-br from-indigo-50 dark:from-indigo-950/80 to-purple-50 dark:to-purple-950/40 border border-slate-200 dark:border-slate-800/80 rounded-3xl p-12 text-center relative overflow-hidden shadow-2xl">
          <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-500 rounded-full blur-3xl opacity-10"></div>
          <div class="relative z-10 space-y-6">
            <h2 class="text-3xl md:text-5xl font-bold text-slate-955 dark:text-white tracking-tight">{{ section.data.title }}</h2>
            <p class="text-slate-600 dark:text-slate-300 max-w-2xl mx-auto font-light leading-relaxed">{{ section.data.subtitle }}</p>
            <div class="pt-4">
              <a :href="section.data.button_url" class="inline-block bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:hover:bg-slate-100 dark:text-slate-950 font-semibold px-8 py-4 rounded-full transition-all duration-300 shadow-lg shadow-white/10 hover:scale-[1.02]">
                {{ section.data.button_text }}
              </a>
            </div>
          </div>
        </section>

        <!-- FAQs Section -->
        <section v-else-if="section.type === 'faqs'" class="max-w-4xl mx-auto px-6 py-16">
          <h2 class="text-3xl font-bold text-center mb-12 text-slate-950 dark:text-white">{{ section.data.title || 'Frequently Asked Questions' }}</h2>
          <div class="space-y-4">
            <div v-for="faq in section.data.resolved_items" :key="faq.id" class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 rounded-2xl p-6 hover:border-slate-300 dark:hover:border-slate-700/80 transition-colors">
              <h3 class="text-lg font-semibold text-slate-955 dark:text-white mb-2">{{ faq.question }}</h3>
              <p class="text-slate-500 dark:text-slate-400 font-light leading-relaxed">{{ faq.answer }}</p>
            </div>
          </div>
        </section>

        <!-- Testimonials Section -->
        <section v-else-if="section.type === 'testimonials'" class="max-w-7xl mx-auto px-6 py-16">
          <h2 class="text-3xl font-bold text-center mb-12 text-slate-955 dark:text-white">{{ section.data.title || 'Client Success Stories' }}</h2>
          <div class="grid md:grid-cols-3 gap-8">
            <div v-for="t in section.data.resolved_items" :key="t.id" class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/20 p-8 rounded-2xl flex flex-col justify-between hover:border-indigo-500/30 transition-all duration-300">
              <p class="text-slate-600 dark:text-slate-300 italic mb-6">"{{ t.testimonial }}"</p>
              <div class="flex items-center gap-4">
                <div v-if="t.avatar_path" class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700">
                  <img :src="t.avatar_path" alt="Client Avatar" class="object-cover w-full h-full" />
                </div>
                <div>
                  <h4 class="font-semibold text-slate-950 dark:text-white">{{ t.client_name }}</h4>
                  <p class="text-xs text-slate-400 dark:text-slate-500">{{ t.client_position }}, {{ t.client_company }}</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Team Section -->
        <section v-else-if="section.type === 'team'" class="max-w-7xl mx-auto px-6 py-16">
          <h2 class="text-3xl font-bold text-center mb-12 text-slate-950 dark:text-white">{{ section.data.title || 'Meet Our Experts' }}</h2>
          <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-8">
            <div v-for="m in section.data.resolved_items" :key="m.id" class="group text-center">
              <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 aspect-square mb-4 bg-slate-50 dark:bg-slate-900">
                <img v-if="m.avatar_path" :src="m.avatar_path" alt="Team Avatar" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300" />
              </div>
              <h4 class="font-bold text-lg text-slate-950 dark:text-white">{{ m.name }}</h4>
              <p class="text-sm text-indigo-650 dark:text-indigo-400">{{ m.position }}</p>
            </div>
          </div>
        </section>

        <!-- Features Section -->
        <section v-else-if="section.type === 'features'" class="max-w-7xl mx-auto px-6 py-16">
          <div class="grid md:grid-cols-3 gap-8">
            <div v-for="(item, i) in section.data.items" :key="i" class="border border-slate-200 dark:border-slate-800 p-8 rounded-2xl hover:border-slate-350 dark:hover:border-slate-700/80 bg-white dark:bg-slate-900/30 transition-colors">
              <h3 class="font-bold text-xl text-slate-955 dark:text-white mb-2">{{ item.title }}</h3>
              <p class="text-slate-500 dark:text-slate-400 font-light text-sm leading-relaxed">{{ item.description }}</p>
            </div>
          </div>
        </section>

        <!-- Rich Text Content Section -->
        <section v-else-if="section.type === 'content'" class="max-w-3xl mx-auto px-6 py-16 prose dark:prose-invert prose-indigo">
          <div v-html="section.data.body"></div>
        </section>

      </div>
    </main>
  </PublicLayout>
</template>
