<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  blog: {
    type: Object,
    default: null
  },
  categories: {
    type: Array,
    required: true
  },
  tags: {
    type: Array,
    required: true
  }
})

const isEditing = !!props.blog
const activeTab = ref('content') // 'content' or 'seo'

const initialTagIds = props.blog?.tags ? props.blog.tags.map(t => t.id) : []

const form = useForm({
  title: props.blog?.title || '',
  slug: props.blog?.slug || '',
  excerpt: props.blog?.excerpt || '',
  body: props.blog?.body || '',
  status: props.blog?.status || 'draft',
  published_at: props.blog?.published_at ? props.blog.published_at.substring(0, 16) : '',
  category_id: props.blog?.category_id || '',
  focus_keyword: props.blog?.focus_keyword || '',
  tag_ids: initialTagIds,
  seo: {
    meta_title: props.blog?.seo?.meta_title || '',
    meta_description: props.blog?.seo?.meta_description || '',
    meta_keywords: props.blog?.seo?.meta_keywords || '',
    robots: props.blog?.seo?.robots || 'index, follow',
    canonical_url: props.blog?.seo?.canonical_url || '',
    og_title: props.blog?.seo?.og_title || '',
    og_description: props.blog?.seo?.og_description || '',
    og_image_url: props.blog?.seo?.og_image_url || '',
    twitter_card: props.blog?.seo?.twitter_card || 'summary_large_image',
    twitter_title: props.blog?.seo?.twitter_title || '',
    twitter_description: props.blog?.seo?.twitter_description || '',
    twitter_image: props.blog?.seo?.twitter_image || ''
  }
})

const toggleTagSelection = (tagId) => {
  const index = form.tag_ids.indexOf(tagId)
  if (index > -1) {
    form.tag_ids.splice(index, 1)
  } else {
    form.tag_ids.push(tagId)
  }
}

const submit = () => {
  if (isEditing) {
    form.put(route('admin.blogs.update', props.blog.id))
  } else {
    form.post(route('admin.blogs.store'))
  }
}
</script>

