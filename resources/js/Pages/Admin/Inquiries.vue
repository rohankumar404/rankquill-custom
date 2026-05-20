<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  inquiries: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  }
})

const activeStatus = ref(props.filters.status || '')
const selectedInquiry = ref(null)

const filterByStatus = (status) => {
  activeStatus.value = status
  router.get(route('admin.inquiries.index'), { status }, { preserveState: true })
}

const updateStatus = (inquiry, newStatus) => {
  router.put(route('admin.inquiries.update', inquiry.id), {
    status: newStatus
  }, {
    preserveScroll: true,
    onSuccess: () => {
      if (selectedInquiry.value && selectedInquiry.value.id === inquiry.id) {
        selectedInquiry.value.status = newStatus
      }
    }
  })
}

const deleteInquiry = (inquiry) => {
  if (confirm('Are you sure you want to delete this inquiry?')) {
    router.delete(route('admin.inquiries.destroy', inquiry.id), {
      preserveScroll: true,
      onSuccess: () => {
        selectedInquiry.value = null
      }
    })
  }
}

const openDetails = (inquiry) => {
  selectedInquiry.value = inquiry
}

const closeDetails = () => {
  selectedInquiry.value = null
}
</script>

<template>
  <Head title="Lead Management - Admin Panel" />

  <AdminLayout>
    <div class="space-y-6">
      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-white tracking-tight">Lead Inquiries</h1>
          <p class="text-slate-400 text-xs">Monitor user requests, metadata details, and track conversions.</p>
        </div>
        
        <a 
          :href="route('admin.inquiries.export')"
          class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition flex items-center gap-1.5 shadow-md shadow-indigo-600/10"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Export CSV Report
        </a>
      </div>

      <!-- Filters Row -->
      <div class="flex items-center gap-2 border-b border-slate-800 pb-4">
        <button 
          @click="filterByStatus('')"
          class="text-xs font-medium px-4 py-2 rounded-lg transition"
          :class="!activeStatus ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/25' : 'text-slate-400 hover:text-slate-200'"
        >
          All Leads
        </button>
        <button 
          @click="filterByStatus('new')"
          class="text-xs font-medium px-4 py-2 rounded-lg transition"
          :class="activeStatus === 'new' ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/25' : 'text-slate-400 hover:text-slate-200'"
        >
          New
        </button>
        <button 
          @click="filterByStatus('processing')"
          class="text-xs font-medium px-4 py-2 rounded-lg transition"
          :class="activeStatus === 'processing' ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/25' : 'text-slate-400 hover:text-slate-200'"
        >
          Processing
        </button>
        <button 
          @click="filterByStatus('resolved')"
          class="text-xs font-medium px-4 py-2 rounded-lg transition"
          :class="activeStatus === 'resolved' ? 'bg-indigo-600/15 text-indigo-400 border border-indigo-500/25' : 'text-slate-400 hover:text-slate-200'"
        >
          Resolved
        </button>
      </div>

      <!-- Leads Table -->
      <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-800 bg-slate-950/80 text-[10px] uppercase font-bold tracking-wider text-slate-400">
                <th class="p-4">Contact Info</th>
                <th class="p-4">Company</th>
                <th class="p-4">Subject</th>
                <th class="p-4">Status</th>
                <th class="p-4">Source</th>
                <th class="p-4">Submitted At</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-xs">
              <tr v-if="inquiries.data.length === 0">
                <td colspan="7" class="p-8 text-center text-slate-500">No leads captured yet.</td>
              </tr>
              <tr 
                v-for="lead in inquiries.data" 
                :key="lead.id"
                @click="openDetails(lead)"
                class="hover:bg-slate-900/50 cursor-pointer transition-colors"
              >
                <!-- Name & Email -->
                <td class="p-4">
                  <div class="font-semibold text-slate-100">{{ lead.name }}</div>
                  <div class="text-[10px] text-slate-500">{{ lead.email }}</div>
                </td>
                
                <!-- Company -->
                <td class="p-4 text-slate-300">
                  {{ lead.company || 'N/A' }}
                </td>

                <!-- Subject -->
                <td class="p-4 text-slate-300 truncate max-w-[200px]">
                  {{ lead.subject || 'General inquiry' }}
                </td>

                <!-- Status -->
                <td class="p-4" @click.stop>
                  <select 
                    v-model="lead.status" 
                    @change="updateStatus(lead, lead.status)"
                    class="bg-slate-900 border border-slate-800 rounded-lg text-xs py-1 px-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition"
                  >
                    <option value="new">New</option>
                    <option value="processing">Processing</option>
                    <option value="resolved">Resolved</option>
                  </select>
                </td>

                <!-- Source -->
                <td class="p-4 text-slate-400">
                  <span class="text-[10px] bg-slate-900 border border-slate-850 px-2 py-0.5 rounded-md font-mono uppercase">
                    {{ lead.source }}
                  </span>
                </td>

                <!-- Date -->
                <td class="p-4 text-slate-500">
                  {{ new Date(lead.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) }}
                </td>

                <!-- Actions -->
                <td class="p-4 text-right" @click.stop>
                  <button 
                    @click="deleteInquiry(lead)"
                    class="text-rose-500 hover:text-rose-400 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors"
                    title="Delete Lead"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Detail Showcase Modal -->
      <div v-if="selectedInquiry" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div @click="closeDetails" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

        <!-- Content Container -->
        <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-3xl p-8 space-y-6 shadow-2xl">
          <div class="flex justify-between items-start">
            <div>
              <span class="text-[10px] font-mono text-indigo-400 uppercase tracking-wider bg-indigo-500/10 border border-indigo-500/20 px-2.5 py-0.5 rounded-full">
                Lead Detail #{{ selectedInquiry.id }}
              </span>
              <h3 class="text-xl font-bold text-white mt-2">{{ selectedInquiry.name }}</h3>
              <p class="text-xs text-slate-500">{{ selectedInquiry.email }}</p>
            </div>
            <button @click="closeDetails" class="text-slate-500 hover:text-slate-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Lead Info Grid -->
          <div class="grid grid-cols-2 gap-4 bg-slate-950/60 p-4 border border-slate-950 rounded-2xl text-xs">
            <div>
              <span class="text-slate-500 block">Phone:</span>
              <span class="text-slate-200 font-medium">{{ selectedInquiry.phone || 'N/A' }}</span>
            </div>
            <div>
              <span class="text-slate-500 block">Company:</span>
              <span class="text-slate-200 font-medium">{{ selectedInquiry.company || 'N/A' }}</span>
            </div>
            <div>
              <span class="text-slate-500 block">Captured Source:</span>
              <span class="text-slate-200 font-medium uppercase">{{ selectedInquiry.source }}</span>
            </div>
            <div>
              <span class="text-slate-500 block">Current Status:</span>
              <span class="text-slate-200 font-medium uppercase text-indigo-400">{{ selectedInquiry.status }}</span>
            </div>
          </div>

          <!-- Message Block -->
          <div class="space-y-2">
            <span class="text-xs font-semibold text-slate-400">Message Payload:</span>
            <div class="bg-slate-950 border border-slate-800 p-5 rounded-2xl text-xs text-slate-300 leading-relaxed whitespace-pre-wrap max-h-48 overflow-y-auto">
              {{ selectedInquiry.message }}
            </div>
          </div>

          <!-- Visitor Metadata -->
          <div class="space-y-2" v-if="selectedInquiry.metadata">
            <span class="text-xs font-semibold text-slate-400">Visitor Metadata:</span>
            <div class="bg-slate-950/50 p-4 rounded-xl text-[10px] font-mono text-slate-500 space-y-1">
              <div>IP Address: <span class="text-slate-400">{{ selectedInquiry.metadata.ip || 'N/A' }}</span></div>
              <div>User Agent: <span class="text-slate-400">{{ selectedInquiry.metadata.user_agent || 'N/A' }}</span></div>
              <div>Referrer: <span class="text-slate-400">{{ selectedInquiry.metadata.referrer || 'Direct / Unknown' }}</span></div>
            </div>
          </div>

          <!-- Modal Actions -->
          <div class="flex justify-between items-center pt-4 border-t border-slate-800/80">
            <button 
              @click="deleteInquiry(selectedInquiry)"
              class="text-xs text-rose-500 hover:text-rose-400 font-semibold flex items-center gap-1"
            >
              Delete Inquire
            </button>
            <div class="flex gap-2">
              <button 
                v-if="selectedInquiry.status !== 'resolved'"
                @click="updateStatus(selectedInquiry, 'resolved')"
                class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2 rounded-lg"
              >
                Mark Resolved
              </button>
              <button 
                @click="closeDetails"
                class="bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2 rounded-lg"
              >
                Close Details
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
