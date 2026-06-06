<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  service: {
    type: Object,
    default: null
  },
  faqs: {
    type: Array,
    required: true
  },
  testimonials: {
    type: Array,
    required: true
  }
})

const isEditing = !!props.service
const activeTab = ref('general') // 'general', 'content', 'seo'

const form = useForm({
  title: props.service?.title || '',
  slug: props.service?.slug || '',
  tagline: props.service?.tagline || '',
  description: props.service?.description || '',
  features: props.service?.features || [],
  pricing: props.service?.pricing || [],
  price_starting_at: props.service?.price_starting_at || '',
  status: props.service?.status || 'active',
  content: props.service?.content || [],
  seo: {
    meta_title: props.service?.seo?.meta_title || '',
    meta_description: props.service?.seo?.meta_description || '',
    meta_keywords: props.service?.seo?.meta_keywords || '',
    robots: props.service?.seo?.robots || 'index, follow',
    canonical_url: props.service?.seo?.canonical_url || '',
    og_title: props.service?.seo?.og_title || '',
    og_description: props.service?.seo?.og_description || '',
    og_image_url: props.service?.seo?.og_image_url || '',
    twitter_card: props.service?.seo?.twitter_card || 'summary_large_image',
    twitter_title: props.service?.seo?.twitter_title || '',
    twitter_description: props.service?.seo?.twitter_description || '',
    twitter_image: props.service?.seo?.twitter_image || ''
  }
})

// General features checklist helpers
const addGeneralFeature = () => {
  form.features.push('')
}
const removeGeneralFeature = (idx) => {
  form.features.splice(idx, 1)
}

// Pricing tiers helpers
const addPricingPlan = () => {
  form.pricing.push({
    name: '',
    price: '',
    interval: 'one-time',
    description: '',
    features: []
  })
}
const removePricingPlan = (idx) => {
  form.pricing.splice(idx, 1)
}
const addPlanFeature = (planIdx) => {
  if (!form.pricing[planIdx].features) {
    form.pricing[planIdx].features = []
  }
  form.pricing[planIdx].features.push('')
}
const removePlanFeature = (planIdx, fIdx) => {
  form.pricing[planIdx].features.splice(fIdx, 1)
}

// Page builder section types helper
const sectionTypes = [
  { type: 'hero', label: 'Hero Banner Section', defaultData: { title: '', subtitle: '', primary_cta_text: '', primary_cta_url: '', image: '' } },
  { type: 'features', label: 'Service Features Grid', defaultData: { title: '', items: [] } },
  { type: 'testimonials', label: 'Client Testimonials Slider', defaultData: { title: 'What Our Clients Say', testimonial_ids: [] } },
  { type: 'faqs', label: 'Accordions FAQ Section', defaultData: { title: 'Service FAQs', faq_ids: [] } },
  { type: 'content', label: 'Rich Body Content', defaultData: { body: '' } },
  { type: 'cta', label: 'Call To Action Banner', defaultData: { title: 'Propel your business forward', subtitle: '', button_text: 'Get Started', button_url: '' } }
]

const addSection = (type) => {
  const target = sectionTypes.find(s => s.type === type)
  if (target) {
    form.content.push({
      type: type,
      data: JSON.parse(JSON.stringify(target.defaultData))
    })
  }
}

const removeSection = (index) => {
  form.content.splice(index, 1)
}

const moveSectionUp = (index) => {
  if (index > 0) {
    const temp = form.content[index]
    form.content[index] = form.content[index - 1]
    form.content[index - 1] = temp
  }
}

const moveSectionDown = (index) => {
  if (index < form.content.length - 1) {
    const temp = form.content[index]
    form.content[index] = form.content[index + 1]
    form.content[index + 1] = temp
  }
}

const addFeatureItem = (sectionIdx) => {
  if (!form.content[sectionIdx].data.items) {
    form.content[sectionIdx].data.items = []
  }
  form.content[sectionIdx].data.items.push({ title: '', description: '' })
}

const removeFeatureItem = (sectionIdx, itemIdx) => {
  form.content[sectionIdx].data.items.splice(itemIdx, 1)
}

const toggleIdSelection = (array, id) => {
  const index = array.indexOf(id)
  if (index > -1) {
    array.splice(index, 1)
  } else {
    array.push(id)
  }
}

