<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { ref, computed } from 'vue';

const props = defineProps({
    submissions: Array,
    filters: Object,
    stats: Object,
});

const { can } = usePermissions();

const deleteSubmission = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
        router.delete(`/contact-submissions/${id}`);
    }
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

const getStatusBadge = (status) => {
    const badges = {
        'unread': 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
        'read': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
        'replied': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
    };
    return badges[status] || 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300';
};

const filterByStatus = (status) => {
    router.get('/contact-submissions', { status, website: props.filters.website }, { preserveState: true });
};

const filterByWebsite = (website) => {
    router.get('/contact-submissions', { status: props.filters.status, website }, { preserveState: true });
};
</script>

<template>
    <Head title="Pesan Kontak" />

    <AdminLayout>
        <template #title>Pesan Kontak</template>

        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">Pesan Kontak Masuk</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Kelola pesan kontak dari semua portal</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 md:p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Pesan</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.total }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 md:p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Belum Dibaca</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.unread }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 md:p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Sudah Dibaca</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.read }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 md:p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Sudah Dibalas</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ stats.replied }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <select @change="filterByStatus($event.target.value)" :value="filters.status"
                class="px-4 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                <option value="all">Semua Status</option>
                <option value="unread">Belum Dibaca</option>
                <option value="read">Sudah Dibaca</option>
                <option value="replied">Sudah Dibalas</option>
            </select>

            <select @change="filterByWebsite($event.target.value)" :value="filters.website"
                class="px-4 py-2 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                <option value="all">Semua Portal</option>
                <option value="pendidikan">Pendidikan</option>
                <option value="komunitas">Komunitas</option>
                <option value="kebudayaan">Kebudayaan</option>
                <option value="datatek">DataTek</option>
            </select>
        </div>

        <!-- Submissions Table -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Daftar Pesan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Pengirim</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Portal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        <tr v-for="item in submissions" :key="item.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ item.name }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ item.email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900 dark:text-slate-200 line-clamp-2">{{ item.subject }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getBadgeColor(item.website_source)" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium capitalize">
                                    {{ item.website_source }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusBadge(item.status)" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium capitalize">
                                    {{ item.status === 'unread' ? 'Belum Dibaca' : item.status === 'read' ? 'Sudah Dibaca' : 'Sudah Dibalas' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                {{ new Date(item.created_at).toLocaleDateString('id-ID', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                }) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <Link v-if="can('view contact submissions')" :href="`/contact-submissions/${item.id}`"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat
                                    </Link>

                                    <button v-if="can('delete contact submissions')" @click="deleteSubmission(item.id)"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="submissions.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Belum ada pesan kontak</h3>
                    <p class="text-slate-500 dark:text-slate-400">Pesan kontak dari portal akan muncul di sini</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>