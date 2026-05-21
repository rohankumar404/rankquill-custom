<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    auth: Object,
});
</script>

<template>
    <Head title="Welcome to RankQuill" />
    
    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative overflow-hidden py-24 md:py-36 transition-colors duration-300">
            <!-- Background Glows -->
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/3 left-1/3 w-[300px] h-[300px] bg-purple-500/10 dark:bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <!-- Badge -->
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 mb-8 animate-pulse">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                    Tailwind CSS v4 + Vue 3 Enabled
                </span>
                
                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight mb-8 leading-[1.1] text-slate-900 dark:text-white">
                    Supercharge Your CMS <br class="hidden sm:inline" />
                    With
                    <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
                        Modern Agility
                    </span>
                </h1>
                
                <!-- Subtitle -->
                <p class="max-w-2xl mx-auto text-slate-500 dark:text-slate-400 text-lg md:text-xl font-light leading-relaxed mb-12">
                    RankQuill is a high-performance content engine designed to run at lightning speeds, optimize search structures, and deliver modern visual aesthetics out of the box.
                </p>
                
                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#contact" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-8 py-4 rounded-full transition-all duration-300 shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/35 hover:scale-[1.02] text-center">
                        Deploy Engine
                    </a>
                    <a href="#features" class="w-full sm:w-auto bg-white hover:bg-slate-50 dark:bg-slate-900 dark:hover:bg-slate-800/80 text-slate-800 dark:text-slate-200 font-semibold px-8 py-4 rounded-full transition-all duration-300 border border-slate-200 dark:border-slate-800 hover:scale-[1.02] text-center">
                        Explore Features
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Showcase Section -->
        <section id="features" class="py-20 border-t border-slate-200/50 dark:border-slate-900/50 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900 dark:text-white mb-4">
                        State-of-the-Art Architecture
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 font-light leading-relaxed">
                        Experience unmatched speeds and beautiful theme responsiveness designed for modern web apps.
                    </p>
                </div>
                
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <div class="group p-8 rounded-2xl border border-slate-200/60 dark:border-slate-900/60 bg-white/50 dark:bg-slate-900/30 hover:border-indigo-500/30 dark:hover:border-indigo-500/20 hover:bg-white dark:hover:bg-slate-900/50 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Theme Switcher</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-light">
                            Fully reactive light and dark modes syncing with localStorage and local system preferences.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="group p-8 rounded-2xl border border-slate-200/60 dark:border-slate-900/60 bg-white/50 dark:bg-slate-900/30 hover:border-indigo-500/30 dark:hover:border-indigo-500/20 hover:bg-white dark:hover:bg-slate-900/50 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Mega Menu Navigation</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-light">
                            Sleek, multi-column desktop dropdowns and responsive accordion panels on mobile.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="group p-8 rounded-2xl border border-slate-200/60 dark:border-slate-900/60 bg-white/50 dark:bg-slate-900/30 hover:border-indigo-500/30 dark:hover:border-indigo-500/20 hover:bg-white dark:hover:bg-slate-900/50 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Glassmorphism Styles</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-light">
                            Aesthetic styling with backdrop blurs, gradients, and micro-interactions.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