const submit = () => {
  if (isEditing) {
    form.put(route('admin.services.update', props.service.id))
  } else {
    form.post(route('admin.services.store'))
  }
}
</script>

<template>
  <Head :title="isEditing ? 'Edit Service - Admin' : 'Add New Service - Admin'" />

  <AdminLayout>
    <form @submit.prevent="submit" class="space-y-6 max-w-6xl mx-auto pb-16">
      
      <!-- Top Title and Status Bar -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-900 border border-slate-800/80 p-5 rounded-2xl">
        <div class="flex-1 w-full">
          <div class="text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-1.5">
            {{ isEditing ? 'Edit Service Option' : 'Register New Agency Service' }}
          </div>
          <input 
            v-model="form.title" 
            type="text" 
            placeholder="Service Title (e.g. Enterprise SEO Consulting)" 
            class="bg-transparent border-0 border-b border-transparent focus:border-indigo-500 focus:ring-0 text-xl md:text-2xl font-black text-white p-0 pb-1 w-full placeholder-slate-600 focus:outline-none"
            required
          />
          <span v-if="form.errors.title" class="text-xs text-rose-500 block mt-1">{{ form.errors.title }}</span>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto justify-end">
          <div>
            <select 
              v-model="form.status" 
              class="bg-slate-950 border border-slate-800 text-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500"
            >
              <option value="active">Active (Live)</option>
              <option value="inactive">Inactive (Private)</option>
            </select>
          </div>
          
          <button 
            type="submit" 
            :disabled="form.processing"
            class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-indigo-600/10 disabled:opacity-50"
          >
            {{ form.processing ? 'Saving...' : 'Save Service' }}
          </button>
        </div>
      </div>

      <!-- Tab Navigation and Configuration -->
      <div class="grid md:grid-cols-3 gap-6">
        <!-- Sidebar Metadata -->
        <div class="md:col-span-1 space-y-6">
          <!-- Slug & Price settings -->
          <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400">Service Route & Pricing</h3>
            
            <!-- Slug -->
            <div>
              <label class="admin-label">Slug (URL Path)</label>
              <input 
                v-model="form.slug" 
                type="text" 
                placeholder="search-engine-optimization" 
                class="admin-input"
              />
              <p class="text-[10px] text-slate-500 mt-1">Leave blank to auto-slugify the title.</p>
              <span v-if="form.errors.slug" class="text-xs text-rose-500 block mt-1">{{ form.errors.slug }}</span>
            </div>

            <!-- Price starting at -->
            <div>
              <label class="admin-label">Base Starting Price ($)</label>
              <input v-model="form.price_starting_at" type="number" step="0.01" placeholder="e.g. 1500" class="admin-input" />
              <p class="text-[10px] text-slate-500 mt-1">Leave blank for custom proposal quote options.</p>
              <span v-if="form.errors.price_starting_at" class="text-xs text-rose-500 block mt-1">{{ form.errors.price_starting_at }}</span>
            </div>
          </div>

          <!-- Section Picker / Quick Add (Only when content tab active) -->
          <div v-if="activeTab === 'content'" class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400">Add Landing Blocks</h3>
            <p class="text-[10px] text-slate-500 leading-normal mb-2">Build a landing page layout for this service by adding blocks.</p>
            
            <div class="grid grid-cols-1 gap-2">
              <button 
                v-for="section in sectionTypes" 
                :key="section.type"
                type="button"
                @click="addSection(section.type)"
                class="flex items-center justify-between text-left p-3 rounded-xl bg-slate-950 border border-slate-800/60 hover:border-indigo-500/30 text-slate-300 hover:text-white transition-all text-xs group"
              >
                <span>{{ section.label }}</span>
                <span class="text-indigo-500 group-hover:translate-x-0.5 transition-transform font-bold text-sm">+</span>
              </button>
            </div>
          </div>

          <!-- Tab Selector -->
          <div class="bg-slate-900 border border-slate-800/80 p-3 rounded-2xl flex gap-1">
            <button 
              type="button" 
              @click="activeTab = 'general'" 
              class="flex-1 text-center py-2 text-xs font-semibold rounded-xl transition-all"
              :class="activeTab === 'general' ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:text-slate-200'"
            >
              General Tiers
            </button>
            <button 
              type="button" 
              @click="activeTab = 'content'" 
              class="flex-1 text-center py-2 text-xs font-semibold rounded-xl transition-all"
              :class="activeTab === 'content' ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:text-slate-200'"
            >
              Landing Page
            </button>
            <button 
              type="button" 
              @click="activeTab = 'seo'" 
              class="flex-1 text-center py-2 text-xs font-semibold rounded-xl transition-all"
              :class="activeTab === 'seo' ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:text-slate-200'"
            >
              SEO Meta
            </button>
          </div>
        </div>

        <!-- Main Form Fields -->
        <div class="md:col-span-2 space-y-6">
          
          <!-- GENERAL TIERS TAB -->
          <div v-if="activeTab === 'general'" class="space-y-6">
            <!-- Details Card -->
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
              <h3 class="text-xs font-bold uppercase tracking-widest text-slate-200">Basic Service Profile</h3>
              
              <div class="space-y-1.5">
                <label class="admin-label">Service Tagline</label>
                <input v-model="form.tagline" type="text" class="admin-input" placeholder="Boost search ranking through data-driven digital audits" />
                <span v-if="form.errors.tagline" class="text-xs text-rose-500 block mt-1">{{ form.errors.tagline }}</span>
              </div>

              <div class="space-y-1.5">
                <label class="admin-label">Brief Description</label>
                <textarea v-model="form.description" rows="3" class="admin-input" placeholder="Short description summarized for home grids..."></textarea>
                <span v-if="form.errors.description" class="text-xs text-rose-500 block mt-1">{{ form.errors.description }}</span>
              </div>
            </div>

            <!-- Global Bullet Features Card -->
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
              <div class="flex justify-between items-center">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-200">Core Deliverables</h3>
                <button type="button" @click="addGeneralFeature" class="text-[10px] text-indigo-400 font-bold hover:underline">+ Add Deliverable</button>
              </div>

              <div class="space-y-2">
                <div v-for="(feat, fIdx) in form.features" :key="fIdx" class="flex gap-2 items-center">
                  <input v-model="form.features[fIdx]" type="text" class="admin-input" placeholder="e.g. 24/7 Technical SEO Auditing Support" required />
                  <button type="button" @click="removeGeneralFeature(fIdx)" class="text-rose-500 hover:text-rose-400 text-xs px-2 py-1">
                    Delete
                  </button>
                </div>
                <div v-if="form.features.length === 0" class="text-center text-slate-650 text-xs italic py-4">No deliverables listed yet.</div>
              </div>
            </div>

            <!-- Pricing plans/tiers -->
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
              <div class="flex justify-between items-center">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-200">Tiered Pricing Plans</h3>
                <button type="button" @click="addPricingPlan" class="text-[10px] text-indigo-400 font-bold hover:underline">+ Add Pricing Plan</button>
              </div>

              <div class="space-y-6 divide-y divide-slate-800/80">
                <div v-if="form.pricing.length === 0" class="text-center text-slate-650 text-xs italic py-4">No pricing plans defined.</div>
                
                <div v-for="(plan, pIdx) in form.pricing" :key="pIdx" class="pt-6 first:pt-0 space-y-4">
                  <div class="flex justify-between items-start">
                    <span class="text-xs font-bold text-slate-350">Pricing Plan #{{ pIdx + 1 }}</span>
                    <button type="button" @click="removePricingPlan(pIdx)" class="text-xs text-rose-500 hover:text-rose-450 font-semibold">
                      Remove Tier
                    </button>
                  </div>

                  <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                      <label class="admin-label">Plan Name</label>
                      <input v-model="plan.name" type="text" class="admin-input" placeholder="Growth Plan" required />
                    </div>
                    <div>
                      <label class="admin-label">Plan Price ($)</label>
                      <input v-model="plan.price" type="number" step="0.01" class="admin-input" placeholder="2499" required />
                    </div>
                    <div>
                      <label class="admin-label">Interval</label>
                      <input v-model="plan.interval" type="text" class="admin-input" placeholder="monthly" />
                    </div>
                  </div>

                  <div class="space-y-1.5">
                    <label class="admin-label">Short Description</label>
                    <input v-model="plan.description" type="text" class="admin-input" placeholder="Perfect for scaling mid-market ecommerce brands..." />
                  </div>

                  <!-- Plan Specific Features -->
                  <div class="space-y-2">
                    <div class="flex justify-between items-center">
                      <span class="text-[10px] font-semibold text-slate-450 uppercase">Included in Plan Features</span>
                      <button type="button" @click="addPlanFeature(pIdx)" class="text-[9px] text-indigo-400 hover:underline">+ Add Plan Feature</button>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-2">
                      <div v-for="(pFeat, pfIdx) in plan.features" :key="pfIdx" class="flex gap-2 items-center bg-slate-950 p-1.5 rounded-lg border border-slate-850">
                        <input v-model="plan.features[pfIdx]" type="text" class="bg-transparent border-0 focus:ring-0 flex-1 text-xs text-slate-200 px-2 placeholder-slate-650" placeholder="e.g. 5 Keywords" required />
                        <button type="button" @click="removePlanFeature(pIdx, pfIdx)" class="text-rose-500 text-xs px-2">✕</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- LANDING PAGE BLOCKS TAB -->
          <div v-else-if="activeTab === 'content'" class="space-y-4">
            <div class="flex items-center justify-between">
              <h2 class="text-sm font-bold uppercase tracking-widest text-slate-500">Service Landing Blocks</h2>
              <span class="text-xs text-slate-600">{{ form.content.length }} blocks added</span>
            </div>

            <!-- Empty State -->
            <div v-if="form.content.length === 0" class="bg-slate-900/40 border border-dashed border-slate-800 rounded-3xl p-16 text-center text-slate-500">
              <svg xmlns="http://www.w3.org/2050/svg" class="h-10 w-10 mx-auto text-slate-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
              <h4 class="text-slate-300 font-semibold text-sm mb-1">Canvas is empty</h4>
              <p class="text-xs text-slate-600 max-w-sm mx-auto">Select landing blocks from the sidebar picker to begin building the dynamic landing page.</p>
            </div>

            <!-- Dynamic Sections Form -->
            <div 
              v-for="(section, idx) in form.content" 
              :key="idx" 
              class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg transition duration-200 hover:border-slate-855"
            >
              <!-- Section Header Control Bar -->
              <div class="bg-slate-950 px-5 py-3.5 border-b border-slate-800/80 flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="text-[10px] font-mono font-bold bg-slate-850 border border-slate-750 px-2 py-0.5 rounded text-indigo-400 uppercase">
                    {{ section.type }}
                  </span>
                  <span class="text-xs font-semibold text-slate-200">Block #{{ idx + 1 }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <!-- Up/Down control -->
                  <button type="button" @click="moveSectionUp(idx)" :disabled="idx === 0" class="p-1 rounded text-slate-500 hover:text-slate-200 hover:bg-slate-855 disabled:opacity-30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                  </button>
                  <button type="button" @click="moveSectionDown(idx)" :disabled="idx === form.content.length - 1" class="p-1 rounded text-slate-500 hover:text-slate-200 hover:bg-slate-855 disabled:opacity-30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  <div class="w-px h-4 bg-slate-805 mx-1"></div>
                  <!-- Delete block -->
                  <button type="button" @click="removeSection(idx)" class="p-1 rounded text-rose-500 hover:text-rose-400 hover:bg-rose-955/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Section Custom Form Details -->
              <div class="p-5 space-y-4 bg-slate-900/40">
                
                <!-- HERO SECTION EDITING -->
                <div v-if="section.type === 'hero'" class="grid sm:grid-cols-2 gap-4">
                  <div class="sm:col-span-2">
                    <label class="admin-label">Hero Banner Title</label>
                    <input v-model="section.data.title" type="text" class="admin-input" placeholder="Title" required />
                  </div>
                  <div class="sm:col-span-2">
                    <label class="admin-label">Subtitle Description</label>
                    <textarea v-model="section.data.subtitle" rows="2" class="admin-input" placeholder="Subtitle description"></textarea>
                  </div>
                  <div>
                    <label class="admin-label">Primary CTA Button Label</label>
                    <input v-model="section.data.primary_cta_text" type="text" class="admin-input" placeholder="Learn More" />
                  </div>
                  <div>
                    <label class="admin-label">Primary CTA URL</label>
                    <input v-model="section.data.primary_cta_url" type="text" class="admin-input" placeholder="/contact" />
                  </div>
                  <div class="sm:col-span-2">
                    <label class="admin-label">Illustration / Hero Image URL</label>
                    <input v-model="section.data.image" type="text" class="admin-input" placeholder="https://agency.com/images/seo-banner.jpg" />
                  </div>
                </div>

                <!-- FEATURES GRID EDITING -->
                <div v-else-if="section.type === 'features'" class="space-y-4">
                  <div>
                    <label class="admin-label">Section Header Title</label>
                    <input v-model="section.data.title" type="text" class="admin-input" placeholder="Why Choose Our SEO Services" />
                  </div>
                  
                  <div class="space-y-2">
                    <div class="flex justify-between items-center">
                      <span class="text-xs font-semibold text-slate-400">Features List Items</span>
                      <button type="button" @click="addFeatureItem(idx)" class="text-[10px] text-indigo-400 font-bold hover:underline">+ Add Feature Item</button>
                    </div>
                    
                    <div v-for="(item, iIdx) in section.data.items" :key="iIdx" class="bg-slate-955 p-4 border border-slate-805 rounded-xl space-y-3 relative">
                      <button type="button" @click="removeFeatureItem(idx, iIdx)" class="absolute top-2 right-2 text-rose-500 hover:text-rose-400 text-xs">
                        Remove
                      </button>
                      <div class="grid sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-1">
                          <label class="admin-label">Feature Title</label>
                          <input v-model="item.title" type="text" class="admin-input" placeholder="Quality Deliverable" required />
                        </div>
                        <div class="sm:col-span-2">
                          <label class="admin-label">Feature Description</label>
                          <input v-model="item.description" type="text" class="admin-input" placeholder="Describe the feature details..." required />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- TESTIMONIALS SELECTION EDITING -->
                <div v-else-if="section.type === 'testimonials'" class="space-y-3">
                  <div>
                    <label class="admin-label">Testimonial Section Title</label>
                    <input v-model="section.data.title" type="text" class="admin-input" placeholder="What Clients Say" />
                  </div>
                  <div>
                    <label class="admin-label">Select Testimonials to Include</label>
                    <div class="grid sm:grid-cols-2 gap-2 mt-2 max-h-48 overflow-y-auto p-3 bg-slate-950 rounded-xl border border-slate-800">
                      <label 
                        v-for="t in testimonials" 
                        :key="t.id"
                        class="flex items-center space-x-2 text-xs text-slate-300 p-1 hover:bg-slate-900 rounded cursor-pointer"
                      >
                        <input 
                          type="checkbox" 
                          :checked="section.data.testimonial_ids.includes(t.id)"
                          @change="toggleIdSelection(section.data.testimonial_ids, t.id)"
                          class="rounded bg-slate-955 border-slate-805 text-indigo-650 focus:ring-0" 
                        />
                        <span>{{ t.client_name }} - {{ t.client_company }}</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- FAQS SELECTION EDITING -->
                <div v-else-if="section.type === 'faqs'" class="space-y-3">
                  <div>
                    <label class="admin-label">FAQ Section Title</label>
                    <input v-model="section.data.title" type="text" class="admin-input" placeholder="Service FAQs" />
                  </div>
                  <div>
                    <label class="admin-label">Select FAQs to Include</label>
                    <div class="grid sm:grid-cols-2 gap-2 mt-2 max-h-48 overflow-y-auto p-3 bg-slate-950 rounded-xl border border-slate-800">
                      <label 
                        v-for="faq in faqs" 
                        :key="faq.id"
                        class="flex items-center space-x-2 text-xs text-slate-300 p-1 hover:bg-slate-900 rounded cursor-pointer"
                      >
                        <input 
                          type="checkbox" 
                          :checked="section.data.faq_ids.includes(faq.id)"
                          @change="toggleIdSelection(section.data.faq_ids, faq.id)"
                          class="rounded bg-slate-955 border-slate-805 text-indigo-650 focus:ring-0" 
                        />
                        <span class="truncate">{{ faq.question }}</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- RICH BODY CONTENT EDITING -->
                <div v-else-if="section.type === 'content'">
                  <label class="admin-label">Body Markdown/HTML Content</label>
                  <textarea 
                    v-model="section.data.body" 
                    rows="6" 
                    class="admin-input font-mono text-xs" 
                    placeholder="Enter formatted content here..."
                    required
                  ></textarea>
                </div>

                <!-- CTA BANNER EDITING -->
                <div v-else-if="section.type === 'cta'" class="grid sm:grid-cols-2 gap-4">
                  <div class="sm:col-span-2">
                    <label class="admin-label">CTA Banner Message</label>
                    <input v-model="section.data.title" type="text" class="admin-input" placeholder="Message details" required />
                  </div>
                  <div class="sm:col-span-2">
                    <label class="admin-label">CTA Subtitle Message</label>
                    <input v-model="section.data.subtitle" type="text" class="admin-input" placeholder="Optional details..." />
                  </div>
                  <div>
                    <label class="admin-label">Button Label</label>
                    <input v-model="section.data.button_text" type="text" class="admin-input" placeholder="Get Started" required />
                  </div>
                  <div>
                    <label class="admin-label">Action URL</label>
                    <input v-model="section.data.button_url" type="text" class="admin-input" placeholder="/contact" required />
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- SEO META TAB -->
          <div v-else-if="activeTab === 'seo'" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-6">
            <div>
              <h2 class="text-sm font-bold uppercase tracking-widest text-slate-200">SEO Meta Parameters</h2>
              <p class="text-[10px] text-slate-500">Provide search engine optimized tags for this service landing page.</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
              <div class="sm:col-span-2">
                <label class="admin-label">SEO Meta Title</label>
                <input v-model="form.seo.meta_title" type="text" class="admin-input" placeholder="Meta title" />
              </div>
              <div class="sm:col-span-2">
                <label class="admin-label">SEO Meta Description</label>
                <textarea v-model="form.seo.meta_description" rows="3" class="admin-input" placeholder="Enter concise summary for search engines"></textarea>
              </div>
              <div class="sm:col-span-2">
                <label class="admin-label">Meta Keywords</label>
                <input v-model="form.seo.meta_keywords" type="text" class="admin-input" placeholder="keyword1, keyword2, tag" />
              </div>
              <div>
                <label class="admin-label">Robots Policy</label>
                <input v-model="form.seo.robots" type="text" class="admin-input" placeholder="index, follow" />
              </div>
              <div>
                <label class="admin-label">Canonical URL</label>
                <input v-model="form.seo.canonical_url" type="url" class="admin-input" placeholder="https://agency.com/services/slug" />
              </div>
            </div>

            <div class="border-t border-slate-800/80 pt-5">
              <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Open Graph (Social Sharing)</h3>
              <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                  <label class="admin-label">OG Title</label>
                  <input v-model="form.seo.og_title" type="text" class="admin-input" placeholder="Facebook title" />
                </div>
                <div class="sm:col-span-2">
                  <label class="admin-label">OG Description</label>
                  <textarea v-model="form.seo.og_description" rows="2" class="admin-input" placeholder="Facebook description"></textarea>
                </div>
                <div class="sm:col-span-2">
                  <label class="admin-label">OG Image URL</label>
                  <input v-model="form.seo.og_image_url" type="text" class="admin-input" placeholder="https://agency.com/og-image.jpg" />
                </div>
              </div>
            </div>

            <div class="border-t border-slate-800/80 pt-5">
              <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Twitter Card Parameters</h3>
              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="admin-label">Twitter Card Type</label>
                  <select v-model="form.seo.twitter_card" class="admin-input">
                    <option value="summary">Summary</option>
                    <option value="summary_large_image">Summary Large Image</option>
                    <option value="app">App</option>
                    <option value="player">Player</option>
                  </select>
                </div>
                <div>
                  <label class="admin-label">Twitter Title</label>
                  <input v-model="form.seo.twitter_title" type="text" class="admin-input" placeholder="Twitter title" />
                </div>
                <div class="sm:col-span-2">
                  <label class="admin-label">Twitter Description</label>
                  <textarea v-model="form.seo.twitter_description" rows="2" class="admin-input" placeholder="Twitter description"></textarea>
                </div>
                <div class="sm:col-span-2">
                  <label class="admin-label">Twitter Image URL</label>
                  <input v-model="form.seo.twitter_image" type="text" class="admin-input" placeholder="https://agency.com/twitter-image.jpg" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </AdminLayout>
</template>
