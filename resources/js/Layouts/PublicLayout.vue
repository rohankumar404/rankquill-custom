<script setup>
import { ref, onMounted, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const isDark = ref(false);
const isMobileMenuOpen = ref(false);
const isMegaMenuOpen = ref(false);
const isMobileServicesOpen = ref(false);
let megaMenuTimeout = null;

const toggleTheme = () => {
    isDark.value = !isDark.value;
};

const openMegaMenu = () => {
    if (megaMenuTimeout) clearTimeout(megaMenuTimeout);
    isMegaMenuOpen.value = true;
};

const closeMegaMenu = () => {
    megaMenuTimeout = setTimeout(() => {
        isMegaMenuOpen.value = false;
    }, 150); // slight delay to prevent flickering
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
    if (isMobileMenuOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
    document.body.style.overflow = '';
};

const toggleMobileServices = () => {
    isMobileServicesOpen.value = !isMobileServicesOpen.value;
};

onMounted(() => {
    isDark.value = localStorage.getItem('theme') === 'dark' || 
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
});

watch(isDark, (newVal) => {
    if (newVal) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
});
</script>

<template>
    <div class="min-h-screen transition-colors duration-300 font-sans selection:bg-indigo-500 selection:text-white bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
        
        <!-- Header / Glass Navbar -->
        <header class="sticky top-0 z-40 w-full border-b border-slate-200/50 dark:border-slate-900/50 bg-white/70 dark:bg-slate-950/70 backdrop-blur-md transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                <!-- Logo -->
                <Link href="/" class="flex items-center space-x-2 group">
                    <span class="text-2xl font-extrabold tracking-wider bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent group-hover:opacity-90 transition-opacity">
                        RANKQUILL
                    </span>
                    <span class="px-1.5 py-0.5 text-[10px] font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-md border border-indigo-500/20">
                        PRO
                    </span>
                </Link>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center space-x-8 text-sm font-medium">
                    <Link href="/" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        Home
                    </Link>
                    
                    <!-- Services with Mega Menu -->
                    <div 
                        class="relative"
                        @mouseenter="openMegaMenu"
                        @mouseleave="closeMegaMenu"
                    >
                        <Link 
                            href="/services" 
                            class="flex items-center gap-1 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors py-2"
                        >
                            Services
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="h-3.5 w-3.5 transition-transform duration-300"
                                :class="{ 'rotate-180': isMegaMenuOpen }"
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </Link>

                        <!-- Desktop Mega Menu Dropdown -->
                        <transition
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="opacity-0 translate-y-2 scale-95"
                            enter-to-class="opacity-100 translate-y-0 scale-100"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="opacity-100 translate-y-0 scale-100"
                            leave-to-class="opacity-0 translate-y-2 scale-95"
                        >
                            <div 
                                v-show="isMegaMenuOpen"
                                class="absolute left-1/2 -translate-x-1/2 top-full w-[720px] bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-2xl p-6 backdrop-blur-xl grid grid-cols-12 gap-6 mt-1"
                            >
                                <!-- Featured Promo Box -->
                                <div class="col-span-4 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl p-5 text-white flex flex-col justify-between relative overflow-hidden shadow-lg">
                                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                                    <div>
                                        <h4 class="font-bold text-lg mb-2">Build The Future</h4>
                                        <p class="text-xs text-indigo-100 leading-relaxed">
                                            Need customized enterprise scaling and modern software solutions?
                                        </p>
                                    </div>
                                    <div class="mt-6">
                                        <button class="bg-white hover:bg-slate-100 text-indigo-600 text-xs font-semibold py-2.5 px-4 rounded-lg transition-all shadow-md">
                                            Book Strategy Call
                                        </button>
                                    </div>
                                </div>

                                <!-- Services Listing -->
                                <div class="col-span-5 space-y-4">
                                    <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Capabilities</h4>
                                    <div class="space-y-3">
                                        <Link href="/services" class="group flex items-start gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                                            <div class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-semibold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Development</h5>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">High-performance apps & platforms</p>
                                            </div>
                                        </Link>
                                        <Link href="/services" class="group flex items-start gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                                            <div class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-semibold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">SEO & Content</h5>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">Strategic ranking and optimization</p>
                                            </div>
                                        </Link>
                                        <Link href="/services" class="group flex items-start gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">
                                            <div class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-sm font-semibold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">AI & Automation</h5>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">Custom agent workflows & LLMs</p>
                                            </div>
                                        </Link>
                                    </div>
                                </div>

                                <!-- Resources Listing -->
                                <div class="col-span-3 space-y-4">
                                    <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Resources</h4>
                                    <div class="space-y-3">
                                        <Link href="/blog" class="block group">
                                            <h5 class="text-xs font-semibold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Our Blog</h5>
                                            <p class="text-[10px] text-slate-400 leading-normal">Insights & strategic industry news</p>
                                        </Link>
                                        <Link href="/portfolio" class="block group">
                                            <h5 class="text-xs font-semibold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Case Studies</h5>
                                            <p class="text-[10px] text-slate-400 leading-normal">Recent client success and deliverables</p>
                                        </Link>
                                        <a href="#newsletter" class="block group">
                                            <h5 class="text-xs font-semibold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Newsletter</h5>
                                            <p class="text-[10px] text-slate-400 leading-normal">Join 10k+ enterprise developers</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>

                    <Link href="/portfolio" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        Portfolio
                    </Link>
                    <Link href="/blog" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        Blog
                    </Link>
                </nav>

                <!-- Actions: Switcher & Button -->
                <div class="flex items-center space-x-4">
                    
                    <!-- Theme Toggle Switcher Button -->
                    <button 
                        @click="toggleTheme" 
                        class="relative p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-all duration-300 text-slate-500 dark:text-slate-400 cursor-pointer overflow-hidden group focus:outline-none"
                        aria-label="Toggle theme"
                        title="Toggle Light/Dark Theme"
                    >
                        <div class="relative w-5 h-5 flex items-center justify-center">
                            <!-- Sun SVG (visible in light mode, hidden in dark) -->
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="h-5 w-5 absolute transition-all duration-500 transform group-hover:rotate-45"
                                :class="isDark ? 'opacity-0 scale-50 rotate-90' : 'opacity-100 scale-100 rotate-0'"
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                            
                            <!-- Moon SVG (visible in dark mode, hidden in light) -->
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="h-5 w-5 absolute transition-all duration-500 transform group-hover:-rotate-12"
                                :class="isDark ? 'opacity-100 scale-100 rotate-0' : 'opacity-0 scale-50 -rotate-90'"
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </div>
                    </button>

                    <!-- Contact / Action Button -->
                    <a 
                        href="#contact" 
                        class="hidden sm:inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-5 py-2.5 rounded-full transition-all duration-300 shadow-md shadow-indigo-600/10 hover:scale-[1.02] cursor-pointer"
                    >
                        Get Started
                    </a>

                    <!-- Mobile Hamburger Button -->
                    <button 
                        @click="toggleMobileMenu" 
                        class="p-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 md:hidden hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors focus:outline-none"
                        aria-label="Toggle menu"
                    >
                        <div class="relative w-6 h-6 flex flex-col justify-center items-center">
                            <span 
                                class="block w-5 h-0.5 bg-current rounded transition-all duration-300"
                                :class="isMobileMenuOpen ? 'rotate-45 translate-y-1.5' : '-translate-y-1'"
                            ></span>
                            <span 
                                class="block w-5 h-0.5 bg-current rounded my-0.5 transition-all duration-300"
                                :class="isMobileMenuOpen ? 'opacity-0' : 'opacity-100'"
                            ></span>
                            <span 
                                class="block w-5 h-0.5 bg-current rounded transition-all duration-300"
                                :class="isMobileMenuOpen ? '-rotate-45 -translate-y-1.5' : 'translate-y-1'"
                            ></span>
                        </div>
                    </button>
                </div>
            </div>
        </header>

        <!-- Mobile Drawer Overlay and Navigation -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-show="isMobileMenuOpen"
                class="fixed inset-0 z-50 md:hidden flex"
            >
                <!-- Backdrop -->
                <div 
                    class="fixed inset-0 bg-slate-950/60 dark:bg-slate-950/80 backdrop-blur-sm"
                    @click="closeMobileMenu"
                ></div>

                <!-- Drawer content -->
                <div 
                    class="relative ml-auto w-full max-w-sm h-full bg-white/95 dark:bg-slate-950/95 border-l border-slate-200/50 dark:border-slate-900/50 shadow-2xl flex flex-col p-6 backdrop-blur-xl transition-all duration-300 transform"
                    :class="isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'"
                >
                    <!-- Close button in drawer -->
                    <div class="flex items-center justify-between pb-6 border-b border-slate-100 dark:border-slate-900">
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">Menu</span>
                        <button 
                            @click="closeMobileMenu"
                            class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-900 text-slate-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Links List -->
                    <nav class="flex-1 py-8 space-y-6 overflow-y-auto">
                        <Link 
                            href="/" 
                            @click="closeMobileMenu"
                            class="block text-lg font-semibold hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                        >
                            Home
                        </Link>
                        
                        <!-- Accordion for Services on mobile -->
                        <div>
                            <button 
                                @click="toggleMobileServices"
                                class="w-full flex items-center justify-between text-lg font-semibold hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                            >
                                Services
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5 transition-transform duration-300"
                                    :class="{ 'rotate-180': isMobileServicesOpen }"
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div 
                                v-show="isMobileServicesOpen"
                                class="pl-4 mt-3 space-y-3 border-l border-slate-200 dark:border-slate-800 transition-all duration-300"
                            >
                                <Link 
                                    href="/services" 
                                    @click="closeMobileMenu"
                                    class="block text-sm text-slate-500 hover:text-slate-800 dark:hover:text-slate-200"
                                >
                                    All Services
                                </Link>
                                <Link 
                                    href="/services" 
                                    @click="closeMobileMenu"
                                    class="block text-sm text-slate-500 hover:text-slate-800 dark:hover:text-slate-200"
                                >
                                    Development
                                </Link>
                                <Link 
                                    href="/services" 
                                    @click="closeMobileMenu"
                                    class="block text-sm text-slate-500 hover:text-slate-800 dark:hover:text-slate-200"
                                >
                                    SEO & Content
                                </Link>
                                <Link 
                                    href="/services" 
                                    @click="closeMobileMenu"
                                    class="block text-sm text-slate-500 hover:text-slate-800 dark:hover:text-slate-200"
                                >
                                    AI & Automation
                                </Link>
                            </div>
                        </div>

                        <Link 
                            href="/portfolio" 
                            @click="closeMobileMenu"
                            class="block text-lg font-semibold hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                        >
                            Portfolio
                        </Link>
                        <Link 
                            href="/blog" 
                            @click="closeMobileMenu"
                            class="block text-lg font-semibold hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                        >
                            Blog
                        </Link>
                    </nav>

                    <!-- Drawer footer buttons -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-900 space-y-4">
                        <a 
                            href="#contact" 
                            @click="closeMobileMenu"
                            class="block w-full text-center bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 rounded-full transition-all duration-300 shadow-md"
                        >
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Slot / Page Content -->
        <main class="relative z-10">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200/50 dark:border-slate-900/50 bg-white dark:bg-slate-950 transition-colors duration-300 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                    <div class="space-y-4">
                        <Link href="/" class="flex items-center space-x-2">
                            <span class="text-xl font-extrabold tracking-wider bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
                                RANKQUILL
                            </span>
                        </Link>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            A high-fidelity modern content management engine designed for fast deployment, premium visual delivery, and strategic search growth.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">Platform</h4>
                        <ul class="space-y-2.5 text-xs text-slate-400">
                            <li><Link href="/" class="hover:text-indigo-500 transition-colors">Home</Link></li>
                            <li><Link href="/services" class="hover:text-indigo-500 transition-colors">Services</Link></li>
                            <li><Link href="/portfolio" class="hover:text-indigo-500 transition-colors">Portfolio</Link></li>
                            <li><Link href="/blog" class="hover:text-indigo-500 transition-colors">Blog</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">Enterprise</h4>
                        <ul class="space-y-2.5 text-xs text-slate-400">
                            <li><a href="#" class="hover:text-indigo-500 transition-colors">Solutions</a></li>
                            <li><a href="#" class="hover:text-indigo-500 transition-colors">Case Studies</a></li>
                            <li><a href="#" class="hover:text-indigo-500 transition-colors">Security Audit</a></li>
                            <li><a href="#" class="hover:text-indigo-500 transition-colors">SLA Status</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4">Subscribe</h4>
                        <p class="text-xs text-slate-400 mb-3 leading-relaxed">
                            Stay up-to-date with our latest technology strategies and newsletter.
                        </p>
                        <form class="flex gap-2">
                            <input 
                                type="email" 
                                placeholder="Enter email"
                                class="bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-indigo-500 flex-1"
                                required
                            />
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg px-4 py-2 text-xs font-semibold transition-all">
                                Go
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Copyright row -->
                <div class="pt-8 border-t border-slate-100 dark:border-slate-900 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                    <div>
                        &copy; 2026 RankQuill Premium Engine. All rights reserved.
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="hover:text-indigo-500 transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-indigo-500 transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>
</template>
