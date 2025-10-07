<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    nama: '',
    keterangan: '',
    is_active: true
});

const submit = () => {
    form.post('/data-master/slider-categories', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Tambah Kategori Slider" />

    <AdminLayout>
        <template #title>Tambah Kategori Slider</template>

        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">
                        Tambah Kategori Slider
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Buat kategori baru untuk mengelompokkan slider
                    </p>
                </div>
                <Link href="/data-master/slider-categories"
                    class="inline-flex items-center px-4 py-2.5 bg-slate-600 text-white font-medium text-sm rounded-xl hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </Link>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Form Kategori Slider</h3>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-6">
                <!-- Nama Kategori -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama"
                        v-model="form.nama"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-colors"
                        placeholder="Contoh: Slider Landing Page"
                        required
                    />
                    <p v-if="form.errors.nama" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.nama }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        Nama kategori akan digunakan untuk mengelompokkan slider
                    </p>
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Keterangan
                    </label>
                    <textarea 
                        id="keterangan"
                        v-model="form.keterangan"
                        rows="4"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-colors resize-none"
                        placeholder="Deskripsi singkat tentang kategori ini..."
                    ></textarea>
                    <p v-if="form.errors.keterangan" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.keterangan }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        Opsional. Berikan penjelasan tentang penggunaan kategori ini
                    </p>
                </div>

                <!-- Status Aktif -->
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            v-model="form.is_active"
                            class="w-5 h-5 text-purple-600 border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-purple-500 dark:bg-slate-700 cursor-pointer"
                        />
                        <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">
                            Aktifkan kategori
                        </span>
                    </label>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 ml-8">
                        Kategori yang aktif dapat digunakan untuk mengelompokkan slider
                    </p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <Link href="/data-master/slider-categories"
                        class="px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                        Batal
                    </Link>
                    <button 
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium text-sm rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-else class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Kategori' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>