<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: Array,
});

const form = useForm({
    slider_category_id: '',
    nama: '',
    keterangan: '',
    gambar: null,
    urutan: 0,
    is_active: true
});

const previewImage = ref(null);

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.gambar = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    form.gambar = null;
    previewImage.value = null;
    // Reset input file
    document.getElementById('gambar').value = '';
};

const submit = () => {
    form.post('/sliders', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            previewImage.value = null;
        },
    });
};
</script>

<template>
    <Head title="Tambah Slider" />

    <AdminLayout>
        <template #title>Tambah Slider</template>

        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">
                        Tambah Slider Gambar
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Buat slider baru untuk ditampilkan di situs
                    </p>
                </div>
                <Link href="/sliders"
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
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Form Slider Gambar</h3>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-6">
                <!-- Kategori Slider -->
                <div>
                    <label for="slider_category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Kategori Slider <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="slider_category_id"
                        v-model="form.slider_category_id"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-colors"
                        required
                    >
                        <option value="">Pilih Kategori</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.nama }}
                        </option>
                    </select>
                    <p v-if="form.errors.slider_category_id" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.slider_category_id }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        Pilih kategori untuk mengelompokkan slider
                    </p>
                </div>

                <!-- Nama Slider -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Nama Slider <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama"
                        v-model="form.nama"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-colors"
                        placeholder="Tuliskan simpel aja, karena tidak diperlihatkan namanya di halaman utama"
                        required
                    />
                    <p v-if="form.errors.nama" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.nama }}
                    </p>
                </div>

                <!-- Upload Gambar -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Gambar Slider <span class="text-red-500">*</span>
                    </label>
                    
                    <!-- Preview Image -->
                    <div v-if="previewImage" class="mb-4">
                        <div class="relative inline-block">
                            <img :src="previewImage" alt="Preview" class="w-full max-w-md h-48 object-cover rounded-lg border-2 border-slate-200 dark:border-slate-600">
                            <button 
                                type="button"
                                @click="removeImage"
                                class="absolute top-2 right-2 p-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Upload Button -->
                    <div v-else class="flex items-center justify-center w-full">
                        <label for="gambar" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-lg cursor-pointer bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mb-2 text-sm text-slate-500 dark:text-slate-400">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG, GIF, WEBP (MAX. 2MB)</p>
                            </div>
                            <input 
                                id="gambar" 
                                type="file" 
                                class="hidden" 
                                accept="image/*"
                                @change="handleImageChange"
                                required
                            />
                        </label>
                    </div>
                    <p v-if="form.errors.gambar" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.gambar }}
                    </p>
                </div>

                <!-- Urutan -->
                <div>
                    <label for="urutan" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Urutan Tampilan
                    </label>
                    <input 
                        type="number" 
                        id="urutan"
                        v-model="form.urutan"
                        min="0"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-colors"
                        placeholder="0"
                    />
                    <p v-if="form.errors.urutan" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.urutan }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        Semakin kecil angka, semakin awal ditampilkan (0 = paling awal)
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
                            Aktifkan slider
                        </span>
                    </label>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 ml-8">
                        Slider yang aktif akan ditampilkan di situs
                    </p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <Link href="/sliders"
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
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Slider' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>