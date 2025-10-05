<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslations } from '@/composables/useTranslations';

const props = defineProps({
    submission: Object,
});

const { can } = usePermissions();
const { t } = useTranslations();

const form = useForm({
    status: props.submission.status,
});

const updateStatus = (newStatus) => {
    form.status = newStatus;
    form.patch(`/contact-submissions/${props.submission.id}/status`, {
        preserveScroll: true,
    });
};

const getBadgeColor = (source) => {
    const colors = {
        'pendidikan': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
        'komunitas': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
        'kebudayaan': 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300',
        'datatek': 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300'
    };
    return colors[source] || 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300';
};

const getPortalColor = (source) => {
    const colors = {
        'pendidikan': 'from-blue-500 to-cyan-500',
        'komunitas': 'from-green-500 to-emerald-500',
        'kebudayaan': 'from-purple-500 to-pink-500',
        'datatek': 'from-orange-500 to-red-500'
    };
    return colors[source] || 'from-slate-500 to-slate-600';
};
</script>

<template>
    <Head :title="`${t('messages.message_from')} ${submission.name}`" />

    <AdminLayout>
        <template #title>{{ t('messages.message_detail') }}</template>

        <!-- Breadcrumb -->
        <div class="mb-6 md:mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <Link href="/contact-submissions"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    {{ t('messages.contact_messages') }}
                </Link>
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white font-medium">{{ t('messages.message_detail') }}</span>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Message Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700"
                        :class="`bg-gradient-to-r ${getPortalColor(submission.website_source)}`">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-white">{{ submission.subject }}</h3>
                                <p class="text-sm text-white/80 mt-1">{{ t('messages.portal') }} {{ t(`messages.${submission.website_source}`) }}</p>
                            </div>
                            <span :class="getBadgeColor(submission.website_source)" 
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium capitalize">
                                {{ t(`messages.${submission.website_source}`) }}
                            </span>
                        </div>
                    </div>

                    <!-- Sender Info -->
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">{{ t('messages.sender_name') }}</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ submission.name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Email</p>
                                <a :href="`mailto:${submission.email}`" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ submission.email }}
                                </a>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">{{ t('messages.phone_number') }}</p>
                                <a :href="`tel:${submission.phone}`" class="text-sm font-medium text-green-600 dark:text-green-400 hover:underline">
                                    {{ submission.phone }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div class="px-6 py-6">
                        <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">{{ t('messages.message_content') }}</h4>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ submission.message }}</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                            <span>{{ t('messages.received_at') }}: {{ new Date(submission.created_at).toLocaleString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            }) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.message_status') }}</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
                                {{ t('messages.change_status') }}
                            </label>
                            <div class="space-y-2">
                                <button @click="updateStatus('unread')" :disabled="!can('edit contact submissions')"
                                    :class="[
                                        'w-full flex items-center justify-between px-4 py-3 rounded-lg border-2 transition-all',
                                        form.status === 'unread' 
                                            ? 'border-red-500 bg-red-50 dark:bg-red-900/20' 
                                            : 'border-slate-200 dark:border-slate-700 hover:border-red-300 dark:hover:border-red-700',
                                        !can('edit contact submissions') ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                    ]">
                                    <span class="text-sm font-medium" :class="form.status === 'unread' ? 'text-red-700 dark:text-red-300' : 'text-slate-700 dark:text-slate-300'">
                                        {{ t('messages.unread') }}
                                    </span>
                                    <span v-if="form.status === 'unread'" class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                </button>

                                <button @click="updateStatus('read')" :disabled="!can('edit contact submissions')"
                                    :class="[
                                        'w-full flex items-center justify-between px-4 py-3 rounded-lg border-2 transition-all',
                                        form.status === 'read' 
                                            ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' 
                                            : 'border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-700',
                                        !can('edit contact submissions') ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                    ]">
                                    <span class="text-sm font-medium" :class="form.status === 'read' ? 'text-blue-700 dark:text-blue-300' : 'text-slate-700 dark:text-slate-300'">
                                        {{ t('messages.already_read') }}
                                    </span>
                                    <span v-if="form.status === 'read'" class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                </button>

                                <button @click="updateStatus('replied')" :disabled="!can('edit contact submissions')"
                                    :class="[
                                        'w-full flex items-center justify-between px-4 py-3 rounded-lg border-2 transition-all',
                                        form.status === 'replied' 
                                            ? 'border-green-500 bg-green-50 dark:bg-green-900/20' 
                                            : 'border-slate-200 dark:border-slate-700 hover:border-green-300 dark:hover:border-green-700',
                                        !can('edit contact submissions') ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                    ]">
                                    <span class="text-sm font-medium" :class="form.status === 'replied' ? 'text-green-700 dark:text-green-300' : 'text-slate-700 dark:text-slate-300'">
                                        {{ t('messages.already_replied') }}
                                    </span>
                                    <span v-if="form.status === 'replied'" class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.quick_actions') }}</h3>
                    </div>
                    
                    <div class="p-6 space-y-3">
                        <a :href="`mailto:${submission.email}?subject=Re: ${submission.subject}`"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ t('messages.reply_via_email') }}
                        </a>

                        <a :href="`https://wa.me/${submission.phone.replace(/[^0-9]/g, '')}`" target="_blank"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium text-sm rounded-xl transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            {{ t('messages.contact_via_whatsapp') }}
                        </a>

                        <Link href="/contact-submissions"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ t('messages.back_to_list') }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>