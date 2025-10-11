<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { computed } from 'vue';

const props = defineProps({
    materi: Object,
});

const { can } = usePermissions();

const getBadgeColor = (jenis) => {
    const colors = {
        'youtube': 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
        'google_slides': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
        'google_docs': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300'
    };
    return colors[jenis] || 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300';
};

const getMediaLabel = (jenis) => {
    const labels = {
        'youtube': 'YouTube',
        'google_slides': 'Google Slides',
        'google_docs': 'Google Docs'
    };
    return labels[jenis] || jenis;
};

const embedUrl = computed(() => {
    if (props.materi.jenis_media === 'youtube') {
        const youtubeRegex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/;
        const match = props.materi.url_media.match(youtubeRegex);
        if (match && match[1]) {
            return `https://www.youtube.com/embed/${match[1]}`;
        }
    } else if (props.materi.jenis_media === 'google_slides') {
        return props.materi.url_media.replace('/edit', '/embed');
    } else if (props.materi.jenis_media === 'google_docs') {
        return props.materi.url_media.replace('/edit', '/preview');
    }
    return props.materi.url_media;
});

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
    <Head :title="materi.judul" />

    <AdminLayout>
        <template #title>Detail Materi</template>

        <!-- Breadcrumb -->
        <div class="mb-6 md:mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <Link href="/materi-datatek"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    Daftar Materi
                </Link>
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white font-medium">Detail Materi</span>
            </nav>
        </div>

        <!-- Action Buttons -->
        <div class="mb-6 flex flex-wrap gap-3">
            <Link v-if="can('edit materi kebudayaan')" :href="`/materi-datatek/${materi.id}/edit`"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl transition-colors duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Materi
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Video/Media Player -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="aspect-video bg-slate-100 dark:bg-slate-900">
                        <iframe
                            :src="embedUrl"
                            class="w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>

                <!-- Informasi Materi -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Materi</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Judul -->
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
                                {{ materi.judul }}
                            </h1>
                            <div class="flex items-center gap-2">
                                <span :class="getBadgeColor(materi.jenis_media)"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium">
                                    {{ getMediaLabel(materi.jenis_media) }}
                                </span>
                                <span v-if="materi.is_active"
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

                        <!-- Keterangan -->
                        <div v-if="materi.keterangan">
                            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi</h4>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ materi.keterangan }}
                            </p>
                        </div>

                        <!-- URL Media -->
                        <div>
                            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">URL Media</h4>
                            <a :href="materi.url_media" target="_blank" rel="noopener noreferrer"
                                class="text-blue-600 dark:text-blue-400 hover:underline break-all text-sm">
                                {{ materi.url_media }}
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
                                {{ materi.urutan }}
                            </div>
                        </div>

                        <!-- Dibuat Oleh -->
                        <div v-if="materi.creator">
                            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Dibuat Oleh</div>
                            <div class="text-base font-medium text-slate-900 dark:text-white">
                                {{ materi.creator.name }}
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ formatDate(materi.created_at) }}
                            </div>
                        </div>

                        <!-- Diupdate Oleh -->
                        <div v-if="materi.updater">
                            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Terakhir Diupdate Oleh</div>
                            <div class="text-base font-medium text-slate-900 dark:text-white">
                                {{ materi.updater.name }}
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ formatDate(materi.updated_at) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>