<script setup>
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Admin Log In | RankQuill" />

    <div class="min-h-screen bg-slate-950 flex flex-col justify-center items-center px-4 relative overflow-hidden font-sans">
        <!-- Background Decorative Blur Elements -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

        <div class="w-full max-w-md z-10">
            <!-- Branding / Logo Header -->
            <div class="text-center mb-8">
                <span class="text-3xl font-black tracking-widest bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                    RANKQUILL
                </span>
                <p class="text-slate-400 text-sm mt-2">CMS Agency Administrative Portal</p>
            </div>

            <!-- Glassmorphism Card -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-3xl p-8 backdrop-blur-xl shadow-2xl">
                <h2 class="text-xl font-bold text-slate-100 mb-6">Access Account</h2>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email field -->
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Email Address</label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            autocomplete="username"
                            class="w-full px-4 py-3 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-slate-100 text-sm focus:outline-none transition-colors"
                            placeholder="admin@rankquill.com"
                            required
                            autofocus
                        />
                        <span v-if="form.errors.email" class="text-xs text-rose-500 mt-1 block">
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <!-- Password field -->
                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            id="password"
                            autocomplete="current-password"
                            class="w-full px-4 py-3 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-slate-100 text-sm focus:outline-none transition-colors"
                            placeholder="••••••••"
                            required
                        />
                        <span v-if="form.errors.password" class="text-xs text-rose-500 mt-1 block">
                            {{ form.errors.password }}
                        </span>
                    </div>

                    <!-- Remember me checkbox -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="rounded border-slate-800 bg-slate-950 text-indigo-600 focus:ring-indigo-500/20 focus:ring-2 w-4 h-4 cursor-pointer"
                            />
                            <span class="text-xs text-slate-400 select-none">Remember this browser</span>
                        </label>
                    </div>

                    <!-- Submit action -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full mt-4 py-3.5 bg-indigo-600 hover:bg-indigo-500 disabled:bg-indigo-600/50 text-sm font-semibold text-white rounded-xl shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/25 active:scale-[0.99] transition-all"
                    >
                        <span v-if="form.processing">Authenticating...</span>
                        <span v-else>Verify & Log In</span>
                    </button>
                </form>
            </div>

            <!-- Footer links -->
            <div class="text-center mt-6 text-xs text-slate-500">
                <p>&copy; 2026 RankQuill CMS. All rights reserved.</p>
            </div>
        </div>
    </div>
</template>
