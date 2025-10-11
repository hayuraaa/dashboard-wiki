<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { computed } from 'vue';

const props = defineProps({
    jenisMedia: Array,
});

const form = useForm({
    judul: '',
    keterangan: '',
    jenis_media: 'wikimedia_commons', // Default ke Google Slides karena YouTube disabled
    url_media: '',
    is_active: true,
    urutan: 0,
});

const filteredJenisMedia = computed(() => {
    return props.jenisMedia.filter(media => media.value !== 'youtube');
});

const videoPreview = ref(null);

const handleMediaChange = () => {
    videoPreview.value = null;

    if (form.url_media && form.jenis_media === 'youtube') {
        // Extract YouTube video ID
        let videoId = '';
        const youtubeRegex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/;
        const match = form.url_media.match(youtubeRegex);

        if (match && match[1]) {
            videoId = match[1];
            videoPreview.value = `https://www.youtube.com/embed/${videoId}`;
        }
    } else if (form.url_media && form.jenis_media === 'google_slides') {
        // Google Slides embed URL
        if (form.url_media.includes('docs.google.com/presentation')) {
            videoPreview.value = form.url_media.replace('/edit', '/embed');
        }
    } else if (form.url_media && form.jenis_media === 'google_docs') {
        // Google Docs embed URL
        if (form.url_media.includes('docs.google.com/document')) {
            videoPreview.value = form.url_media.replace('/edit', '/preview');
        }
    } else if (form.url_media && form.jenis_media === 'wikimedia_commons') {
        // Jika user kasih URL lengkap PDF, langsung pakai
        if (form.url_media.includes('upload.wikimedia.org')) {
            videoPreview.value = form.url_media;
        }
        // Jika URL halaman File:, kita perlu convert
        else if (form.url_media.includes('commons.wikimedia.org/wiki/File:')) {
            // Kasih pesan ke user untuk pakai URL direct
            videoPreview.value = null;
            alert('Gunakan URL direct PDF: Klik kanan pada PDF → Copy link address');
        }
    }
};

const getMediaPlaceholder = () => {
    const placeholders = {
        'youtube': 'https://www.youtube.com/watch?v=VIDEO_ID atau https://youtu.be/VIDEO_ID',
        'google_slides': 'https://docs.google.com/presentation/d/SLIDE_ID/edit',
        'google_docs': 'https://docs.google.com/document/d/DOC_ID/edit',
        'wikimedia_commons': 'https://upload.wikimedia.org/wikipedia/commons/e/e3/Buku_Pegangan_Data_Terbuka.pdf'
    };
    return placeholders[form.jenis_media] || '';
};

const getMediaHelp = () => {
    const helps = {
        'youtube': 'Masukkan URL video YouTube (contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ)',
        'google_slides': 'Masukkan URL Google Slides yang sudah dibagikan (pastikan akses publik)',
        'google_docs': 'Masukkan URL Google Docs yang sudah dibagikan (pastikan akses publik)',
        'wikimedia_commons': 'Gunakan URL direct PDF (bukan URL halaman wiki). Cara: Buka file di Commons → Klik "Original file" → Copy URL'
    };
    return helps[form.jenis_media] || '';
};

const submit = () => {
    form.post('/media-kebudayaan');
};
</script>

<template>

    <Head title="Tambah Media Kebudayaan" />

    <AdminLayout>
        <template #title>Tambah Media Kebudayaan Baru</template>

        <!-- Breadcrumb -->
        <div class="mb-6 md:mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <Link href="/media-kebudayaan"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                Daftar Media Kebudayaan
                </Link>
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white font-medium">Tambah Media</span>
            </nav>
        </div>

        <!-- Form Container -->
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Form Header -->
            <div
                class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Media Kebudayaan</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Lengkapi data media kebudayaan</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="submit" class="p-6 space-y-6">
                <!-- Informasi Dasar Section -->
                <div class="space-y-6">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Informasi Dasar</h4>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Judul Media -->
                        <div>
                            <label for="judul"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Judul Media <span class="text-red-500">*</span>
                            </label>
                            <input id="judul" v-model="form.judul" type="text"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-purple-500 dark:focus:border-purple-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                :class="{ 'border-red-300 dark:border-red-600': form.errors.judul }"
                                placeholder="Contoh: Batik Nusantara: Warisan Budaya Indonesia" />
                            <div v-if="form.errors.judul" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.judul }}
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Keterangan
                            </label>
                            <textarea id="keterangan" v-model="form.keterangan" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-purple-500 dark:focus:border-purple-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="Deskripsi singkat tentang media kebudayaan ini"></textarea>
                            <div v-if="form.errors.keterangan" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.keterangan }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Section -->
                <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Media
                        Konten
                    </h4>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Jenis Media -->
                        <div class="lg:col-span-2">
                            <label for="jenis_media"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Jenis Media <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_media" v-model="form.jenis_media" @change="handleMediaChange"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-purple-500 dark:focus:border-purple-400 transition-colors duration-200"
                                :class="{ 'border-red-300 dark:border-red-600': form.errors.jenis_media }">
                                <option v-for="media in filteredJenisMedia" :key="media.value" :value="media.value">
                                    {{ media.label }}
                                </option>
                            </select>
                            <div v-if="form.errors.jenis_media" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.jenis_media }}
                            </div>
                        </div>

                        <!-- URL Media -->
                        <div class="lg:col-span-2">
                            <label for="url_media"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                URL Media <span class="text-red-500">*</span>
                            </label>
                            <input id="url_media" v-model="form.url_media" type="url" @input="handleMediaChange"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-purple-500 dark:focus:border-purple-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                :class="{ 'border-red-300 dark:border-red-600': form.errors.url_media }"
                                :placeholder="getMediaPlaceholder()" />
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ getMediaHelp() }}
                            </p>
                            <div v-if="form.errors.url_media" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.url_media }}
                            </div>
                        </div>

                        <!-- Preview -->
                        <div v-if="videoPreview" class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Preview Media
                            </label>
                            <div v-if="form.jenis_media === 'wikimedia_commons'"
                                class="w-full rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900"
                                style="height: 600px;">
                                <embed :src="videoPreview" type="application/pdf" class="w-full h-full" />
                            </div>
                            <div v-else
                                class="w-full aspect-video rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900">
                                <iframe :src="videoPreview" class="w-full h-full" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Tambahan Section -->
                <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Pengaturan Tambahan</h4>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Urutan -->
                        <div>
                            <label for="urutan"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Urutan Tampilan
                            </label>
                            <input id="urutan" v-model="form.urutan" type="number" min="0"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-purple-500 dark:focus:border-purple-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="0" />
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Media dengan urutan lebih kecil akan ditampilkan lebih dulu
                            </p>
                            <div v-if="form.errors.urutan" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.urutan }}
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Status
                            </label>
                            <div class="flex items-center h-12">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-purple-600">
                                    </div>
                                    <span class="ms-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{
                                        form.is_active
                                            ? 'Aktif' : 'Tidak Aktif' }}</span>
                                </label>
                            </div>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Hanya media aktif yang akan ditampilkan di halaman publik
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-slate-200 dark:border-slate-700 gap-3">
                    <Link href="/media-kebudayaan"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                    Batal
                    </Link>

                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium text-sm rounded-xl hover:from-purple-700 hover:to-pink-700 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Media</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>