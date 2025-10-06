<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tipeBanner: Array,
    targetSitusOptions: Array,
});

const form = useForm({
    judul: '',
    deskripsi: '',
    gambar: null,
    url_pranala: '',
    text_tombol: '',
    tipe: 'pengumuman',
    target_situs: [],
    is_active: true,
    urutan: 0,
});

const gambarPreview = ref(null);

const handleGambarUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.gambar = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            gambarPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeGambar = () => {
    form.gambar = null;
    gambarPreview.value = null;
    const fileInput = document.getElementById('gambar');
    if (fileInput) fileInput.value = '';
};

const toggleSitus = (situsValue) => {
    const index = form.target_situs.indexOf(situsValue);
    if (index > -1) {
        form.target_situs.splice(index, 1);
    } else {
        form.target_situs.push(situsValue);
    }
};

const submit = () => {
    form.post('/banner-modals');
};
</script>

<template>
    <Head title="Tambah Banner Modal" />

    <AdminLayout>
        <template #title>Tambah Banner Modal Baru</template>

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
                <span class="text-slate-900 dark:text-white font-medium">Tambah Banner</span>
            </nav>
        </div>

        <!-- Form Container -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Banner</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Lengkapi data banner modal</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="submit" class="p-6 space-y-6">
                <!-- Informasi Dasar Section -->
                <div class="space-y-6">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Informasi Dasar</h4>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Judul -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Judul Banner <span class="text-red-500">*</span>
                            </label>
                            <input id="judul" v-model="form.judul" type="text"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                :class="{ 'border-red-300 dark:border-red-600': form.errors.judul }"
                                placeholder="Contoh: Lomba Menulis Artikel Wikipedia 2025" />
                            <div v-if="form.errors.judul" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.judul }}
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" v-model="form.deskripsi" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="Deskripsi singkat tentang banner ini"></textarea>
                            <div v-if="form.errors.deskripsi" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.deskripsi }}
                            </div>
                        </div>

                        <!-- Gambar Upload -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Gambar Banner
                            </label>
                            <div v-if="gambarPreview" class="mb-4">
                                <div class="relative inline-block">
                                    <img :src="gambarPreview" alt="Preview" class="w-full max-w-md h-auto rounded-lg border border-slate-200 dark:border-slate-700">
                                    <button type="button" @click="removeGambar"
                                        class="absolute top-2 right-2 p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <label class="cursor-pointer inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Pilih Gambar
                                <input id="gambar" type="file" class="hidden" @change="handleGambarUpload" accept="image/*">
                            </label>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Format: JPG, PNG, GIF, SVG. Maksimal 2MB
                            </p>
                            <div v-if="form.errors.gambar" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.gambar }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pranala Section -->
                <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Pranala Luar</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- URL Pranala -->
                        <div class="lg:col-span-2">
                            <label for="url_pranala" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                URL Pranala
                            </label>
                            <input id="url_pranala" v-model="form.url_pranala" type="url"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="https://example.com" />
                            <div v-if="form.errors.url_pranala" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.url_pranala }}
                            </div>
                        </div>

                        <!-- Text Tombol -->
                        <div class="lg:col-span-2">
                            <label for="text_tombol" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Text Tombol
                            </label>
                            <input id="text_tombol" v-model="form.text_tombol" type="text"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="Contoh: Daftar Sekarang, Selengkapnya" />
                            <div v-if="form.errors.text_tombol" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.text_tombol }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Section -->
                <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Pengaturan Banner</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Tipe Banner -->
                        <div>
                            <label for="tipe" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Tipe Banner <span class="text-red-500">*</span>
                            </label>
                            <select id="tipe" v-model="form.tipe"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200"
                                :class="{ 'border-red-300 dark:border-red-600': form.errors.tipe }">
                                <option v-for="tipe in tipeBanner" :key="tipe.value" :value="tipe.value">
                                    {{ tipe.label }}
                                </option>
                            </select>
                            <div v-if="form.errors.tipe" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.tipe }}
                            </div>
                        </div>

                        <!-- Urutan -->
                        <div>
                            <label for="urutan" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Urutan Tampilan
                            </label>
                            <input id="urutan" v-model="form.urutan" type="number" min="0"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="0" />
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Banner dengan urutan lebih kecil akan ditampilkan lebih dulu
                            </p>
                        </div>

                        <!-- Target Situs -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Target Situs
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label v-for="situs in targetSitusOptions" :key="situs.value"
                                    class="flex items-center p-3 border border-slate-200 dark:border-slate-600 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                                    :class="{ 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700': form.target_situs.includes(situs.value) }">
                                    <input type="checkbox" :value="situs.value" @change="toggleSitus(situs.value)"
                                        :checked="form.target_situs.includes(situs.value)"
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-slate-800 dark:bg-slate-700 dark:border-slate-600">
                                    <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">{{ situs.label }}</span>
                                </label>
                            </div>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Kosongkan untuk menampilkan di semua situs
                            </p>
                        </div>

                        <!-- Status Aktif -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Status
                            </label>
                            <div class="flex items-center h-12">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ form.is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-slate-200 dark:border-slate-700 gap-3">
                    <Link href="/banner-modals"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                        Batal
                    </Link>

                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium text-sm rounded-xl hover:from-blue-700 hover:to-purple-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Banner</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>