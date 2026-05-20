<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    auth: Object,
});
</script>

<template>
    <Head title="Welcome" />
    
    <div class="flex items-center justify-center min-h-screen bg-slate-900 text-white font-sans">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">RankQuill Scaffolding</h1>
            <p class="text-slate-400 text-lg">Inertia.js, Vue 3, and Tailwind CSS v4 are successfully configured.</p>
        </div>
    </div>
</template>
