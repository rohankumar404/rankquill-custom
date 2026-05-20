<script setup>
useLayout: true;
import { ref, watch, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

// Custom lightweight debounce helper to avoid lodash dependency
const debounce = (fn, delay) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn(...args), delay);
    };
};

const props = defineProps({
    subscribers: Object,
    filters: Object,
    stats: Object,
    recent_trends: Array,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

// Fetch clean trends data
const maxTrendCount = computed(() => {
    const counts = props.recent_trends.map(t => t.count);
    const max = Math.max(...counts);
    return max > 0 ? max : 5;
});

// Update filtering on changes
const updateFilters = debounce(() => {
    router.get(
        route('admin.newsletter.index'),
        { search: search.value, status: status.value },
        { preserveState: true, replace: true }
    );
}, 300);

watch([search, status], () => {
    updateFilters();
});

// Reset filters helper
const resetFilters = () => {
    search.value = '';
    status.value = '';
};

// Subscriber deletion trigger
const deleteSubscriber = (id) => {
    if (confirm('Are you sure you want to delete and unsubscribe this member?')) {
        router.delete(route('admin.newsletter.destroy', id), {
            preserveScroll: true,
        });
    }
};

// Tooltip helper
const hoveredBar = ref(null);
</script>

<template>
    <Head title="Newsletter Management | RankQuill Admin" />

    <AdminLayout>
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Newsletter Subscribers</h1>
                <p class="text-slate-400 text-sm mt-1">Monitor subscription velocity, sync statuses to Mailchimp & Brevo, and export contact metrics.</p>
            </div>
            
            <a
                :href="route('admin.newsletter.export')"
                class="inline-flex items-center justify-center px-5 py-3 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-750 text-sm font-semibold text-white rounded-xl shadow-lg shadow-indigo-600/20 transition-all space-x-2 w-full sm:w-auto"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export Subscribers CSV</span>
            </a>
        </div>

        <!-- KPI Grid Metrics -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-slate-900 opacity-20 transition-transform duration-500 group-hover:scale-110">
                    <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block">Total Contacts</span>
                <span class="text-3xl font-extrabold text-slate-100 block mt-2">{{ stats.total }}</span>
                <span class="text-[10px] text-slate-400 block mt-1">Collected newsletter profiles</span>
            </div>

            <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-emerald-500/10 opacity-20 transition-transform duration-500 group-hover:scale-110">
                    <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block">Active Status</span>
                <span class="text-3xl font-extrabold text-emerald-400 block mt-2">{{ stats.active }}</span>
                <span class="text-[10px] text-slate-400 block mt-1">Actively receiving updates</span>
            </div>

            <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-yellow-500/10 opacity-20 transition-transform duration-500 group-hover:scale-110">
                    <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block">Pending Contacts</span>
                <span class="text-3xl font-extrabold text-yellow-450 block mt-2">{{ stats.pending }}</span>
                <span class="text-[10px] text-slate-400 block mt-1">Awaiting opt-in validation</span>
            </div>

            <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-rose-500/10 opacity-20 transition-transform duration-500 group-hover:scale-110">
                    <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block">Unsubscribed</span>
                <span class="text-3xl font-extrabold text-rose-450 block mt-2">{{ stats.unsubscribed }}</span>
                <span class="text-[10px] text-slate-400 block mt-1">Users opted-out of feeds</span>
            </div>
        </div>

        <!-- 30-Day Growth Analytics Chart -->
        <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-100">Subscription Velocity</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Registrations tracked daily over the past 30 days.</p>
                </div>
                <div class="flex items-center space-x-2 text-[10px] text-slate-400 bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-lg">
                    <span class="h-2.5 w-2.5 rounded-full bg-indigo-500 inline-block"></span>
                    <span>Signups count</span>
                </div>
            </div>

            <!-- SVG Bar Graph -->
            <div class="relative pt-6">
                <div class="h-44 w-full flex items-end justify-between gap-1 border-b border-slate-800 pb-2 relative z-10">
                    <!-- Y Axis Lines -->
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none -z-10">
                        <div class="border-t border-slate-900/80 w-full h-0"></div>
                        <div class="border-t border-slate-900/80 w-full h-0"></div>
                        <div class="border-t border-slate-900/80 w-full h-0"></div>
                        <div class="border-t border-slate-900/80 w-full h-0"></div>
                    </div>

                    <!-- Bars -->
                    <div
                        v-for="(trend, idx) in recent_trends"
                        :key="trend.date"
                        class="flex-1 group flex flex-col justify-end items-center h-full relative cursor-pointer"
                        @mouseenter="hoveredBar = idx"
                        @mouseleave="hoveredBar = null"
                    >
                        <!-- Tooltip popup -->
                        <div
                            v-if="hoveredBar === idx"
                            class="absolute -top-10 bg-slate-950 border border-indigo-500/30 text-white rounded-lg px-2.5 py-1 text-[10px] font-bold z-30 shadow-xl pointer-events-none whitespace-nowrap"
                        >
                            {{ trend.label }}: <span class="text-indigo-400">{{ trend.count }} signups</span>
                        </div>

                        <!-- Actual Visual Bar -->
                        <div
                            :style="{ height: `${(trend.count / maxTrendCount) * 100}%` }"
                            :class="[
                                trend.count > 0 ? 'bg-gradient-to-t from-indigo-600/70 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400' : 'bg-slate-900/30',
                                'w-full rounded-t transition-all duration-200 min-h-[4px]'
                            ]"
                        ></div>
                    </div>
                </div>

                <!-- X Axis Labels (selective rendering to prevent clutter) -->
                <div class="flex justify-between text-[10px] text-slate-500 pt-2 px-1">
                    <span>{{ recent_trends[0]?.label }}</span>
                    <span>{{ recent_trends[10]?.label }}</span>
                    <span>{{ recent_trends[20]?.label }}</span>
                    <span>{{ recent_trends[29]?.label }}</span>
                </div>
            </div>
        </div>

        <!-- Filter Workstation -->
        <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-5 mb-6 flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-1 w-full">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4.5 w-4.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search by email address..."
                    class="w-full pl-10 pr-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                />
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select
                    v-model="status"
                    class="w-full sm:w-48 px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-350 text-sm transition-colors"
                >
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="unsubscribed">Unsubscribed</option>
                </select>

                <button
                    v-if="search || status"
                    @click="resetFilters"
                    class="px-4.5 py-3 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 text-sm font-semibold rounded-xl transition-all"
                >
                    Reset
                </button>
            </div>
        </div>

        <!-- Subscribers Table List -->
        <div class="bg-slate-950/70 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-950/80">
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">ID</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Email Address</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Subscribed At</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Unsubscribed At</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900">
                        <tr
                            v-for="sub in subscribers.data"
                            :key="sub.id"
                            class="hover:bg-slate-900/40 transition-colors group"
                        >
                            <td class="px-6 py-4 text-sm font-medium text-slate-400">#{{ sub.id }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-slate-200">{{ sub.email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        sub.status === 'active' ? 'bg-emerald-500/10 text-emerald-450 border-emerald-500/20' :
                                        sub.status === 'pending' ? 'bg-yellow-500/10 text-yellow-450 border-yellow-500/20' :
                                        'bg-rose-500/10 text-rose-450 border-rose-500/20',
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border'
                                    ]"
                                >
                                    {{ sub.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ sub.subscribed_at ? new Date(sub.subscribed_at).toLocaleDateString() : 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ sub.unsubscribed_at ? new Date(sub.unsubscribed_at).toLocaleDateString() : 'N/A' }}</td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    @click="deleteSubscriber(sub.id)"
                                    class="text-rose-500 hover:text-rose-450 text-xs font-bold transition-colors inline-flex items-center space-x-1"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Delete</span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="subscribers.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm">
                                No newsletter subscribers found matching current query.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Row -->
            <div v-if="subscribers.links && subscribers.links.length > 3" class="px-6 py-4 bg-slate-950/40 border-t border-slate-900 flex items-center justify-between">
                <span class="text-xs text-slate-500">Showing page {{ subscribers.current_page }} of {{ subscribers.last_page }}</span>
                <div class="flex items-center space-x-1">
                    <Link
                        v-for="link in subscribers.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        v-html="link.label"
                        :class="[
                            link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-900 text-slate-400 border-slate-800 hover:bg-slate-850',
                            'px-3 py-1.5 border rounded-lg text-xs font-semibold transition-all',
                            !link.url ? 'opacity-40 cursor-not-allowed' : ''
                        ]"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
