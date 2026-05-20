<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import axios from 'axios';

const props = defineProps({
    settings: Object,
    smtp: Object,
    scripts: Array,
    social_links: Array,
});

// Setup active tab state
const activeTab = ref('general');

const tabs = [
    { id: 'general', name: 'General & Logo', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
    { id: 'contact', name: 'Contact Info', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
    { id: 'theme', name: 'Design & Colors', icon: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-3M9.707 9.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 12l1.293-1.293z' },
    { id: 'seo', name: 'Default SEO', icon: 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z' },
    { id: 'scripts', name: 'Header/Body Scripts', icon: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4' },
    { id: 'socials', name: 'Social Media', icon: 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1' },
    { id: 'smtp', name: 'Mail Configuration', icon: 'M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5m4.5 0v3m-2.25-3v3m4.5-3v3' },
    { id: 'leads', name: 'Lead Settings', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' }
];

// Initialize Inertia form
const form = useForm({
    general: {
        site_name: props.settings.general?.site_name || '',
        site_tagline: props.settings.general?.site_tagline || '',
        maintenance_mode: props.settings.general?.maintenance_mode === 'true' || props.settings.general?.maintenance_mode === '1' || props.settings.general?.maintenance_mode === true,
        logo_url: props.settings.general?.logo_url || null,
    },
    logo: null,
    contact: {
        contact_email: props.settings.contact?.contact_email || '',
        contact_phone: props.settings.contact?.contact_phone || '',
        contact_address: props.settings.contact?.contact_address || '',
    },
    theme: {
        primary_color: props.settings.theme?.primary_color || '#6366f1',
        secondary_color: props.settings.theme?.secondary_color || '#0f172a',
        accent_color: props.settings.theme?.accent_color || '#f43f5e',
        font_primary: props.settings.theme?.font_primary || 'Outfit',
        font_secondary: props.settings.theme?.font_secondary || 'Inter',
    },
    seo: {
        default_seo_title: props.settings.seo?.default_seo_title || '',
        default_seo_description: props.settings.seo?.default_seo_description || '',
        default_seo_keywords: props.settings.seo?.default_seo_keywords || '',
        default_seo_robots: props.settings.seo?.default_seo_robots || 'index, follow',
    },
    smtp: {
        host: props.smtp?.host || '',
        port: props.smtp?.port || 587,
        username: props.smtp?.username || '',
        password: '', 
        encryption: props.smtp?.encryption || 'tls',
        from_address: props.smtp?.from_address || '',
        from_name: props.smtp?.from_name || '',
        is_active: props.smtp?.is_active || false,
    },
    scripts: props.scripts ? props.scripts.map(s => ({ ...s })) : [],
    social_links: props.social_links ? props.social_links.map(l => ({ ...l, is_active: l.is_active === 1 || l.is_active === true })) : [],
    leads: {
        lead_notification_emails: props.settings.leads?.lead_notification_emails || 'hello@rankquill.com,leads@rankquill.com',
        recaptcha_enabled: props.settings.leads?.recaptcha_enabled === 'true' || props.settings.leads?.recaptcha_enabled === '1' || props.settings.leads?.recaptcha_enabled === true,
        recaptcha_site_key: props.settings.leads?.recaptcha_site_key || '',
        recaptcha_secret_key: props.settings.leads?.recaptcha_secret_key || '',
    },
});

// Logo file handling
const logoPreview = ref(props.settings.general?.logo_url || null);
const handleLogoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

// Script List Management
const addScript = () => {
    form.scripts.push({
        id: null,
        name: '',
        placement: 'head',
        code: '',
        is_active: true,
        requires_consent: true,
    });
};
const removeScript = (index) => {
    form.scripts.splice(index, 1);
};

// Social Link List Management
const addSocialLink = () => {
    form.social_links.push({
        id: null,
        platform_name: '',
        url: '',
        icon_svg: '',
        order: form.social_links.length + 1,
        is_active: true,
    });
};
const removeSocialLink = (index) => {
    form.social_links.splice(index, 1);
};

// Submit main settings form
const submit = () => {
    const originalValue = form.leads.recaptcha_enabled;
    form.leads.recaptcha_enabled = form.leads.recaptcha_enabled ? 'true' : 'false';

    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.leads.recaptcha_enabled = originalValue;
        },
        onError: () => {
            form.leads.recaptcha_enabled = originalValue;
        }
    });
};

// SMTP Connection Verification state & methods
const isTestingSmtp = ref(false);
const testEmail = ref('');
const testResult = ref({ success: null, message: '' });

const testSmtpConnection = async () => {
    if (!testEmail.value) {
        testResult.value = { success: false, message: 'Please specify a target email address for verification.' };
        return;
    }

    isTestingSmtp.value = true;
    testResult.value = { success: null, message: '' };

    try {
        const response = await axios.post(route('admin.settings.test-smtp'), {
            host: form.smtp.host,
            port: form.smtp.port,
            username: form.smtp.username,
            password: form.smtp.password,
            encryption: form.smtp.encryption,
            from_address: form.smtp.from_address,
            from_name: form.smtp.from_name,
            test_email: testEmail.value,
        });

        testResult.value = { success: true, message: response.data.message };
    } catch (error) {
        const msg = error.response?.data?.message || 'Verification connection failed. Please double-check SMTP values.';
        testResult.value = { success: false, message: msg };
    } finally {
        isTestingSmtp.value = false;
    }
};
</script>

<template>
    <Head title="Global Settings | RankQuill Admin" />

    <AdminLayout>
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">System Settings</h1>
            <p class="text-slate-400 text-sm mt-1">Configure global application assets, brand identity, tracking scripts, and server parameters.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Navigation Tabs -->
            <div class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-slate-950/70 border border-slate-800 rounded-2xl p-4 sticky top-24 space-y-1">
                    <button
                        v-for="tab in tabs"
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        :class="[
                            activeTab === tab.id
                                ? 'bg-indigo-600/10 text-indigo-400 border-indigo-600/40'
                                : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900 border-transparent',
                            'w-full flex items-center px-4 py-3.5 text-sm font-semibold rounded-xl border transition-all duration-150 text-left'
                        ]"
                    >
                        <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="tab.icon" />
                        </svg>
                        {{ tab.name }}
                    </button>
                </div>
            </div>

            <!-- Form Workspace -->
            <div class="flex-1 bg-slate-950/70 border border-slate-800 rounded-2xl p-6 md:p-8">
                <form @submit.prevent="submit" class="space-y-8">
                    
                    <!-- TAB 1: GENERAL -->
                    <div v-show="activeTab === 'general'" class="space-y-6">
                        <div class="border-b border-slate-800 pb-4 mb-6">
                            <h3 class="text-lg font-bold text-slate-100">General Identity</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Control agency name, tagline, and maintenance state.</p>
                        </div>

                        <!-- Site Name & Tagline -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="site_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Agency Name</label>
                                <input
                                    v-model="form.general.site_name"
                                    type="text"
                                    id="site_name"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                    required
                                />
                                <span v-if="form.errors['general.site_name']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['general.site_name'] }}</span>
                            </div>

                            <div>
                                <label for="site_tagline" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Agency Tagline</label>
                                <input
                                    v-model="form.general.site_tagline"
                                    type="text"
                                    id="site_tagline"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                />
                                <span v-if="form.errors['general.site_tagline']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['general.site_tagline'] }}</span>
                            </div>
                        </div>

                        <!-- Logo Upload -->
                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-4">Agency Brand Logo</label>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <!-- Preview box -->
                                <div class="w-32 h-32 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-center overflow-hidden p-3 flex-shrink-0">
                                    <img v-if="logoPreview" :src="logoPreview" class="max-w-full max-h-full object-contain" alt="Brand Logo Preview" />
                                    <span v-else class="text-xs text-slate-600 font-medium">No Logo</span>
                                </div>

                                <!-- File Input fields -->
                                <div class="space-y-3 w-full">
                                    <div class="relative">
                                        <input
                                            type="file"
                                            id="logo_file"
                                            @change="handleLogoChange"
                                            accept="image/*"
                                            class="hidden"
                                        />
                                        <label
                                            for="logo_file"
                                            class="inline-flex items-center px-4 py-2.5 bg-slate-850 hover:bg-slate-800 border border-slate-750 text-xs font-semibold text-slate-200 rounded-xl cursor-pointer transition-colors"
                                        >
                                            <svg class="h-4 w-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            Upload Logo Image
                                        </label>
                                    </div>
                                    <p class="text-xs text-slate-500">Supports PNG, JPG, or SVG. Maximum file size 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Mode -->
                        <div class="flex items-center justify-between p-5 bg-slate-900/50 border border-slate-800 rounded-2xl">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-200">Maintenance Mode</h4>
                                <p class="text-xs text-slate-500 mt-1">Direct public visitors to a maintenance dashboard landing screen.</p>
                            </div>
                            <button
                                type="button"
                                @click="form.general.maintenance_mode = !form.general.maintenance_mode"
                                :class="[
                                    form.general.maintenance_mode ? 'bg-indigo-600' : 'bg-slate-700',
                                    'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none'
                                ]"
                            >
                                <span
                                    :class="[
                                        form.general.maintenance_mode ? 'translate-x-5' : 'translate-x-0',
                                        'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
                                    ]"
                                />
                            </button>
                        </div>
                    </div>

                    <!-- TAB 2: CONTACT -->
                    <div v-show="activeTab === 'contact'" class="space-y-6">
                        <div class="border-b border-slate-800 pb-4 mb-6">
                            <h3 class="text-lg font-bold text-slate-100">Contact Channels</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Define support credentials and geographical locations.</p>
                        </div>

                        <div>
                            <label for="contact_email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Agency Support Email</label>
                            <input
                                v-model="form.contact.contact_email"
                                type="email"
                                id="contact_email"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                            />
                            <span v-if="form.errors['contact.contact_email']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['contact.contact_email'] }}</span>
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Public Hotline / Phone</label>
                            <input
                                v-model="form.contact.contact_phone"
                                type="text"
                                id="contact_phone"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                            />
                            <span v-if="form.errors['contact.contact_phone']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['contact.contact_phone'] }}</span>
                        </div>

                        <div>
                            <label for="contact_address" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Physical Headquarters Address</label>
                            <textarea
                                v-model="form.contact.contact_address"
                                id="contact_address"
                                rows="3"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                            ></textarea>
                            <span v-if="form.errors['contact.contact_address']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['contact.contact_address'] }}</span>
                        </div>
                    </div>

                    <!-- TAB 3: THEME -->
                    <div v-show="activeTab === 'theme'" class="space-y-6">
                        <div class="border-b border-slate-800 pb-4 mb-6">
                            <h3 class="text-lg font-bold text-slate-100">Design Settings</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Control agency font families and key hex color templates.</p>
                        </div>

                        <!-- Colors Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="primary_color" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Primary Palette (Hex)</label>
                                <div class="flex items-center space-x-2">
                                    <input
                                        v-model="form.theme.primary_color"
                                        type="color"
                                        class="h-10 w-12 bg-slate-900 border border-slate-800 rounded-xl cursor-pointer p-1"
                                    />
                                    <input
                                        v-model="form.theme.primary_color"
                                        type="text"
                                        class="flex-1 px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                                    />
                                </div>
                            </div>

                            <div>
                                <label for="secondary_color" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Secondary Palette (Hex)</label>
                                <div class="flex items-center space-x-2">
                                    <input
                                        v-model="form.theme.secondary_color"
                                        type="color"
                                        class="h-10 w-12 bg-slate-900 border border-slate-800 rounded-xl cursor-pointer p-1"
                                    />
                                    <input
                                        v-model="form.theme.secondary_color"
                                        type="text"
                                        class="flex-1 px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                                    />
                                </div>
                            </div>

                            <div>
                                <label for="accent_color" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Accent / Call-to-action</label>
                                <div class="flex items-center space-x-2">
                                    <input
                                        v-model="form.theme.accent_color"
                                        type="color"
                                        class="h-10 w-12 bg-slate-900 border border-slate-800 rounded-xl cursor-pointer p-1"
                                    />
                                    <input
                                        v-model="form.theme.accent_color"
                                        type="text"
                                        class="flex-1 px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-slate-100 text-sm focus:outline-none focus:border-indigo-500"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Fonts Configuration -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-900">
                            <div>
                                <label for="font_primary" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Primary Typography (Headings)</label>
                                <select
                                    v-model="form.theme.font_primary"
                                    id="font_primary"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                >
                                    <option value="Outfit">Outfit</option>
                                    <option value="Inter">Inter</option>
                                    <option value="Roboto">Roboto</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Playfair Display">Playfair Display</option>
                                </select>
                            </div>

                            <div>
                                <label for="font_secondary" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Secondary Typography (Paragraphs)</label>
                                <select
                                    v-model="form.theme.font_secondary"
                                    id="font_secondary"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                >
                                    <option value="Inter">Inter</option>
                                    <option value="Outfit">Outfit</option>
                                    <option value="Roboto">Roboto</option>
                                    <option value="Open Sans">Open Sans</option>
                                    <option value="Lora">Lora</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: DEFAULT SEO -->
                    <div v-show="activeTab === 'seo'" class="space-y-6">
                        <div class="border-b border-slate-800 pb-4 mb-6">
                            <h3 class="text-lg font-bold text-slate-100">Fallbacks & SEO</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Control agency standard meta tags if individual page variables are absent.</p>
                        </div>

                        <div>
                            <label for="default_seo_title" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Default Title Template</label>
                            <input
                                v-model="form.seo.default_seo_title"
                                type="text"
                                id="default_seo_title"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                required
                            />
                            <span v-if="form.errors['seo.default_seo_title']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['seo.default_seo_title'] }}</span>
                        </div>

                        <div>
                            <label for="default_seo_description" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Default Meta Description</label>
                            <textarea
                                v-model="form.seo.default_seo_description"
                                id="default_seo_description"
                                rows="3"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                required
                            ></textarea>
                            <span v-if="form.errors['seo.default_seo_description']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['seo.default_seo_description'] }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="default_seo_keywords" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Default SEO Keywords</label>
                                <input
                                    v-model="form.seo.default_seo_keywords"
                                    type="text"
                                    id="default_seo_keywords"
                                    placeholder="agency, marketing, seo"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                />
                            </div>

                            <div>
                                <label for="default_seo_robots" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Search Crawler Policy (Robots)</label>
                                <select
                                    v-model="form.seo.default_seo_robots"
                                    id="default_seo_robots"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                >
                                    <option value="index, follow">index, follow (Standard)</option>
                                    <option value="noindex, follow">noindex, follow (No index, follow links)</option>
                                    <option value="noindex, nofollow">noindex, nofollow (Private settings)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: HEADER & BODY SCRIPTS -->
                    <div v-show="activeTab === 'scripts'" class="space-y-6">
                        <div class="border-b border-slate-800 pb-4 mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-100">Tracking & Custom Scripts</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Embed Google Analytics, pixel codes, and analytics scripts safely.</p>
                            </div>
                            <button
                                type="button"
                                @click="addScript"
                                class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white rounded-xl shadow-lg shadow-indigo-600/20 transition-all"
                            >
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Script Snippet
                            </button>
                        </div>

                        <div v-if="form.scripts.length === 0" class="text-center py-10 bg-slate-900/30 border border-dashed border-slate-800 rounded-2xl">
                            <svg class="h-10 w-10 text-slate-650 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            <p class="text-sm font-medium text-slate-500">No custom script blocks registered.</p>
                        </div>

                        <div v-else class="space-y-6">
                            <div
                                v-for="(script, index) in form.scripts"
                                :key="index"
                                class="p-6 bg-slate-900/60 border border-slate-800 rounded-2xl space-y-4 relative group"
                            >
                                <button
                                    type="button"
                                    @click="removeScript(index)"
                                    class="absolute top-4 right-4 p-1.5 bg-slate-950 hover:bg-rose-950/40 text-slate-500 hover:text-rose-400 border border-slate-800 hover:border-rose-900 rounded-lg transition-all"
                                    title="Delete Script"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mr-8">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Script Name</label>
                                        <input
                                            v-model="script.name"
                                            type="text"
                                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-850 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-200 text-sm transition-colors"
                                            placeholder="e.g. Meta Pixel"
                                            required
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Placement Location</label>
                                        <select
                                            v-model="script.placement"
                                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-850 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-200 text-sm transition-colors"
                                        >
                                            <option value="head">&lt;head&gt; section</option>
                                            <option value="body_start">Immediately after &lt;body&gt;</option>
                                            <option value="body_end">Just before &lt;/body&gt;</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Code Snippet (HTML/Javascript)</label>
                                    <textarea
                                        v-model="script.code"
                                        rows="4"
                                        class="w-full px-4 py-3 bg-slate-950 border border-slate-850 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-200 text-xs font-mono transition-colors"
                                        placeholder="<script>...</script>"
                                        required
                                    ></textarea>
                                </div>

                                <div class="flex items-center space-x-6 pt-2 border-t border-slate-950">
                                    <label class="inline-flex items-center space-x-2.5 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            v-model="script.is_active"
                                            class="rounded bg-slate-950 border-slate-850 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 focus:ring-offset-2 h-4 w-4"
                                        />
                                        <span class="text-xs font-medium text-slate-300">Is Active</span>
                                    </label>

                                    <label class="inline-flex items-center space-x-2.5 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            v-model="script.requires_consent"
                                            class="rounded bg-slate-950 border-slate-850 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 focus:ring-offset-2 h-4 w-4"
                                        />
                                        <span class="text-xs font-medium text-slate-300">Requires Cookie Consent</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 6: SOCIAL NETWORKS -->
                    <div v-show="activeTab === 'socials'" class="space-y-6">
                        <div class="border-b border-slate-800 pb-4 mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-100">Social Connections</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Manage references to agency accounts on social platforms.</p>
                            </div>
                            <button
                                type="button"
                                @click="addSocialLink"
                                class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white rounded-xl shadow-lg shadow-indigo-600/20 transition-all"
                            >
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Social Account
                            </button>
                        </div>

                        <div v-if="form.social_links.length === 0" class="text-center py-10 bg-slate-900/30 border border-dashed border-slate-800 rounded-2xl">
                            <svg class="h-10 w-10 text-slate-650 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <p class="text-sm font-medium text-slate-500">No social platforms linked yet.</p>
                        </div>

                        <div v-else class="space-y-4">
                            <div
                                v-for="(link, index) in form.social_links"
                                :key="index"
                                class="p-6 bg-slate-900/60 border border-slate-800 rounded-2xl flex flex-col md:flex-row items-start md:items-center gap-4 relative group"
                            >
                                <button
                                    type="button"
                                    @click="removeSocialLink(index)"
                                    class="absolute top-4 right-4 md:static p-1.5 bg-slate-950 hover:bg-rose-950/40 text-slate-500 hover:text-rose-400 border border-slate-800 hover:border-rose-900 rounded-lg transition-all md:order-last"
                                    title="Delete Platform Link"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 flex-1 w-full mr-6 md:mr-0">
                                    <div class="sm:col-span-3">
                                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1">Platform Name</label>
                                        <input
                                            v-model="link.platform_name"
                                            type="text"
                                            class="w-full px-3 py-2 bg-slate-950 border border-slate-850 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-200 text-sm"
                                            placeholder="e.g. LinkedIn"
                                            required
                                        />
                                    </div>

                                    <div class="sm:col-span-5">
                                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1">Profile / Target URL</label>
                                        <input
                                            v-model="link.url"
                                            type="url"
                                            class="w-full px-3 py-2 bg-slate-950 border border-slate-850 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-200 text-sm"
                                            placeholder="https://..."
                                            required
                                        />
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500 mb-1">Display Order</label>
                                        <input
                                            v-model.number="link.order"
                                            type="number"
                                            class="w-full px-3 py-2 bg-slate-950 border border-slate-850 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-200 text-sm"
                                            min="0"
                                            required
                                        />
                                    </div>

                                    <div class="sm:col-span-2 flex items-center pt-5 sm:pt-6">
                                        <label class="inline-flex items-center space-x-2 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                v-model="link.is_active"
                                                class="rounded bg-slate-950 border-slate-850 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 focus:ring-offset-2 h-4 w-4"
                                            />
                                            <span class="text-xs font-semibold text-slate-300">Active</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 7: SMTP SETTINGS -->
                    <div v-show="activeTab === 'smtp'" class="space-y-6">
                        <div class="border-b border-slate-800 pb-4 mb-6">
                            <h3 class="text-lg font-bold text-slate-100">Mail & SMTP Server</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Configure transaction SMTP relay details for site alerts and inquiries.</p>
                        </div>

                        <!-- Mail Server Credentials -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label for="smtp_host" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Mail Host (Server Address)</label>
                                <input
                                    v-model="form.smtp.host"
                                    type="text"
                                    id="smtp_host"
                                    placeholder="smtp.mailtrap.io"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                    required
                                />
                                <span v-if="form.errors['smtp.host']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['smtp.host'] }}</span>
                            </div>

                            <div>
                                <label for="smtp_port" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">SMTP Server Port</label>
                                <input
                                    v-model.number="form.smtp.port"
                                    type="number"
                                    id="smtp_port"
                                    placeholder="2525"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                    required
                                />
                                <span v-if="form.errors['smtp.port']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['smtp.port'] }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="smtp_username" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Server Account ID (Username)</label>
                                <input
                                    v-model="form.smtp.username"
                                    type="text"
                                    id="smtp_username"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                />
                                <span v-if="form.errors['smtp.username']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['smtp.username'] }}</span>
                            </div>

                            <div>
                                <label for="smtp_password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Secret Key / Password</label>
                                <input
                                    v-model="form.smtp.password"
                                    type="password"
                                    id="smtp_password"
                                    placeholder="••••••••••••"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                />
                                <span v-if="form.errors['smtp.password']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['smtp.password'] }}</span>
                            </div>

                            <div>
                                <label for="smtp_encryption" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Transport Encryption</label>
                                <select
                                    v-model="form.smtp.encryption"
                                    id="smtp_encryption"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                >
                                    <option value="">None (Plain)</option>
                                    <option value="tls">TLS</option>
                                    <option value="ssl">SSL</option>
                                </select>
                                <span v-if="form.errors['smtp.encryption']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['smtp.encryption'] }}</span>
                            </div>
                        </div>

                        <!-- Sender Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-900">
                            <div>
                                <label for="smtp_from_address" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Sender From Address</label>
                                <input
                                    v-model="form.smtp.from_address"
                                    type="email"
                                    id="smtp_from_address"
                                    placeholder="noreply@agency.com"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                    required
                                />
                                <span v-if="form.errors['smtp.from_address']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['smtp.from_address'] }}</span>
                            </div>

                            <div>
                                <label for="smtp_from_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Sender Display Name</label>
                                <input
                                    v-model="form.smtp.from_name"
                                    type="text"
                                    id="smtp_from_name"
                                    placeholder="RankQuill Agency"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                    required
                                />
                                <span v-if="form.errors['smtp.from_name']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['smtp.from_name'] }}</span>
                            </div>
                        </div>

                        <!-- Toggle & Test connection Block -->
                        <div class="flex items-center justify-between p-5 bg-slate-900/50 border border-slate-800 rounded-2xl">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-200">SMTP Integration Active</h4>
                                <p class="text-xs text-slate-500 mt-1">Route transactional application notifications via this configuration.</p>
                            </div>
                            <button
                                type="button"
                                @click="form.smtp.is_active = !form.smtp.is_active"
                                :class="[
                                    form.smtp.is_active ? 'bg-indigo-600' : 'bg-slate-700',
                                    'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none'
                                ]"
                            >
                                <span
                                    :class="[
                                        form.smtp.is_active ? 'translate-x-5' : 'translate-x-0',
                                        'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
                                    ]"
                                />
                            </button>
                        </div>

                        <!-- Connection Verification Tester -->
                        <div class="bg-indigo-600/5 border border-indigo-500/20 rounded-2xl p-6 space-y-4">
                            <div>
                                <h4 class="text-sm font-bold text-indigo-400">SMTP Relay Verification Test</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Send a quick test message using in-memory values before saving.</p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <input
                                    v-model="testEmail"
                                    type="email"
                                    placeholder="Enter validator email (e.g. admin@domain.com)"
                                    class="flex-1 px-4 py-2.5 bg-slate-900 border border-slate-800 focus:outline-none focus:border-indigo-500 rounded-xl text-slate-200 text-xs"
                                />
                                <button
                                    type="button"
                                    @click="testSmtpConnection"
                                    :disabled="isTestingSmtp"
                                    class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white rounded-xl shadow-lg shadow-indigo-600/25 transition-all disabled:opacity-50"
                                >
                                    <svg v-if="isTestingSmtp" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                    </svg>
                                    Verify Connection
                                </button>
                            </div>

                            <div
                                v-if="testResult.success !== null"
                                :class="[
                                    testResult.success ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-rose-500/10 border-rose-500/30 text-rose-400',
                                    'p-3.5 border rounded-xl text-xs flex items-start space-x-2'
                                ]"
                            >
                                <svg v-if="testResult.success" class="h-4.5 w-4.5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg v-else class="h-4.5 w-4.5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="leading-relaxed">{{ testResult.message }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 8: LEADS & SPAM PROTECTION -->
                    <div v-show="activeTab === 'leads'" class="space-y-6">
                        <div class="border-b border-slate-800 pb-4 mb-6">
                            <h3 class="text-lg font-bold text-slate-100">Leads & Notification Routing</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Control notification emails, honeypots, and Google reCAPTCHA configurations.</p>
                        </div>

                        <!-- Notification Recipients -->
                        <div class="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl space-y-4">
                            <div>
                                <label for="lead_notification_emails" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Notification Alert Emails (Comma Separated)</label>
                                <input
                                    v-model="form.leads.lead_notification_emails"
                                    type="text"
                                    id="lead_notification_emails"
                                    placeholder="hello@rankquill.com, notifications@rankquill.com"
                                    class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-sm transition-colors"
                                    required
                                />
                                <p class="text-[10px] text-slate-500 mt-1">Inquiry details will be relayed to all valid email addresses defined in this list.</p>
                                <span v-if="form.errors['leads.lead_notification_emails']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['leads.lead_notification_emails'] }}</span>
                            </div>
                        </div>

                        <!-- Spam Protection & reCAPTCHA -->
                        <div class="bg-slate-900/40 border border-slate-800 p-6 rounded-2xl space-y-6">
                            <div>
                                <h4 class="text-sm font-bold text-slate-200">Google reCAPTCHA Integration</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Protect lead submission endpoints against automated spam scripts.</p>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-950 border border-slate-850 rounded-xl">
                                <div>
                                    <span class="text-xs font-semibold text-slate-350 block">Enable reCAPTCHA v2</span>
                                    <span class="text-[10px] text-slate-500">Requires recaptcha field verification on frontend forms if active.</span>
                                </div>
                                <button
                                    type="button"
                                    @click="form.leads.recaptcha_enabled = !form.leads.recaptcha_enabled"
                                    :class="[
                                        form.leads.recaptcha_enabled ? 'bg-indigo-600' : 'bg-slate-700',
                                        'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none'
                                    ]"
                                >
                                    <span
                                        :class="[
                                            form.leads.recaptcha_enabled ? 'translate-x-5' : 'translate-x-0',
                                            'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
                                        ]"
                                    />
                                </button>
                            </div>

                            <div v-if="form.leads.recaptcha_enabled" class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                <div>
                                    <label for="recaptcha_site_key" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">reCAPTCHA Site Key</label>
                                    <input
                                        v-model="form.leads.recaptcha_site_key"
                                        type="text"
                                        id="recaptcha_site_key"
                                        placeholder="6Ld..."
                                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-xs transition-colors"
                                    />
                                    <span v-if="form.errors['leads.recaptcha_site_key']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['leads.recaptcha_site_key'] }}</span>
                                </div>

                                <div>
                                    <label for="recaptcha_secret_key" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">reCAPTCHA Secret Key</label>
                                    <input
                                        v-model="form.leads.recaptcha_secret_key"
                                        type="password"
                                        id="recaptcha_secret_key"
                                        placeholder="••••••••••••••••••••"
                                        class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-slate-100 text-xs transition-colors"
                                    />
                                    <span v-if="form.errors['leads.recaptcha_secret_key']" class="text-xs text-rose-500 mt-1 block">{{ form.errors['leads.recaptcha_secret_key'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT CONTROLS -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-800">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3.5 text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 disabled:opacity-50 text-white rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center space-x-2"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            <span>Save Configuration</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
