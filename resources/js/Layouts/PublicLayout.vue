<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isDark = ref(false);
const isMobileMenuOpen = ref(false);
const isMegaMenuOpen = ref(false);
const isMobileServicesOpen = ref(false);
const isScrolled = ref(false);
let megaMenuTimeout = null;

const currentPath = computed(() => {
  if (typeof window !== 'undefined') return window.location.pathname;
  return '';
});

const isActive = (path) => currentPath.value === path || currentPath.value.startsWith(path + '/');

const toggleTheme = () => {
  isDark.value = !isDark.value;
};

const openMegaMenu = () => {
  if (megaMenuTimeout) clearTimeout(megaMenuTimeout);
  isMegaMenuOpen.value = true;
};

const closeMegaMenu = () => {
  megaMenuTimeout = setTimeout(() => { isMegaMenuOpen.value = false; }, 200);
};

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
  document.body.style.overflow = isMobileMenuOpen.value ? 'hidden' : '';
};

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
  document.body.style.overflow = '';
};

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
  const saved = localStorage.getItem('theme');
  isDark.value = saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches);
  window.addEventListener('scroll', handleScroll, { passive: true });
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

watch(isDark, (val) => {
  if (val) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
}, { immediate: true });
</script>

<template>
  <div class="min-h-screen font-sans bg-slate-50 dark:bg-[#070B14] text-slate-800 dark:text-slate-100 transition-colors duration-300 selection:bg-indigo-500 selection:text-white">

    <!-- ======================================================
         HEADER / NAVBAR
    ======================================================= -->
    <header
      :class="[
        'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
        isScrolled
          ? 'bg-white/95 dark:bg-[#070B14]/95 backdrop-blur-xl shadow-lg shadow-slate-200/20 dark:shadow-slate-950/50 border-b border-slate-200/50 dark:border-slate-800/50'
          : 'bg-transparent'
      ]"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18 py-4">

          <!-- Logo -->
          <Link href="/" class="flex items-center gap-2.5 group flex-shrink-0">
            <div class="relative">
              <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-600/30">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
            </div>
            <div>
              <span class="text-lg font-black tracking-tight bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
                RankQuill
              </span>
              <span class="hidden sm:inline ml-1.5 text-[9px] font-bold bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded-md border border-indigo-200 dark:border-indigo-500/30 uppercase tracking-wider">
                Pro
              </span>
            </div>
          </Link>

          <!-- Desktop Nav -->
          <nav class="hidden lg:flex items-center gap-1">
            <Link href="/" :class="['px-3 py-2 rounded-lg text-sm font-medium transition-colors', isActive('/') && currentPath === '/' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50']">
              Home
            </Link>

            <!-- Services Mega Menu -->
            <div class="relative" @mouseenter="openMegaMenu" @mouseleave="closeMegaMenu">
              <Link href="/services" :class="['px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-1', isActive('/services') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50']">
                Services
                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="isMegaMenuOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </Link>

              <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 translate-y-2 scale-95" enter-to-class="opacity-100 translate-y-0 scale-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 translate-y-0 scale-100" leave-to-class="opacity-0 translate-y-2 scale-95">
                <div v-show="isMegaMenuOpen" class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-[680px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl shadow-slate-900/20 overflow-hidden">
                  <div class="grid grid-cols-3 gap-0">
                    <!-- Promo -->
                    <div class="col-span-1 bg-gradient-to-br from-indigo-600 to-purple-700 p-6 flex flex-col justify-between">
                      <div>
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                          <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h4 class="font-bold text-white text-sm mb-1">Full-Stack Digital Agency</h4>
                        <p class="text-indigo-100 text-xs leading-relaxed">Enterprise solutions engineered for results.</p>
                      </div>
                      <Link href="/contact-us" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition-colors">
                        Book Strategy Call
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </Link>
                    </div>
                    <!-- Service Links -->
                    <div class="col-span-2 p-6 grid grid-cols-2 gap-3">
                      <Link href="/services/web-development" class="group flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <div><p class="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">Web Development</p><p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Laravel, Vue, Next.js</p></div>
                      </Link>
                      <Link href="/services/seo-optimization" class="group flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 bg-green-500/10 text-green-600 dark:text-green-400 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-green-500 group-hover:text-white transition-colors">
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div><p class="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400">SEO Optimization</p><p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Technical, On-Page, Links</p></div>
                      </Link>
                      <Link href="/services/app-development" class="group flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div><p class="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400">App Development</p><p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">iOS, Android, PWA</p></div>
                      </Link>
                      <Link href="/services/ppc-campaigns" class="group flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 bg-orange-500/10 text-orange-600 dark:text-orange-400 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <div><p class="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400">PPC Campaigns</p><p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Google, Meta, LinkedIn</p></div>
                      </Link>
                      <Link href="/services/graphic-design" class="group flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 bg-pink-500/10 text-pink-600 dark:text-pink-400 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-pink-500 group-hover:text-white transition-colors">
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        </div>
                        <div><p class="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-pink-600 dark:group-hover:text-pink-400">Graphic Design</p><p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Brand, UI/UX, Motion</p></div>
                      </Link>
                      <Link href="/services/ai-solutions" class="group flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="w-8 h-8 bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-cyan-500 group-hover:text-white transition-colors">
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div><p class="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400">AI Solutions</p><p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">LLM, Agents, Automation</p></div>
                      </Link>
                    </div>
                  </div>
                </div>
              </transition>
            </div>

            <Link href="/portfolio" :class="['px-3 py-2 rounded-lg text-sm font-medium transition-colors', isActive('/portfolio') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50']">Portfolio</Link>
            <Link href="/blog" :class="['px-3 py-2 rounded-lg text-sm font-medium transition-colors', isActive('/blog') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50']">Blog</Link>
            <Link href="/about-us" :class="['px-3 py-2 rounded-lg text-sm font-medium transition-colors', isActive('/about-us') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/50']">About</Link>
          </nav>

          <!-- Right Actions -->
          <div class="flex items-center gap-2">
            <!-- Theme Toggle -->
            <button @click="toggleTheme" class="p-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition-all duration-300 focus:outline-none" :title="isDark ? 'Switch to Light' : 'Switch to Dark'">
              <svg v-if="isDark" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
              <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>

            <!-- CTA Button -->
            <Link href="/contact-us" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-full transition-all duration-300 shadow-md shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:scale-[1.02]">
              Get Started
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </Link>

            <!-- Mobile Menu Toggle -->
            <button @click="toggleMobileMenu" class="lg:hidden p-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 transition-colors focus:outline-none" aria-label="Menu">
              <svg v-if="!isMobileMenuOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
              <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- ======================================================
         MOBILE DRAWER
    ======================================================= -->
    <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-show="isMobileMenuOpen" class="fixed inset-0 z-40 lg:hidden">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="closeMobileMenu"></div>
        <div class="absolute right-0 top-0 bottom-0 w-80 bg-white dark:bg-slate-950 border-l border-slate-200 dark:border-slate-800 shadow-2xl flex flex-col">
          <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-900">
            <span class="font-bold text-lg bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Navigation</span>
            <button @click="closeMobileMenu" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900 text-slate-500 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <nav class="flex-1 p-5 space-y-1 overflow-y-auto">
            <Link href="/" @click="closeMobileMenu" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
              Home
            </Link>

            <!-- Services Accordion -->
            <div>
              <button @click="isMobileServicesOpen = !isMobileServicesOpen" class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                <div class="flex items-center gap-3">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  Services
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="isMobileServicesOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div v-show="isMobileServicesOpen" class="pl-10 mt-1 space-y-1">
                <Link href="/services" @click="closeMobileMenu" class="block py-2 px-3 text-xs text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors rounded-lg hover:bg-slate-50 dark:hover:bg-slate-900">All Services</Link>
                <Link href="/services/web-development" @click="closeMobileMenu" class="block py-2 px-3 text-xs text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors rounded-lg hover:bg-slate-50 dark:hover:bg-slate-900">Web Development</Link>
                <Link href="/services/seo-optimization" @click="closeMobileMenu" class="block py-2 px-3 text-xs text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors rounded-lg hover:bg-slate-50 dark:hover:bg-slate-900">SEO Optimization</Link>
                <Link href="/services/ai-solutions" @click="closeMobileMenu" class="block py-2 px-3 text-xs text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors rounded-lg hover:bg-slate-50 dark:hover:bg-slate-900">AI Solutions</Link>
              </div>
            </div>

            <Link href="/portfolio" @click="closeMobileMenu" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              Portfolio
            </Link>
            <Link href="/blog" @click="closeMobileMenu" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
              Blog
            </Link>
            <Link href="/about-us" @click="closeMobileMenu" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              About Us
            </Link>
            <Link href="/contact-us" @click="closeMobileMenu" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              Contact
            </Link>
          </nav>
          <div class="p-5 border-t border-slate-100 dark:border-slate-900">
            <Link href="/contact-us" @click="closeMobileMenu" class="block w-full text-center bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold px-6 py-3 rounded-full transition-all duration-300 shadow-lg shadow-indigo-600/20">
              Get a Free Strategy Call →
            </Link>
          </div>
        </div>
      </div>
    </transition>

    <!-- ======================================================
         MAIN CONTENT (with top padding for fixed header)
    ======================================================= -->
    <main class="pt-[72px]">
      <slot />
    </main>

    <!-- ======================================================
         FOOTER
    ======================================================= -->
    <footer class="bg-slate-950 text-slate-400 border-t border-slate-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
          <!-- Brand Column -->
          <div class="lg:col-span-2">
            <Link href="/" class="flex items-center gap-2.5 mb-5">
              <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              </div>
              <span class="text-lg font-black text-white">RankQuill</span>
            </Link>
            <p class="text-sm leading-relaxed text-slate-500 max-w-xs mb-6">
              Premium digital agency engineering search-optimized platforms for enterprise brands worldwide.
            </p>
            <div class="flex items-center gap-3">
              <a href="https://twitter.com/rankquill" target="_blank" class="w-9 h-9 bg-slate-900 hover:bg-indigo-600 border border-slate-800 hover:border-indigo-500 rounded-lg flex items-center justify-center text-slate-500 hover:text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
              </a>
              <a href="https://linkedin.com/company/rankquill" target="_blank" class="w-9 h-9 bg-slate-900 hover:bg-indigo-600 border border-slate-800 hover:border-indigo-500 rounded-lg flex items-center justify-center text-slate-500 hover:text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
              </a>
              <a href="https://github.com/rankquill" target="_blank" class="w-9 h-9 bg-slate-900 hover:bg-indigo-600 border border-slate-800 hover:border-indigo-500 rounded-lg flex items-center justify-center text-slate-500 hover:text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
              </a>
            </div>
          </div>

          <!-- Company Links -->
          <div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-300 mb-4">Company</h4>
            <ul class="space-y-3">
              <li><Link href="/about-us" class="text-sm hover:text-white transition-colors">About Us</Link></li>
              <li><Link href="/portfolio" class="text-sm hover:text-white transition-colors">Portfolio</Link></li>
              <li><Link href="/blog" class="text-sm hover:text-white transition-colors">Blog</Link></li>
              <li><Link href="/contact-us" class="text-sm hover:text-white transition-colors">Contact</Link></li>
            </ul>
          </div>

          <!-- Services Links -->
          <div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-300 mb-4">Services</h4>
            <ul class="space-y-3">
              <li><Link href="/services/web-development" class="text-sm hover:text-white transition-colors">Web Development</Link></li>
              <li><Link href="/services/seo-optimization" class="text-sm hover:text-white transition-colors">SEO Optimization ✨</Link></li>
              <li><Link href="/services/app-development" class="text-sm hover:text-white transition-colors">App Development</Link></li>
              <li><Link href="/services/ai-solutions" class="text-sm hover:text-white transition-colors">AI Solutions</Link></li>
            </ul>
          </div>

          <!-- Newsletter -->
          <div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-300 mb-4">Newsletter</h4>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">Weekly insights on SEO, AI & growth strategies.</p>
            <form class="flex gap-2">
              <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 bg-slate-900 border border-slate-800 rounded-lg text-sm text-slate-300 placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors min-w-0"/>
              <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm transition-colors flex-shrink-0">→</button>
            </form>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-slate-900 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p class="text-xs text-slate-600">© 2026 RankQuill. All rights reserved. Built with ❤️ using Laravel + Vue.</p>
          <div class="flex items-center gap-5 text-xs text-slate-600">
            <a href="#" class="hover:text-slate-400 transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-slate-400 transition-colors">Terms of Service</a>
            <a href="/sitemap.xml" class="hover:text-slate-400 transition-colors">Sitemap</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>
