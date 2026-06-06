<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const isMobileSidebarOpen = ref(false);
const isUserMenuOpen = ref(false);

const user = computed(() => page.props.auth?.user || {});
const userInitial = computed(() => user.value?.name?.charAt(0)?.toUpperCase() || 'A');
const flash = computed(() => page.props.flash || {});

const navigationGroups = [
  {
    label: 'Main',
    items: [
      { name: 'Dashboard', href: () => route('admin.dashboard'), pattern: '/admin/dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', badge: null },
    ]
  },
  {
    label: 'Content',
    items: [
      { name: 'CMS Pages', href: () => route('admin.pages.index'), pattern: '/admin/pages', icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' },
      { name: 'Blog Posts', href: () => route('admin.blogs.index'), pattern: '/admin/blogs', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h3' },
      { name: 'Services', href: () => route('admin.services.index'), pattern: '/admin/services', icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
      { name: 'Portfolio', href: () => route('admin.portfolios.index'), pattern: '/admin/portfolios', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' },
      { name: 'Media Manager', href: () => route('admin.media.index'), pattern: '/admin/media', icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z' },
    ]
  },
  {
    label: 'CRM',
    items: [
      { name: 'Inquiries', href: () => route('admin.inquiries.index'), pattern: '/admin/inquiries', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
      { name: 'Newsletter', href: () => route('admin.newsletter.index'), pattern: '/admin/newsletter', icon: 'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206' },
    ]
  },
  {
    label: 'System',
    items: [
      { name: 'Activity Logs', href: () => route('admin.activity-logs'), pattern: '/admin/activity-logs', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' },
      { name: 'Settings', href: () => route('admin.settings.index'), pattern: '/admin/settings', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
    ]
  }
];

const isActive = (pattern) => {
  if (typeof window === 'undefined') return false;
  return window.location.pathname.startsWith(pattern);
};

const handleLogout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans flex">

    <!-- ============================================================
         SIDEBAR
    ============================================================= -->
    <aside class="hidden md:flex md:w-64 md:flex-col fixed inset-y-0 z-30">
      <!-- Sidebar Background -->
      <div class="flex flex-col h-full bg-[#0a0f1e] border-r border-slate-800/60">

        <!-- Brand -->
        <div class="h-16 flex items-center px-5 border-b border-slate-800/60 flex-shrink-0">
          <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center mr-2.5 shadow-lg shadow-indigo-600/20">
            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <div>
            <span class="text-base font-black bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent tracking-tight">RankQuill</span>
            <span class="ml-2 text-[9px] font-bold px-1.5 py-0.5 bg-indigo-500/20 text-indigo-400 rounded border border-indigo-500/30">CMS</span>
          </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-5">
          <div v-for="group in navigationGroups" :key="group.label">
            <div class="px-3 mb-2">
              <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">{{ group.label }}</span>
            </div>
            <div class="space-y-0.5">
              <template v-for="item in group.items" :key="item.name">
                <!-- Active Item -->
                <div v-if="item.href === null || item.soon" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 cursor-not-allowed select-none">
                  <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon"/>
                  </svg>
                  <span class="text-sm">{{ item.name }}</span>
                  <span class="ml-auto text-[9px] bg-slate-800 text-slate-600 px-2 py-0.5 rounded-full border border-slate-700">Soon</span>
                </div>
                <Link v-else :href="item.href()" :class="[
                  'group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200',
                  isActive(item.pattern)
                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20'
                    : 'text-slate-400 hover:text-white hover:bg-slate-800/80'
                ]">
                  <svg class="w-4 h-4 flex-shrink-0 transition-colors" :class="isActive(item.pattern) ? 'text-white' : 'text-slate-600 group-hover:text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon"/>
                  </svg>
                  {{ item.name }}
                  <span v-if="item.badge" class="ml-auto text-[10px] bg-indigo-500 text-white px-2 py-0.5 rounded-full">{{ item.badge }}</span>
                </Link>
              </template>
            </div>
          </div>
        </nav>

        <!-- User Footer -->
        <div class="flex-shrink-0 p-3 border-t border-slate-800/60">
          <div class="relative">
            <button @click="isUserMenuOpen = !isUserMenuOpen" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800/60 transition-colors group">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-sm text-white flex-shrink-0">
                {{ userInitial }}
              </div>
              <div class="flex-1 min-w-0 text-left">
                <p class="text-xs font-semibold text-slate-200 truncate">{{ user.name || 'Admin User' }}</p>
                <p class="text-[10px] text-slate-500 truncate">{{ user.email || 'admin@rankquill.com' }}</p>
              </div>
              <svg class="w-3.5 h-3.5 text-slate-600 group-hover:text-slate-400 transition-transform duration-200 flex-shrink-0" :class="isUserMenuOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <!-- User Menu Dropdown -->
            <transition enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0 translate-y-1 scale-95" enter-to-class="opacity-100 translate-y-0 scale-100" leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
              <div v-if="isUserMenuOpen" class="absolute bottom-full left-0 right-0 mb-2 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl shadow-slate-950/80 overflow-hidden py-1">
                <a href="/" target="_blank" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  View Live Site
                </a>
                <Link :href="route('admin.settings.index')" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                  Settings
                </Link>
                <div class="border-t border-slate-800 my-1"></div>
                <button @click="handleLogout" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition-colors">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                  Sign Out
                </button>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </aside>

    <!-- ============================================================
         MAIN CONTENT AREA
    ============================================================= -->
    <div class="flex-1 md:pl-64 flex flex-col min-h-screen min-w-0">

      <!-- Top Bar -->
      <header class="h-16 flex items-center justify-between px-4 sm:px-6 bg-[#0a0f1e] border-b border-slate-800/60 sticky top-0 z-20 flex-shrink-0">
        <!-- Mobile menu toggle -->
        <button @click="isMobileSidebarOpen = true" class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-300 hover:bg-slate-800 transition-colors focus:outline-none">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <!-- Breadcrumb -->
        <div class="hidden md:flex items-center gap-2 text-xs text-slate-500">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          <span>Admin</span>
          <span class="text-slate-700">/</span>
          <span class="text-slate-300 font-medium capitalize">{{ $page.url.split('/').pop() || 'Dashboard' }}</span>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-2 ml-auto">
          <!-- Status badge -->
          <div class="hidden sm:flex items-center gap-1.5 text-xs text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 px-3 py-1.5 rounded-full">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            All Systems Operational
          </div>

          <!-- View site -->
          <a href="/" target="_blank" class="flex items-center gap-1.5 text-xs px-3 py-2 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-slate-400 hover:text-white border border-slate-700/50 transition-all duration-200">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            <span class="hidden sm:inline">Live Site</span>
          </a>

          <!-- Mobile avatar -->
          <div class="md:hidden w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-sm text-white">
            {{ userInitial }}
          </div>
        </div>
      </header>

      <!-- Flash Messages -->
      <div class="px-6 pt-4">
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
          <div v-if="flash.success" class="flex items-center gap-3 px-4 py-3 mb-0 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ flash.success }}
          </div>
        </transition>
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
          <div v-if="flash.error" class="flex items-center gap-3 px-4 py-3 mb-0 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ flash.error }}
          </div>
        </transition>
      </div>

      <!-- Page Content -->
      <main class="flex-1 p-4 sm:p-6 bg-slate-950 overflow-y-auto">
        <slot />
      </main>
    </div>

    <!-- ============================================================
         MOBILE SIDEBAR DRAWER
    ============================================================= -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="isMobileSidebarOpen" class="fixed inset-0 z-50 md:hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="isMobileSidebarOpen = false"></div>

        <!-- Drawer -->
        <aside class="absolute left-0 top-0 bottom-0 w-72 bg-[#0a0f1e] border-r border-slate-800 flex flex-col">
          <!-- Header -->
          <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              </div>
              <span class="font-black bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">RankQuill</span>
            </div>
            <button @click="isMobileSidebarOpen = false" class="p-2 rounded-lg text-slate-500 hover:text-white hover:bg-slate-800 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>

          <!-- Nav -->
          <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
            <div v-for="group in navigationGroups" :key="group.label">
              <div class="px-3 mb-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">{{ group.label }}</span>
              </div>
              <div class="space-y-0.5">
                <template v-for="item in group.items" :key="item.name">
                  <div v-if="item.href === null || item.soon" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" :d="item.icon"/></svg>
                    <span class="text-sm">{{ item.name }}</span>
                    <span class="ml-auto text-[9px] bg-slate-800 text-slate-600 px-2 py-0.5 rounded-full border border-slate-700">Soon</span>
                  </div>
                  <Link v-else :href="item.href()" @click="isMobileSidebarOpen = false" :class="['flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all', isActive(item.pattern) ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800']">
                    <svg class="w-4 h-4" :class="isActive(item.pattern) ? 'text-white' : 'text-slate-600'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" :d="item.icon"/></svg>
                    {{ item.name }}
                  </Link>
                </template>
              </div>
            </div>
          </nav>

          <!-- Logout in mobile -->
          <div class="p-3 border-t border-slate-800">
            <button @click="handleLogout" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
              Sign Out
            </button>
          </div>
        </aside>
      </div>
    </transition>
  </div>
</template>
