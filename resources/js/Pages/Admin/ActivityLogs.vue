<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

defineProps({
    logs: Array,
});
</script>

<template>
    <Head title="Activity Audits" />

    <AdminLayout>
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-100">Activity Audits</h1>
                <p class="text-slate-400 text-sm">System audit log of model activities and user operations.</p>
            </div>
        </div>

        <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <!-- Table Header -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 border-b border-slate-800 text-[10px] uppercase font-semibold tracking-wider text-slate-400">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Log Channel</th>
                            <th class="py-4 px-6">Action Traced</th>
                            <th class="py-4 px-6">Performed By</th>
                            <th class="py-4 px-6">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-300 text-sm">
                        <tr v-if="logs.length === 0">
                            <td colspan="5" class="py-8 text-center text-slate-500 font-medium italic">
                                No activity audit traces found.
                            </td>
                        </tr>
                        <tr 
                            v-for="log in logs" 
                            :key="log.id"
                            class="hover:bg-slate-900/30 transition-colors"
                        >
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ log.id }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-slate-800 text-slate-400 border border-slate-700">
                                    {{ log.log_name || 'default' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-200">{{ log.description }}</td>
                            <td class="py-4 px-6 flex items-center space-x-2">
                                <div class="h-6 w-6 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xs font-semibold">
                                    {{ log.causer_name[0] }}
                                </div>
                                <span>{{ log.causer_name }}</span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-400 font-mono">{{ log.created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
