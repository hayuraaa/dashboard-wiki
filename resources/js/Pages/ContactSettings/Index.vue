<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslations } from '@/composables/useTranslations';

const props = defineProps({
    settings: Array,
});

const { can } = usePermissions();
const { t } = useTranslations();

const getBadgeColor = (source) => {
    const colors = {
        'pendidikan': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
        'komunitas': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
        'kebudayaan': 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300',
        'datatek': 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300'
    };
    return colors[source] || 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300';
};
</script>

<template>
    <Head :title="t('messages.contact_settings')" />

    <AdminLayout>
        <template #title>{{ t('messages.contact_settings') }}</template>

        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">{{ t('messages.contact_email_settings') }}</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ t('messages.manage_portal_emails') }}</p>
                </div>
            </div>
        </div>

        <!-- Settings Table -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.portal_settings') }}</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">{{ t('messages.portal') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">{{ t('messages.recipient_email') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">{{ t('messages.cc_emails') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">{{ t('messages.status') }}</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">{{ t('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        <tr v-for="setting in settings" :key="setting.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3"
                                        :class="setting.website_source === 'pendidikan' ? 'bg-blue-100 dark:bg-blue-900/30' :
                                                setting.website_source === 'komunitas' ? 'bg-green-100 dark:bg-green-900/30' :
                                                setting.website_source === 'kebudayaan' ? 'bg-purple-100 dark:bg-purple-900/30' :
                                                'bg-orange-100 dark:bg-orange-900/30'">
                                        <svg class="w-5 h-5" :class="setting.website_source === 'pendidikan' ? 'text-blue-600 dark:text-blue-400' :
                                                setting.website_source === 'komunitas' ? 'text-green-600 dark:text-green-400' :
                                                setting.website_source === 'kebudayaan' ? 'text-purple-600 dark:text-purple-400' :
                                                'text-orange-600 dark:text-orange-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900 dark:text-white capitalize">{{ t(`messages.${setting.website_source}`) }}</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">{{ setting.website_source }}.wikimedia.or.id</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 dark:text-slate-200">{{ setting.recipient_email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="setting.cc_emails && setting.cc_emails.length > 0" class="flex flex-wrap gap-1">
                                    <span v-for="(email, index) in setting.cc_emails" :key="index"
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                        {{ email }}
                                    </span>
                                </div>
                                <span v-else class="text-xs text-slate-400 dark:text-slate-500 italic">{{ t('messages.no_cc') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span v-if="setting.is_active" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        {{ t('messages.email_active') }}
                                    </span>
                                    <span v-else class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300">
                                        <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                        {{ t('messages.email_inactive') }}
                                    </span>
                                    <br>
                                    <span v-if="setting.send_auto_reply" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                                        </svg>
                                        {{ t('messages.auto_reply') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <Link v-if="can('edit contact settings')" :href="`/contact-settings/${setting.id}/edit`"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ t('messages.edit') }}
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>