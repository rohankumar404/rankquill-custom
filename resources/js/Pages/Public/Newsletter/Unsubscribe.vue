<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    success: Boolean,
    email: String,
    message: String,
});
</script>

<template>
    <Head title="Unsubscribe | RankQuill" />

    <div class="min-h-screen bg-slate-950 flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Glow circles -->
        <div class="absolute top-1/4 left-1/4 h-96 w-96 rounded-full bg-indigo-600/10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 h-96 w-96 rounded-full bg-rose-500/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full bg-slate-900/60 border border-slate-800 rounded-3xl p-8 md:p-10 text-center shadow-2xl relative z-10 backdrop-blur-md">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-indigo-650/10 border border-indigo-500/20 text-indigo-400 mb-6">
                <svg v-if="success" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else class="h-8 w-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h1 class="text-2xl font-extrabold text-white tracking-tight">
                {{ success ? 'Unsubscribed Successfully' : 'Subscription Status' }}
            </h1>
            <p class="text-slate-400 text-sm mt-3 leading-relaxed">
                {{ message }}
            </p>
            <p v-if="success && email" class="text-slate-500 text-xs mt-2 font-mono">
                Target: {{ email }}
            </p>

            <div class="mt-8 border-t border-slate-800/80 pt-6">
                <a
                    href="/"
                    class="inline-flex items-center justify-center w-full px-6 py-3.5 bg-slate-800 hover:bg-slate-750 text-sm font-semibold text-white rounded-xl transition-all"
                >
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>
</template>
