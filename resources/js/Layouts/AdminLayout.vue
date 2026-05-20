<script setup>
usePage
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isMobileSidebarOpen = ref(false);

const navigationItems = [
    { name: 'Dashboard', route: 'admin.dashboard', icon: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z' },
    { name: 'CMS Pages', route: '#', icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' },
    { name: 'Blog', route: '#', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h3' },
    { name: 'Services', route: '#', icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
    { name: 'Portfolio', route: '#', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' },
    { name: 'Inquiries', route: 'admin.inquiries.index', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
    { name: 'Newsletter', route: 'admin.newsletter.index', icon: 'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206' },
    { name: 'Activity Logs', route: 'admin.activity-logs', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' },
    { name: 'Settings', route: 'admin.settings.index', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' }
];

const isCurrentRoute = (itemRoute) => {
    if (itemRoute === '#') return false;
    
    // Fallback comparison based on window location mapping
    const path = window.location.pathname;
    if (itemRoute === 'admin.dashboard' && path.endsWith('/dashboard')) return true;
    if (itemRoute === 'admin.inquiries.index' && path.includes('/inquiries')) return true;
    if (itemRoute === 'admin.newsletter.index' && path.includes('/newsletter')) return true;
    if (itemRoute === 'admin.activity-logs' && path.endsWith('/activity-logs')) return true;
    if (itemRoute === 'admin.settings.index' && path.endsWith('/settings')) return true;
    
    return false;
};

const flashMessage = computed(() => page.props.flash || {});
</script>

<template>
    <div class="min-h-screen bg-slate-900 text-slate-100 flex font-sans">
        <!-- Sidebar for Desktop -->
        <aside class="hidden md:flex md:w-64 md:flex-col fixed inset-y-0 bg-slate-950 border-r border-slate-800 z-30">
            <!-- Brand header -->
            <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-950">
                <span class="text-xl font-extrabold tracking-wider bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                    RANKQUILL
                </span>
                <span class="ml-2 px-1.5 py-0.5 text-[10px] font-semibold bg-indigo-500/20 text-indigo-400 rounded-md border border-indigo-500/30">
                    CMS
                </span>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <template v-for="item in navigationItems" :key="item.name">
                    <Link 
                        :href="item.route !== '#' ? route(item.route) : '#'"
                        :class="[
                            isCurrentRoute(item.route) 
                                ? 'bg-indigo-600 text-white font-medium shadow-md shadow-indigo-600/20' 
                                : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200 transition-all duration-150',
                            'group flex items-center px-4 py-3 text-sm rounded-xl'
                        ]"
                    >
                        <svg 
                            :class="[
                                isCurrentRoute(item.route) ? 'text-white' : 'text-slate-500 group-hover:text-slate-400',
                                'mr-3 h-5 w-5 flex-shrink-0'
                            ]"
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor" 
                            stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                        </svg>
                        {{ item.name }}
                    </Link>
                </template>
            </nav>

            <!-- User Footer Panel -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center font-bold text-sm text-white">
                        A
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-semibold text-slate-200">Admin User</p>
                        <p class="text-[10px] text-slate-500 truncate">admin@rankquill.com</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 md:pl-64 flex flex-col min-w-0">
            <!-- Topbar Header -->
            <header class="h-16 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800 sticky top-0 z-20">
                <!-- Mobile sidebar toggle -->
                <button 
                    @click="isMobileSidebarOpen = true"
                    class="p-1 rounded-lg text-slate-400 hover:bg-slate-900 hover:text-slate-200 md:hidden focus:outline-none"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Breadcrumbs or Status -->
                <div class="hidden sm:flex items-center space-x-2 text-xs text-slate-500">
                    <span class="hover:text-slate-400 cursor-pointer">Admin</span>
                    <span>/</span>
                    <span class="text-slate-300 font-medium">Dashboard</span>
                </div>

                <!-- Notifications & Actions -->
                <div class="flex items-center space-x-4">
                    <button class="relative p-1.5 rounded-lg text-slate-400 hover:bg-slate-900 hover:text-slate-200 transition-colors">
                        <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-indigo-500"></span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                    
                    <a href="/" target="_blank" class="text-xs px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 transition-colors flex items-center space-x-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span>Live Site</span>
                    </a>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 md:p-8 bg-slate-900 overflow-y-auto">
                <!-- Session Alerts -->
                <div v-if="flashMessage.success" class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm flex items-center space-x-2">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ flashMessage.success }}</span>
                </div>
                <div v-if="flashMessage.error" class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm flex items-center space-x-2">
                    <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ flashMessage.error }}</span>
                </div>

                <slot />
            </main>
        </div>

        <!-- Mobile Sidebar Overlay/Drawer -->
        <div v-if="isMobileSidebarOpen" class="fixed inset-0 z-40 flex md:hidden">
            <!-- Backdrop -->
            <div @click="isMobileSidebarOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

            <!-- Drawer Container -->
            <aside class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-950 border-r border-slate-800">
                <!-- Close Button -->
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button 
                        @click="isMobileSidebarOpen = false"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                    >
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Brand -->
                <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-950">
                    <span class="text-xl font-extrabold tracking-wider bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                        RANKQUILL
                    </span>
                </div>

                <!-- Nav -->
                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    <template v-for="item in navigationItems" :key="item.name">
                        <Link 
                            :href="item.route !== '#' ? route(item.route) : '#'"
                            @click="isMobileSidebarOpen = false"
                            :class="[
                                isCurrentRoute(item.route) 
                                    ? 'bg-indigo-600 text-white font-medium' 
                                    : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200',
                                'group flex items-center px-4 py-3 text-sm rounded-xl'
                            ]"
                        >
                            <svg 
                                :class="[
                                    isCurrentRoute(item.route) ? 'text-white' : 'text-slate-500 group-hover:text-slate-400',
                                    'mr-3 h-5 w-5 flex-shrink-0'
                                ]"
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor" 
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                            </svg>
                            {{ item.name }}
                        </Link>
                    </template>
                </nav>
            </aside>
        </div>
    </div>
</template>
