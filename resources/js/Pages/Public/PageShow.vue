<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'
import PublicLayout from '@/Layouts/PublicLayout.vue'

const props = defineProps({
  page: { type: Object, required: true },
  isPreview: { type: Boolean, default: false }
})

// Contact form state
const form = ref({ name: '', email: '', phone: '', subject: '', message: '' })
const submitting = ref(false)
const formStatus = ref('')
const formMessage = ref('')

const isContactPage = props.page.slug === 'contact-us' || props.page.slug === 'contact'

const submitContact = async () => {
  submitting.value = true
  formStatus.value = ''
  try {
    const res = await axios.post('/contact', form.value)
    if (res.data.success !== false) {
      formStatus.value = 'success'
      formMessage.value = 'Thank you! Your message has been sent. We\'ll reply within 24 hours.'
      form.value = { name: '', email: '', phone: '', subject: '', message: '' }
    } else {
      throw new Error()
    }
  } catch (err) {
    formStatus.value = 'error'
    formMessage.value = err.response?.data?.message || 'Failed to send. Please email us directly.'
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <Head>
    <title>{{ page.seo?.meta_title || page.title }} — RankQuill</title>
    <meta name="description" :content="page.seo?.meta_description || ''" />
    <meta v-if="page.seo?.robots" name="robots" :content="page.seo.robots" />
  </Head>

  <PublicLayout>
    <!-- Preview Banner -->
    <div v-if="isPreview" class="bg-amber-500 text-amber-950 text-center text-sm font-semibold py-2.5">
      ⚠ You are viewing a DRAFT preview. This page is not live yet.
    </div>

    <!-- Dynamic Sections -->
    <div v-for="(section, idx) in page.content" :key="idx">

      <!-- ====== HERO SECTION ====== -->
      <section v-if="section.type === 'hero'" class="relative overflow-hidden bg-[#070b14] py-28 sm:py-36">
        <div class="absolute inset-0 hero-grid opacity-60"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="section-wrapper relative z-10">
          <div class="max-w-4xl mx-auto text-center space-y-6">
            <!-- Breadcrumb -->
            <div class="flex items-center justify-center gap-2 text-sm text-slate-500 mb-4">
              <Link href="/" class="hover:text-slate-300 transition-colors">Home</Link>
              <span>/</span>
              <span class="text-slate-300">{{ page.title }}</span>
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight">
              {{ section.data.title }}
            </h1>
            <p v-if="section.data.subtitle" class="text-slate-400 text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
              {{ section.data.subtitle }}
            </p>
            <div v-if="section.data.primary_cta_url" class="flex flex-wrap gap-4 justify-center pt-2">
              <a :href="section.data.primary_cta_url" class="btn-primary px-8 py-4 text-base">
                {{ section.data.primary_cta_text || 'Get Started' }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- ====== FEATURES / VALUES SECTION ====== -->
      <section v-else-if="section.type === 'features'" class="py-24 bg-white dark:bg-slate-950">
        <div class="section-wrapper">
          <div v-if="section.data.title" class="section-header">
            <h2 class="section-title">{{ section.data.title }}</h2>
            <p v-if="section.data.subtitle" class="section-subtitle">{{ section.data.subtitle }}</p>
          </div>
          <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="(item, i) in section.data.items" :key="i"
              class="group bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 card-hover relative overflow-hidden">
              <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
              <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-5">
                <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 13l4 4L19 7"/></svg>
              </div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ item.title }}</h3>
              <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ item.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ====== TEAM SECTION ====== -->
      <section v-else-if="section.type === 'team'" class="py-24 bg-slate-50 dark:bg-[#070b14] relative overflow-hidden">
        <div class="absolute inset-0 hero-grid opacity-30"></div>
        <div class="section-wrapper relative">
          <div class="section-header">
            <span class="section-label">Our People</span>
            <h2 class="section-title">{{ section.data.title || 'Meet The Team' }}</h2>
          </div>
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="member in section.data.resolved_items" :key="member.id"
              class="group text-center">
              <div class="relative w-48 h-48 mx-auto mb-5 rounded-3xl overflow-hidden border-2 border-slate-200 dark:border-slate-800 group-hover:border-indigo-500/40 transition-all duration-300 shadow-lg bg-slate-100 dark:bg-slate-900">
                <img v-if="member.avatar_path" :src="member.avatar_path" :alt="member.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                <div v-else class="w-full h-full flex items-center justify-center text-4xl font-black bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                  {{ member.name?.charAt(0) || '?' }}
                </div>
              </div>
              <h4 class="font-bold text-lg text-slate-900 dark:text-white">{{ member.name }}</h4>
              <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">{{ member.position }}</p>
              <p v-if="member.bio" class="text-xs text-slate-500 dark:text-slate-500 mt-2 px-4 leading-relaxed">{{ member.bio }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ====== TESTIMONIALS SECTION ====== -->
      <section v-else-if="section.type === 'testimonials'" class="py-24 bg-white dark:bg-slate-950">
        <div class="section-wrapper">
          <div class="section-header">
            <span class="section-label">What They Say</span>
            <h2 class="section-title">{{ section.data.title || 'Client Success Stories' }}</h2>
          </div>
          <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="t in section.data.resolved_items" :key="t.id"
              class="group bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 card-hover flex flex-col justify-between overflow-hidden relative">
              <div class="absolute top-4 right-4 text-slate-100 dark:text-slate-800 opacity-60">
                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
              </div>
              <div class="flex gap-1 mb-4">
                <svg v-for="s in 5" :key="s" class="w-4 h-4" :class="s <= (t.rating || 5) ? 'text-amber-400 fill-current' : 'text-slate-200 dark:text-slate-700'" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              </div>
              <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed italic flex-1 mb-5">"{{ t.testimonial }}"</p>
              <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <div v-if="t.avatar_path" class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-indigo-500/20">
                  <img :src="t.avatar_path" :alt="t.client_name" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                  {{ t.client_name?.charAt(0) || '?' }}
                </div>
                <div>
                  <div class="text-sm font-bold text-slate-900 dark:text-white">{{ t.client_name }}</div>
                  <div class="text-xs text-slate-400">{{ t.client_position }}<span v-if="t.client_company">, {{ t.client_company }}</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ====== FAQs SECTION ====== -->
      <section v-else-if="section.type === 'faqs'" class="py-24 bg-slate-50 dark:bg-[#070b14]">
        <div class="section-wrapper">
          <div class="section-header">
            <span class="section-label">FAQ</span>
            <h2 class="section-title">{{ section.data.title || 'Frequently Asked Questions' }}</h2>
          </div>
          <div class="max-w-3xl mx-auto space-y-4">
            <details v-for="faq in section.data.resolved_items" :key="faq.id"
              class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden hover:border-indigo-500/30 transition-colors">
              <summary class="flex items-center justify-between gap-4 p-6 cursor-pointer list-none">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ faq.question }}</h3>
                <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="px-6 pb-6">
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">{{ faq.answer }}</p>
              </div>
            </details>
          </div>
        </div>
      </section>

      <!-- ====== CTA SECTION ====== -->
      <section v-else-if="section.type === 'cta'" class="py-24 bg-white dark:bg-slate-950">
        <div class="section-wrapper">
          <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-12 sm:p-20 overflow-hidden text-center">
            <div class="absolute inset-0 hero-grid opacity-20"></div>
            <div class="relative z-10 space-y-6 max-w-2xl mx-auto">
              <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tight">{{ section.data.title }}</h2>
              <p v-if="section.data.subtitle" class="text-indigo-100 text-lg">{{ section.data.subtitle }}</p>
              <a v-if="section.data.button_url" :href="section.data.button_url" class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-8 py-4 rounded-full hover:bg-indigo-50 transition-all hover:scale-[1.02] shadow-xl text-base">
                {{ section.data.button_text }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- ====== RICH CONTENT SECTION ====== -->
      <section v-else-if="section.type === 'content'" class="py-16 bg-white dark:bg-slate-950">
        <div class="section-wrapper">
          <div class="max-w-3xl mx-auto prose prose-slate dark:prose-invert prose-lg prose-headings:font-black prose-a:text-indigo-600 dark:prose-a:text-indigo-400" v-html="section.data.body"></div>
        </div>
      </section>

    </div>

    <!-- ====================================================
         CONTACT FORM (shown on contact page)
    ===================================================== -->
    <section v-if="isContactPage" class="py-24 bg-slate-50 dark:bg-[#070b14] relative overflow-hidden">
      <div class="absolute inset-0 hero-grid opacity-30"></div>
      <div class="section-wrapper relative">
        <div class="grid lg:grid-cols-2 gap-16 items-start">

          <!-- Contact Info -->
          <div class="space-y-8">
            <div class="space-y-4">
              <span class="section-label">Get In Touch</span>
              <h2 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight">Let's Build<br/><span class="gradient-text">Something Great</span></h2>
              <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                Whether you need a full digital transformation or a quick audit, our team is ready to help. Tell us about your project.
              </p>
            </div>

            <div class="space-y-4">
              <div v-for="item in [
                { icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', label: 'Email Us', val: 'hello@rankquill.com', href: 'mailto:hello@rankquill.com' },
                { icon: 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', label: 'Call Us', val: '+1 (800) RANKQUILL', href: 'tel:+18007265784' },
                { icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', label: 'Location', val: 'Remote-First · Global HQ: San Francisco, CA', href: null },
              ]" :key="item.label" class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="item.icon"/></svg>
                </div>
                <div>
                  <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-0.5">{{ item.label }}</div>
                  <a v-if="item.href" :href="item.href" class="text-slate-700 dark:text-slate-300 font-semibold text-sm hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ item.val }}</a>
                  <p v-else class="text-slate-700 dark:text-slate-300 font-semibold text-sm">{{ item.val }}</p>
                </div>
              </div>
            </div>

            <!-- Response time badge -->
            <div class="flex items-center gap-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl p-4">
              <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
              <p class="text-sm text-emerald-700 dark:text-emerald-400 font-medium">Average response time: <strong>under 2 hours</strong> on business days.</p>
            </div>
          </div>

          <!-- Contact Form Card -->
          <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl shadow-slate-200/20 dark:shadow-slate-950/50">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Send Us a Message</h3>

            <form @submit.prevent="submitContact" class="space-y-4">
              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="form-label">Full Name *</label>
                  <input v-model="form.name" type="text" required placeholder="John Doe" class="form-input" />
                </div>
                <div>
                  <label class="form-label">Email Address *</label>
                  <input v-model="form.email" type="email" required placeholder="john@company.com" class="form-input" />
                </div>
              </div>
              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="form-label">Phone Number</label>
                  <input v-model="form.phone" type="tel" placeholder="+1 (555) 000-0000" class="form-input" />
                </div>
                <div>
                  <label class="form-label">Subject *</label>
                  <select v-model="form.subject" required class="form-input">
                    <option value="">Select a topic...</option>
                    <option>Web Development Project</option>
                    <option>SEO Consultation</option>
                    <option>App Development</option>
                    <option>PPC / Advertising</option>
                    <option>AI Solutions</option>
                    <option>General Inquiry</option>
                  </select>
                </div>
              </div>
              <div>
                <label class="form-label">Your Message *</label>
                <textarea v-model="form.message" required rows="5" placeholder="Tell us about your project, goals, timeline, and budget..." class="form-input resize-none"></textarea>
              </div>

              <!-- Submit -->
              <button type="submit" :disabled="submitting" class="btn-primary w-full py-4 text-base justify-center">
                <svg v-if="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                {{ submitting ? 'Sending Message...' : 'Send Message →' }}
              </button>

              <!-- Status -->
              <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
                <div v-if="formMessage" :class="formStatus === 'success' ? 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-500/10 border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400'" class="flex items-center gap-2 p-4 rounded-xl border text-sm">
                  <svg v-if="formStatus === 'success'" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  <svg v-else class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ formMessage }}
                </div>
              </transition>
            </form>
          </div>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>
