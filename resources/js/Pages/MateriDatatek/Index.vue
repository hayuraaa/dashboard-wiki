<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { ref } from 'vue';

const props = defineProps({
    materi: Array,
});

const { can } = usePermissions();

const deleteMateri = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus materi ini?')) {
        router.delete(`/materi-datatek/${id}`);
    }
};

const getBadgeColor = (jenis) => {
    const colors = {
        'youtube': 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
        'google_slides': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
        'google_docs': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300'
    };
    return colors[jenis] || 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300';
};

const getMediaIcon = (jenis) => {
    const icons = {
        'youtube': `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
        </svg>`,
        'google_slides': `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6zm10-9H8v6h8v-6z"/>
        </svg>`,
        'google_docs': `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6zm2-6h8v2H8v-2zm0-3h8v2H8v-2zm0-3h5v2H8V8z"/>
        </svg>`
    };
    return icons[jenis] || '';
};

const getMediaLabel = (jenis) => {
    const labels = {
        'youtube': 'YouTube',
        'google_slides': 'Google Slides',
        'google_docs': 'Google Docs'
    };
    return labels[jenis] || jenis;
};
</script>

<template>
    <Head title="Materi Datatek" />

    <AdminLayout>
        <template #title>Manajemen Materi</template>

        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">
                        Daftar Materi
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Kelola materi pembelajaran dan video tutorial
                    </p>
                </div>
                <Link v-if="can('create materi')" href="/materi-datatek/create"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium text-sm rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Materi
                </Link>
            </div>
        </div>

        <!-- Information Alert -->
        <div class="mb-6 md:mb-8">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 md:p-5">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600 dark:text-blue-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm md:text-base font-semibold text-blue-900 dark:text-blue-300 mb-1">
                            Informasi
                        </h3>
                        <p class="text-sm text-blue-800 dark:text-blue-300 leading-relaxed">
                            Materi yang Anda kelola akan ditampilkan di halaman materi pada <a href="https://komunitas.wikimedia.or.id/materi" target="_blank"
                                class="font-semibold underline hover:text-blue-600 dark:hover:text-blue-200 transition-colors">
                                datatek.wikimedia.or.id
                            </a> untuk membantu pengguna mempelajari Wikidata dan proyek Wikimedia lainnya.
                        </p>
                    </div>
                    <button type="button"
                        class="flex-shrink-0 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors"
                        onclick="this.parentElement.parentElement.remove()">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Daftar Materi</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                Judul Materi
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                Jenis Media
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                Urutan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        <tr v-for="item in materi" :key="item.id"
                            class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-start">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ item.judul }}
                                        </div>
                                        <div v-if="item.keterangan" class="text-sm text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                                            {{ item.keterangan }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getBadgeColor(item.jenis_media)"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium gap-1.5"
                                    v-html="getMediaIcon(item.jenis_media)">
                                </span>
                                <span :class="getBadgeColor(item.jenis_media)" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ml-1">
                                    {{ getMediaLabel(item.jenis_media) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-900 dark:text-slate-200 font-medium">
                                    {{ item.urutan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="item.is_active"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    Aktif
                                </span>
                                <span v-else
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300">
                                    <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                    Nonaktif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <Link v-if="can('view materi')" :href="`/materi-datatek/${item.id}`"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat
                                    </Link>

                                    <Link v-if="can('edit materi')" :href="`/materi-datatek/${item.id}/edit`"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </Link>

                                    <button v-if="can('delete materi')" @click="deleteMateri(item.id)"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div v-if="materi.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">
                        Belum ada materi
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6">
                        Mulai menambahkan materi pembelajaran untuk ditampilkan
                    </p>
                    <Link href="/materi-datatek/create"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium text-sm rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Materi
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>