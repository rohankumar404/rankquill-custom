<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  service: {
    type: Object,
    required: true
  }
})

const schemaJson = props.service.seo?.schema_markup 
  ? JSON.stringify(props.service.seo.schema_markup) 
  : null
</script>

<template>
  <Head>
    <title>{{ service.seo?.meta_title || service.title }}</title>
    <meta name="description" :content="service.seo?.meta_description || service.tagline || ''" />
    <meta name="keywords" :content="service.seo?.meta_keywords || ''" />
    <meta name="robots" :content="service.seo?.robots || 'index, follow'" />
    <link rel="canonical" :href="service.seo?.canonical_url || '#'" />
    
    <!-- Open Graph -->
    <meta property="og:title" :content="service.seo?.og_title || service.title" />
    <meta property="og:description" :content="service.seo?.og_description || service.tagline || ''" />
    <meta property="og:image" :content="service.seo?.og_image_url || ''" />
    
    <!-- JSON-LD Schema Markup -->
    <script v-if="schemaJson" type="application/ld+json" v-html="schemaJson"></script>
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

    <!-- Hero Header -->
    <div class="border-b border-slate-900/60 bg-slate-900/10 py-20 relative overflow-hidden">
      <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-3xl"></div>
      <div class="max-w-5xl mx-auto px-6 space-y-6 text-center">
        <span class="inline-block bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-bold uppercase tracking-widest py-1.5 px-4 rounded-full">
          {{ service.tagline || 'Service Details' }}
        </span>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
          {{ service.title }}
        </h1>
        <p class="text-lg md:text-xl text-slate-400 font-light leading-relaxed max-w-3xl mx-auto">
          {{ service.description }}
        </p>
        <div v-if="service.price_starting_at" class="pt-2 text-slate-500 text-sm">
          Plans starting from <span class="text-white font-bold text-lg">${{ parseFloat(service.price_starting_at).toLocaleString() }}</span>
        </div>
      </div>
    </div>

    <!-- Render Dynamic Builder Content Blocks -->
    <div v-if="service.content && service.content.length > 0" class="py-8">
      <div v-for="(section, idx) in service.content" :key="idx" class="relative">
        
        <!-- Hero Layout -->
        <section v-if="section.type === 'hero'" class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-12 items-center">
          <div class="space-y-6">
            <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-white leading-tight">
              {{ section.data.title }}
            </h2>
            <p class="text-slate-400 font-light leading-relaxed">
              {{ section.data.subtitle }}
            </p>
            <div class="pt-2">
              <a :href="section.data.primary_cta_url" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-8 py-3.5 rounded-full transition-all">
                {{ section.data.primary_cta_text || 'Learn More' }}
              </a>
            </div>
          </div>
          <div v-if="section.data.image" class="relative">
            <img :src="section.data.image" alt="Section Media" class="rounded-2xl border border-slate-800 shadow-2xl object-cover w-full aspect-video" />
          </div>
        </section>

        <!-- CTA Layout -->
        <section v-else-if="section.type === 'cta'" class="max-w-5xl mx-auto px-6 py-12 my-12 bg-gradient-to-br from-indigo-950/40 to-slate-900 border border-slate-900 rounded-3xl p-10 text-center">
          <div class="space-y-4">
            <h3 class="text-2xl md:text-4xl font-bold text-white">{{ section.data.title }}</h3>
            <p class="text-slate-400 font-light max-w-xl mx-auto">{{ section.data.subtitle }}</p>
            <div class="pt-4">
              <a :href="section.data.button_url" class="inline-block bg-white hover:bg-slate-200 text-slate-950 font-semibold px-6 py-3 rounded-xl transition-all">
                {{ section.data.button_text }}
              </a>
            </div>
          </div>
        </section>

        <!-- Features Layout -->
        <section v-else-if="section.type === 'features'" class="max-w-7xl mx-auto px-6 py-16">
          <div class="grid md:grid-cols-3 gap-8">
            <div v-for="(item, i) in section.data.items" :key="i" class="border border-slate-900 p-8 rounded-2xl bg-slate-950/40 hover:border-slate-800 transition-colors">
              <h4 class="font-bold text-lg text-white mb-2">{{ item.title }}</h4>
              <p class="text-slate-400 font-light text-xs leading-relaxed">{{ item.description }}</p>
            </div>
          </div>
        </section>

        <!-- Dynamic FAQs block -->
        <section v-else-if="section.type === 'faqs'" class="max-w-4xl mx-auto px-6 py-12">
          <h3 class="text-2xl font-bold text-center mb-8 text-white">{{ section.data.title || 'FAQ' }}</h3>
          <div class="space-y-4">
            <div v-for="faq in section.data.resolved_items" :key="faq.id" class="border border-slate-900 bg-slate-900/20 p-6 rounded-xl">
              <h4 class="font-bold text-white mb-2">{{ faq.question }}</h4>
              <p class="text-slate-400 font-light leading-relaxed text-sm">{{ faq.answer }}</p>
            </div>
          </div>
        </section>

        <!-- Dynamic Testimonials block -->
        <section v-else-if="section.type === 'testimonials'" class="max-w-7xl mx-auto px-6 py-12">
          <h3 class="text-2xl font-bold text-center mb-8 text-white">{{ section.data.title || 'Testimonials' }}</h3>
          <div class="grid md:grid-cols-3 gap-8">
            <div v-for="t in section.data.resolved_items" :key="t.id" class="border border-slate-900 p-6 rounded-xl flex flex-col justify-between">
              <p class="text-slate-300 italic text-sm mb-4">"{{ t.testimonial }}"</p>
              <div class="text-xs">
                <div class="font-semibold text-white">{{ t.client_name }}</div>
                <div class="text-slate-500">{{ t.client_position }}, {{ t.client_company }}</div>
              </div>
            </div>
          </div>
        </section>

        <!-- Dynamic Team block -->
        <section v-else-if="section.type === 'team'" class="max-w-7xl mx-auto px-6 py-12">
          <h3 class="text-2xl font-bold text-center mb-8 text-white">{{ section.data.title || 'Our Experts' }}</h3>
          <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-8">
            <div v-for="m in section.data.resolved_items" :key="m.id" class="text-center">
              <div class="w-20 h-20 rounded-full mx-auto overflow-hidden border border-slate-800 bg-slate-900 mb-3">
                <img v-if="m.avatar_path" :src="m.avatar_path" alt="Avatar" class="object-cover w-full h-full" />
              </div>
              <h4 class="font-bold text-sm text-white">{{ m.name }}</h4>
              <p class="text-xs text-indigo-400">{{ m.position }}</p>
            </div>
          </div>
        </section>

        <!-- Content Layout -->
        <section v-else-if="section.type === 'content'" class="max-w-3xl mx-auto px-6 py-12 prose prose-invert prose-indigo">
          <div v-html="section.data.body"></div>
        </section>

      </div>
    </div>

    <!-- Pricing Comparison Plans Matrix -->
    <section v-if="service.pricing && service.pricing.length > 0" class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-900">
      <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
        <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight">Flexible Pricing Plans</h2>
        <p class="text-slate-400 font-light leading-relaxed text-sm">
          Select a packaging plan optimized for your startup sizing or corporate integrations.
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <div 
          v-for="(plan, idx) in service.pricing" 
          :key="idx" 
          class="border p-8 rounded-2xl flex flex-col justify-between hover:scale-[1.01] transition-all duration-300"
          :class="idx === 1 ? 'border-indigo-500 bg-indigo-950/10 shadow-xl shadow-indigo-600/5' : 'border-slate-850 bg-slate-900/10'"
        >
          <div class="space-y-6">
            <div class="space-y-2">
              <h4 class="text-lg font-bold text-slate-300 uppercase tracking-wider">{{ plan.name }}</h4>
              <div class="flex items-baseline gap-1 text-white">
                <span class="text-4xl font-extrabold">${{ parseFloat(plan.price).toLocaleString() }}</span>
                <span class="text-xs text-slate-500 font-normal">/ {{ plan.interval || 'month' }}</span>
              </div>
              <p class="text-xs text-slate-400 font-light pt-2 leading-relaxed">
                {{ plan.description }}
              </p>
            </div>

            <!-- Plan features checklist -->
            <ul v-if="plan.features && plan.features.length > 0" class="space-y-3 pt-6 border-t border-slate-900/60">
              <li v-for="(feat, fIdx) in plan.features" :key="fIdx" class="flex items-center gap-2 text-xs text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ feat }}</span>
              </li>
            </ul>
          </div>

          <div class="pt-8">
            <button 
              class="w-full text-center py-3 rounded-xl text-sm font-semibold transition-all duration-300"
              :class="idx === 1 ? 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/20' : 'bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-850'"
            >
              Get Started
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Polymorphic FAQs (fallback if not in page blocks) -->
    <section v-if="(!service.content || service.content.every(s => s.type !== 'faqs')) && service.faqs && service.faqs.length > 0" class="max-w-4xl mx-auto px-6 py-20 border-t border-slate-900">
      <h2 class="text-3xl font-extrabold text-center mb-12 text-white">Frequently Asked Questions</h2>
      <div class="space-y-4">
        <div v-for="faq in service.faqs" :key="faq.id" class="border border-slate-900 bg-slate-900/20 p-6 rounded-2xl">
          <h4 class="font-bold text-white mb-2">{{ faq.question }}</h4>
          <p class="text-slate-400 font-light leading-relaxed text-sm">{{ faq.answer }}</p>
        </div>
      </div>
    </section>

    <!-- Polymorphic Testimonials (fallback if not in page blocks) -->
    <section v-if="(!service.content || service.content.every(s => s.type !== 'testimonials')) && service.testimonials && service.testimonials.length > 0" class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-900">
      <h2 class="text-3xl font-extrabold text-center mb-12 text-white">Client Feedback</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <div v-for="t in service.testimonials" :key="t.id" class="border border-slate-900 bg-slate-900/10 p-6 rounded-2xl flex flex-col justify-between">
          <p class="text-slate-300 italic text-sm mb-4">"{{ t.testimonial }}"</p>
          <div class="text-xs">
            <div class="font-semibold text-white">{{ t.client_name }}</div>
            <div class="text-slate-500">{{ t.client_position }}, {{ t.client_company }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-12 text-center text-sm text-slate-500">
      <div class="max-w-7xl mx-auto px-6">
        &copy; 2026 RankQuill Premium Engine. All rights reserved.
      </div>
    </footer>
  </div>
</template>
