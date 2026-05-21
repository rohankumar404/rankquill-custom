<script setup>
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    code: "",
});

const submit = () => {
    form.post(route('login.2fa'));
};
</script>

<template>
    <Head title="Two-Factor Verification | RankQuill" />

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
                <div class="flex items-center space-x-3 mb-4">
                    <div class="p-2 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-xl">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-100">Security Verification</h2>
                </div>
                
                <p class="text-xs text-slate-400 leading-relaxed mb-6">
                    Two-Factor Authentication is enabled on this account. Please input the 6-digit verification code from your authenticator app.
                </p>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- OTP Code field -->
                    <div>
                        <label for="code" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Authenticator Code</label>
                        <input
                            v-model="form.code"
                            type="text"
                            id="code"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            autocomplete="one-time-code"
                            class="w-full px-4 py-3 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-slate-100 text-center text-lg font-mono tracking-[0.5em] focus:outline-none transition-colors"
                            placeholder="000000"
                            maxlength="6"
                            required
                            autofocus
                        />
                        <span v-if="form.errors.code" class="text-xs text-rose-500 mt-1 block text-center">
                            {{ form.errors.code }}
                        </span>
                    </div>

                    <!-- Submit action -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full mt-4 py-3.5 bg-indigo-600 hover:bg-indigo-500 disabled:bg-indigo-600/50 text-sm font-semibold text-white rounded-xl shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/25 active:scale-[0.99] transition-all"
                    >
                        <span v-if="form.processing">Verifying Code...</span>
                        <span v-else>Confirm & Log In</span>
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
