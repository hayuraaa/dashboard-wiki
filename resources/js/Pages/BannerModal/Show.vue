<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';

const props = defineProps({
    banner: Object,
});

const { can } = usePermissions();

const getBadgeColor = (tipe) => {
    const colors = {
        'event': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
        'lomba': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
        'kegiatan': 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300',
        'pengumuman': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
        'konferensi': 'bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-300',
        'lainnya': 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300'
    };
    return colors[tipe] || 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300';
};

const getTipeLabel = (tipe) => {
    const labels = {
        'event': 'Event',
        'lomba': 'Lomba',
        'kegiatan': 'Kegiatan',
        'pengumuman': 'Pengumuman',
        'konferensi': 'Konferensi',
        'lainnya': 'Lainnya'
    };
    return labels[tipe] || tipe;
};

const getTargetSitusLabel = (targetSitus) => {
    if (!targetSitus || targetSitus.length === 0) {
        return 'Semua Situs';
    }
    const labels = {
        'datatek': 'Data & Teknologi',
        'komunitas': 'Komunitas',
        'pendidikan': 'Pendidikan',
        'kebudayaan': 'Kebudayaan'
    };
    return targetSitus.map(s => labels[s] || s).join(', ');
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head :title="banner.judul" />

    <AdminLayout>
        <template #title>Detail Banner Modal</template>

        <!-- Breadcrumb -->
        <div class="mb-6 md:mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <Link href="/banner-modals"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    Daftar Banner Modal
                </Link>
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white font-medium">Detail Banner</span>
            </nav>
        </div>

        <!-- Action Buttons -->
        <div class="mb-6 flex flex-wrap gap-3">
            <Link v-if="can('edit banner modal')" :href="`/banner-modals/${banner.id}/edit`"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl transition-colors duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Banner
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Banner Image -->
                <div v-if="banner.gambar" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <img :src="`/storage/${banner.gambar}`" :alt="banner.judul" class="w-full h-auto">
                </div>

                <!-- Informasi Banner -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Banner</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Judul -->
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
                                {{ banner.judul }}
                            </h1>
                            <div class="flex items-center gap-2">
                                <span :class="getBadgeColor(banner.tipe)"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium">
                                    {{ getTipeLabel(banner.tipe) }}
                                </span>
                                <span v-if="banner.is_active"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    Aktif
                                </span>
                                <span v-else
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300">
                                    <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                    Nonaktif
                                </span>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div v-if="banner.deskripsi">
                            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi</h4>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ banner.deskripsi }}
                            </p>
                        </div>

                        <!-- Pranala -->
                        <div v-if="banner.url_pranala || banner.text_tombol" class="pt-4 border-t border-slate-200 dark:border-slate-700">
                            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Pranala Luar</h4>
                            <div class="space-y-2">
                                <div v-if="banner.url_pranala">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">URL:</span>
                                    <a :href="banner.url_pranala" target="_blank" rel="noopener noreferrer"
                                        class="text-blue-600 dark:text-blue-400 hover:underline break-all text-sm block mt-1">
                                        {{ banner.url_pranala }}
                                    </a>
                                </div>
                                <div v-if="banner.text_tombol">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Text Tombol:</span>
                                    <p class="text-slate-900 dark:text-white font-medium mt-1">{{ banner.text_tombol }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Tombol -->
                        <div v-if="banner.url_pranala && banner.text_tombol" class="pt-4">
                            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Preview Tombol</h4>
                            <a :href="banner.url_pranala" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md">
                                {{ banner.text_tombol }}
                                <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Detail Informasi -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Detail</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Urutan -->
                        <div>
                            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Urutan Tampilan</div>
                            <div class="text-base font-semibold text-slate-900 dark:text-white">
                                {{ banner.urutan }}
                            </div>
                        </div>

                        <!-- Target Situs -->
                        <div>
                            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Target Situs</div>
                            <div class="text-base font-medium text-slate-900 dark:text-white">
                                {{ getTargetSitusLabel(banner.target_situs) }}
                            </div>
                        </div>

                        <!-- Dibuat Oleh -->
                        <div v-if="banner.creator" class="pt-4 border-t border-slate-200 dark:border-slate-700">
                            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Dibuat Oleh</div>
                            <div class="text-base font-medium text-slate-900 dark:text-white">
                                {{ banner.creator.name }}
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ formatDate(banner.created_at) }}
                            </div>
                        </div>

                        <!-- Diupdate Oleh -->
                        <div v-if="banner.updater">
                            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Terakhir Diupdate Oleh</div>
                            <div class="text-base font-medium text-slate-900 dark:text-white">
                                {{ banner.updater.name }}
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ formatDate(banner.updated_at) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>