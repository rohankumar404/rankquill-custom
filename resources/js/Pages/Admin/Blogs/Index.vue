<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  blogs: {
    type: Object,
    required: true
  },
  categories: {
    type: Array,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  }
})

const search = ref(props.filters.search || '')
const categoryId = ref(props.filters.category_id || '')

watch([search, categoryId], () => {
  router.get(route('admin.blogs.index'), { 
    search: search.value, 
    category_id: categoryId.value 
  }, {
    preserveState: true,
    replace: true
  })
})

const deleteBlog = (id) => {
  if (confirm('Are you sure you want to delete this blog post?')) {
    router.delete(route('admin.blogs.destroy', id), {
      preserveScroll: true
    })
  }
}
</script>

<template>
  <Head title="Blog Posts - Admin Panel" />

  <AdminLayout>
    <div class="space-y-6">
      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-white tracking-tight">Blog Posts</h1>
          <p class="text-slate-400 text-xs">Write, publish, and optimize editorial articles for your digital agency marketing channel.</p>
        </div>
        
        <Link 
          :href="route('admin.blogs.create')"
          class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition flex items-center gap-1.5 shadow-md shadow-indigo-600/10"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Write New Article
        </Link>
      </div>

      <!-- Search & Filters -->
      <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-900 border border-slate-800/80 p-4 rounded-2xl">
        <div class="relative w-full sm:w-80">
          <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </span>
          <input 
            v-model="search"
            type="text"
            placeholder="Search article by title..."
            class="w-full pl-10 pr-4 py-2 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-slate-200 text-xs focus:outline-none transition-colors"
          />
        </div>

        <div>
          <select 
            v-model="categoryId"
            class="bg-slate-950 border border-slate-800 text-slate-400 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:border-indigo-500"
          >
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
        </div>
      </div>

      <!-- Blogs Table -->
      <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-800 bg-slate-950/80 text-[10px] uppercase font-bold tracking-wider text-slate-400">
                <th class="p-4 pl-6">Article Details</th>
                <th class="p-4">Category</th>
                <th class="p-4">Status</th>
                <th class="p-4">Published At</th>
                <th class="p-4 pr-6 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-xs">
              <tr v-if="blogs.data.length === 0">
                <td colspan="5" class="p-8 text-center text-slate-500 font-medium">No blog posts found.</td>
              </tr>
              <tr 
                v-for="blog in blogs.data" 
                :key="blog.id"
                class="hover:bg-slate-900/30 transition-colors"
              >
                <!-- Title & Author -->
                <td class="p-4 pl-6">
                  <div class="font-semibold text-slate-100">{{ blog.title }}</div>
                  <div class="text-[10px] text-slate-500 mt-1 flex items-center gap-2">
                    <span>By {{ blog.author?.name || 'Admin' }}</span>
                    <span>•</span>
                    <span class="font-mono">/blog/{{ blog.category?.slug || 'uncategorized' }}/{{ blog.slug }}</span>
                  </div>
                </td>
                
                <!-- Category -->
                <td class="p-4">
                  <span class="px-2 py-1 bg-slate-900 border border-slate-800 rounded text-slate-300 text-[10px]">
                    {{ blog.category?.name || 'Uncategorized' }}
                  </span>
                </td>

                <!-- Status -->
                <td class="p-4">
                  <span 
                    class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full border"
                    :class="blog.status === 'published' 
                      ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' 
                      : 'bg-amber-500/10 text-amber-400 border-amber-500/20'"
                  >
                    {{ blog.status }}
                  </span>
                </td>

                <!-- Date -->
                <td class="p-4 text-slate-400 font-mono">
                  {{ blog.published_at ? new Date(blog.published_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '—' }}
                </td>

                <!-- Actions -->
                <td class="p-4 pr-6 text-right space-x-1.5" @click.stop>
                  <a 
                    v-if="blog.status === 'published'"
                    :href="route('blog.show', { category: blog.category?.slug || 'uncategorized', slug: blog.slug })" 
                    target="_blank"
                    class="inline-flex items-center justify-center p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors"
                    title="View Live Article"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                  </a>
                  <Link 
                    :href="route('admin.blogs.edit', blog.id)"
                    class="inline-flex items-center justify-center p-2 rounded-lg bg-slate-900 border border-slate-800 text-indigo-400 hover:text-indigo-300 transition-colors"
                    title="Edit Post"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </Link>
                  <button 
                    @click="deleteBlog(blog.id)"
                    class="inline-flex items-center justify-center p-2 rounded-lg bg-slate-900 border border-slate-800 text-rose-500 hover:text-rose-450 transition-colors"
                    title="Delete Post"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Custom Pagination -->
        <div v-if="blogs.links && blogs.links.length > 3" class="flex items-center justify-between border-t border-slate-800/80 bg-slate-950/80 px-6 py-4">
          <div class="flex-1 flex justify-between sm:hidden">
            <Link 
              v-if="blogs.prev_page_url" 
              :href="blogs.prev_page_url" 
              class="relative inline-flex items-center px-4 py-2 border border-slate-800 text-xs font-semibold rounded-lg text-slate-300 bg-slate-900 hover:bg-slate-850"
            >
              Previous
            </Link>
            <Link 
              v-if="blogs.next_page_url" 
              :href="pages.next_page_url" 
              class="relative inline-flex items-center px-4 py-2 border border-slate-800 text-xs font-semibold rounded-lg text-slate-300 bg-slate-900 hover:bg-slate-850 ml-3"
            >
              Next
            </Link>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-xs text-slate-400">
                Showing
                <span class="font-semibold text-white">{{ blogs.from }}</span>
                to
                <span class="font-semibold text-white">{{ blogs.to }}</span>
                of
                <span class="font-semibold text-white">{{ blogs.total }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px" aria-label="Pagination">
                <template v-for="(link, key) in blogs.links" :key="key">
                  <div 
                    v-if="link.url === null" 
                    class="relative inline-flex items-center px-3 py-2 border border-slate-800 bg-slate-950 text-xs font-medium text-slate-650 rounded-lg cursor-not-allowed"
                    v-html="link.label"
                  />
                  <Link
                    v-else
                    :href="link.url"
                    class="relative inline-flex items-center px-3 py-2 border text-xs font-medium rounded-lg transition-all"
                    :class="link.active 
                      ? 'z-10 bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-600/10' 
                      : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-white hover:bg-slate-850'"
                    v-html="link.label"
                  />
                </template>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
