<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-100">Dashboard</h1>
            <p class="text-slate-400 text-sm">Real-time statistics and digital agency operations hub.</p>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Inquiries</p>
                    <h3 class="text-2xl font-bold mt-1 text-slate-100">{{ stats.leads_count }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Subscribers</p>
                    <h3 class="text-2xl font-bold mt-1 text-slate-100">{{ stats.subscribers_count }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Active Blog Posts</p>
                    <h3 class="text-2xl font-bold mt-1 text-slate-100">{{ stats.posts_count }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h3" />
                    </svg>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Avg Read Time</p>
                    <h3 class="text-2xl font-bold mt-1 text-slate-100">{{ stats.average_read_time }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Dashboard Layout Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800">
                <h3 class="text-lg font-bold text-slate-200 mb-2">Platform Overview</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    The administrative portal is configured and connected using Inertia.js, Vue 3, and Tailwind CSS v4. Standard activity logs, settings, and CRM integration hooks are ready for deployment.
                </p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800">
                <h3 class="text-lg font-bold text-slate-200 mb-2">Quick Navigation</h3>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <Link :href="route('admin.settings.index')" class="p-4 rounded-xl bg-slate-900 border border-slate-800 hover:border-indigo-500/50 hover:bg-slate-900/55 transition-all text-center">
                        <span class="block text-sm font-semibold text-slate-200">Global Settings</span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">Configure parameters</span>
                    </Link>
                    <Link :href="route('admin.activity-logs')" class="p-4 rounded-xl bg-slate-900 border border-slate-800 hover:border-purple-500/50 hover:bg-slate-900/55 transition-all text-center">
                        <span class="block text-sm font-semibold text-slate-200">Activity Audits</span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">Monitor activity logs</span>
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
