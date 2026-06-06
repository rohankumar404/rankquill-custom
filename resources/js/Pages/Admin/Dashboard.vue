<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({ stats: { type: Object, default: () => ({}) } });

const quickLinks = [
  { label: 'Manage Inquiries', sub: 'View all leads', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', href: () => route('admin.inquiries.index'), color: 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' },
  { label: 'Newsletter', sub: 'Manage subscribers', icon: 'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206', href: () => route('admin.newsletter.index'), color: 'bg-purple-500/10 text-purple-400 border-purple-500/20' },
  { label: 'Media Manager', sub: 'Upload & organize files', icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', href: () => route('admin.media.index'), color: 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' },
  { label: 'Activity Logs', sub: 'Monitor all actions', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', href: () => route('admin.activity-logs'), color: 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' },
  { label: 'Global Settings', sub: 'Configure site params', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', href: () => route('admin.settings.index'), color: 'bg-orange-500/10 text-orange-400 border-orange-500/20' },
  { label: 'View Live Site', sub: 'Open public website', icon: 'M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14', href: () => '/', color: 'bg-slate-800 text-slate-400 border-slate-700', external: true },
];
</script>

<template>
  <Head title="Admin Dashboard — RankQuill" />
  <AdminLayout>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-black text-white tracking-tight">Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1">Welcome back! Here's what's happening with your platform.</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="flex items-center gap-1.5 text-xs text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 px-3 py-1.5 rounded-full font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
          Live
        </span>
        <a href="/" target="_blank" class="admin-btn admin-btn-ghost text-xs border border-slate-800">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          View Site
        </a>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

      <div class="admin-card relative overflow-hidden group hover:border-indigo-500/30 transition-all duration-300">
        <div class="absolute inset-0 bg-indigo-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative">
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
              <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-xs text-emerald-400 font-semibold">↑ 12%</span>
          </div>
          <div class="text-3xl font-black text-white mb-1">{{ stats.leads_count ?? 0 }}</div>
          <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Inquiries</div>
        </div>
      </div>

      <div class="admin-card relative overflow-hidden group hover:border-purple-500/30 transition-all duration-300">
        <div class="absolute inset-0 bg-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative">
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-xs text-emerald-400 font-semibold">↑ 8%</span>
          </div>
          <div class="text-3xl font-black text-white mb-1">{{ stats.subscribers_count ?? 0 }}</div>
          <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Subscribers</div>
        </div>
      </div>

      <div class="admin-card relative overflow-hidden group hover:border-cyan-500/30 transition-all duration-300">
        <div class="absolute inset-0 bg-cyan-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative">
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center">
              <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h3"/></svg>
            </div>
            <span class="text-xs text-emerald-400 font-semibold">Active</span>
          </div>
          <div class="text-3xl font-black text-white mb-1">{{ stats.posts_count ?? 0 }}</div>
          <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Blog Posts</div>
        </div>
      </div>

      <div class="admin-card relative overflow-hidden group hover:border-rose-500/30 transition-all duration-300">
        <div class="absolute inset-0 bg-rose-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative">
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center">
              <svg class="w-5 h-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <span class="text-xs text-slate-500 font-semibold">avg</span>
          </div>
          <div class="text-3xl font-black text-white mb-1">{{ stats.average_read_time ?? '—' }}</div>
          <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Avg Read Time</div>
        </div>
      </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="mb-8">
      <h2 class="text-sm font-bold uppercase tracking-widest text-slate-500 mb-4">Quick Actions</h2>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <template v-for="link in quickLinks" :key="link.label">
          <a v-if="link.external" :href="link.href()" target="_blank" class="group flex flex-col items-center text-center gap-3 p-4 rounded-2xl bg-slate-900 border border-slate-800 hover:border-slate-600 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-950/50">
            <div :class="`w-10 h-10 rounded-xl border flex items-center justify-center transition-transform duration-200 group-hover:scale-110 ${link.color}`">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" :d="link.icon"/></svg>
            </div>
            <div>
              <p class="text-xs font-semibold text-slate-300 group-hover:text-white transition-colors">{{ link.label }}</p>
              <p class="text-[10px] text-slate-600 mt-0.5">{{ link.sub }}</p>
            </div>
          </a>
          <Link v-else :href="link.href()" class="group flex flex-col items-center text-center gap-3 p-4 rounded-2xl bg-slate-900 border border-slate-800 hover:border-slate-600 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-950/50">
            <div :class="`w-10 h-10 rounded-xl border flex items-center justify-center transition-transform duration-200 group-hover:scale-110 ${link.color}`">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" :d="link.icon"/></svg>
            </div>
            <div>
              <p class="text-xs font-semibold text-slate-300 group-hover:text-white transition-colors">{{ link.label }}</p>
              <p class="text-[10px] text-slate-600 mt-0.5">{{ link.sub }}</p>
            </div>
          </Link>
        </template>
      </div>
    </div>

    <!-- Platform Info Cards -->
    <div class="grid lg:grid-cols-2 gap-4">
      <!-- Platform Status -->
      <div class="admin-card">
        <h3 class="text-sm font-bold text-slate-200 mb-4 flex items-center gap-2">
          <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          Platform Status
        </h3>
        <div class="space-y-3">
          <div v-for="item in [
            { label: 'Laravel 11 Framework', status: 'Operational', color: 'emerald' },
            { label: 'Inertia.js + Vue 3', status: 'Operational', color: 'emerald' },
            { label: 'MySQL Database', status: 'Connected', color: 'emerald' },
            { label: 'Redis Cache', status: 'Active', color: 'emerald' },
            { label: 'Queue Workers', status: 'Running', color: 'emerald' },
          ]" :key="item.label" class="flex items-center justify-between py-2 border-b border-slate-800/60 last:border-0">
            <span class="text-sm text-slate-400">{{ item.label }}</span>
            <span :class="`text-xs font-semibold px-2 py-0.5 rounded-full bg-${item.color}-500/10 text-${item.color}-400 border border-${item.color}-500/20`">{{ item.status }}</span>
          </div>
        </div>
      </div>

      <!-- Getting Started -->
      <div class="admin-card">
        <h3 class="text-sm font-bold text-slate-200 mb-4 flex items-center gap-2">
          <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          Getting Started
        </h3>
        <div class="space-y-3">
          <div v-for="(task, idx) in [
            { label: 'Configure site settings & branding', done: true, href: () => route('admin.settings.index') },
            { label: 'Add services to your portfolio', done: false, href: null },
            { label: 'Upload media assets', done: false, href: () => route('admin.media.index') },
            { label: 'Review incoming inquiries', done: false, href: () => route('admin.inquiries.index') },
            { label: 'Manage newsletter subscribers', done: false, href: () => route('admin.newsletter.index') },
          ]" :key="idx" class="flex items-center gap-3 py-2 border-b border-slate-800/60 last:border-0">
            <div :class="`w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center ${task.done ? 'bg-emerald-500 border-emerald-500' : 'border-slate-700'}`">
              <svg v-if="task.done" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm flex-1" :class="task.done ? 'text-slate-500 line-through' : 'text-slate-300'">{{ task.label }}</span>
            <component :is="task.href ? Link : 'span'" v-if="!task.done && task.href" :href="task.href()" class="text-xs text-indigo-400 hover:text-indigo-300 hover:underline transition-colors flex-shrink-0">Go →</component>
          </div>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>