<template>
  <Head :title="isEditing ? 'Edit Blog Article - Admin' : 'Write Blog Article - Admin'" />

  <AdminLayout>
    <form @submit.prevent="submit" class="space-y-6 max-w-6xl mx-auto pb-16">
      
      <!-- Top Title and Status Bar -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-900 border border-slate-800/80 p-5 rounded-2xl">
        <div class="flex-1 w-full">
          <div class="text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-1.5">
            {{ isEditing ? 'Edit Article' : 'Draft New Editorial' }}
          </div>
          <input 
            v-model="form.title" 
            type="text" 
            placeholder="Enter Blog Post Title..." 
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
              <option value="draft">Draft (Private)</option>
              <option value="published">Published (Live)</option>
            </select>
          </div>
          
          <button 
            type="submit" 
            :disabled="form.processing"
            class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-indigo-600/10 disabled:opacity-50"
          >
            {{ form.processing ? 'Saving...' : 'Save Article' }}
          </button>
        </div>
      </div>

      <!-- Tab Navigation and Configuration -->
      <div class="grid md:grid-cols-3 gap-6">
        <!-- Sidebar Metadata -->
        <div class="md:col-span-1 space-y-6">
          <!-- Slug & Category Card -->
          <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400">Article Routing</h3>
            
            <!-- Slug -->
            <div>
              <label class="admin-label">Slug (URL Slug)</label>
              <input 
                v-model="form.slug" 
                type="text" 
                placeholder="my-blog-post" 
                class="admin-input"
              />
              <p class="text-[10px] text-slate-500 mt-1">Leave blank to auto-slugify the title.</p>
              <span v-if="form.errors.slug" class="text-xs text-rose-500 block mt-1">{{ form.errors.slug }}</span>
            </div>

            <!-- Category -->
            <div>
              <label class="admin-label">Blog Category</label>
              <select v-model="form.category_id" class="admin-input" required>
                <option value="" disabled>Select Category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
              </select>
              <span v-if="form.errors.category_id" class="text-xs text-rose-500 block mt-1">{{ form.errors.category_id }}</span>
            </div>

            <!-- Publish Date -->
            <div>
              <label class="admin-label">Publish At (Scheduled/Actual)</label>
              <input v-model="form.published_at" type="datetime-local" class="admin-input" />
              <p class="text-[10px] text-slate-500 mt-1">Leave blank to publish immediately on save.</p>
              <span v-if="form.errors.published_at" class="text-xs text-rose-500 block mt-1">{{ form.errors.published_at }}</span>
            </div>

            <!-- SEO Keyword -->
            <div>
              <label class="admin-label">Focus SEO Keyword</label>
              <input v-model="form.focus_keyword" type="text" placeholder="e.g. SEO tips" class="admin-input" />
            </div>
          </div>

          <!-- Tags Checklist Card -->
          <div class="bg-slate-900 border border-slate-800/80 p-5 rounded-2xl space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400">Article Tags</h3>
            <div class="max-h-48 overflow-y-auto p-3 bg-slate-950 rounded-xl border border-slate-800/60 space-y-1.5">
              <label 
                v-for="tag in tags" 
                :key="tag.id"
                class="flex items-center space-x-2 text-xs text-slate-300 p-1 hover:bg-slate-900 rounded cursor-pointer"
              >
                <input 
                  type="checkbox" 
                  :checked="form.tag_ids.includes(tag.id)"
                  @change="toggleTagSelection(tag.id)"
                  class="rounded bg-slate-950 border-slate-800 text-indigo-600 focus:ring-0" 
                />
                <span>{{ tag.name }}</span>
              </label>
            </div>
          </div>

          <!-- Tab Selector -->
          <div class="bg-slate-900 border border-slate-800/80 p-3 rounded-2xl flex gap-1">
            <button 
              type="button" 
              @click="activeTab = 'content'" 
              class="flex-1 text-center py-2 text-xs font-semibold rounded-xl transition-all"
              :class="activeTab === 'content' ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:text-slate-200'"
            >
              Content Layout
            </button>
            <button 
              type="button" 
              @click="activeTab = 'seo'" 
              class="flex-1 text-center py-2 text-xs font-semibold rounded-xl transition-all"
              :class="activeTab === 'seo' ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:text-slate-200'"
            >
              SEO Meta Tags
            </button>
          </div>
        </div>

        <!-- Main Form Content -->
        <div class="md:col-span-2 space-y-6">
          <!-- ARTICLE EDITOR TAB -->
          <div v-if="activeTab === 'content'" class="space-y-6 bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl">
            
            <!-- Excerpt -->
            <div class="space-y-1.5">
              <label class="admin-label">Article Excerpt (Brief Summary)</label>
              <textarea 
                v-model="form.excerpt" 
                rows="3" 
                class="admin-input" 
                placeholder="Write a brief, catchy summary of the article to show on grids and card layouts..."
              ></textarea>
              <span v-if="form.errors.excerpt" class="text-xs text-rose-500 block mt-1">{{ form.errors.excerpt }}</span>
            </div>

            <!-- Body -->
            <div class="space-y-1.5">
              <label class="admin-label">Main Article Body (Supports HTML/Rich Text)</label>
              <textarea 
                v-model="form.body" 
                rows="14" 
                class="admin-input font-mono text-xs leading-relaxed" 
                placeholder="Write full editorial article body content here..."
                required
              ></textarea>
              <span v-if="form.errors.body" class="text-xs text-rose-500 block mt-1">{{ form.errors.body }}</span>
            </div>
          </div>

          <!-- SEO META TAB -->
          <div v-else-if="activeTab === 'seo'" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-6">
            <div>
              <h2 class="text-sm font-bold uppercase tracking-widest text-slate-200">SEO Meta Parameters</h2>
              <p class="text-[10px] text-slate-500">Provide search engine optimized tags for this editorial blog post.</p>
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
                <input v-model="form.seo.canonical_url" type="url" class="admin-input" placeholder="https://agency.com/blog/category/slug" />
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
