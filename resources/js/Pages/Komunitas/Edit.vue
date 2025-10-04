<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    komunitas: Object,
    jenisKomunitas: Array,
});

const form = useForm({
    nama_komunitas: props.komunitas.nama_komunitas,
    latitude: props.komunitas.latitude || '',
    longitude: props.komunitas.longitude || '',
    deskripsi: props.komunitas.deskripsi || '',
    jenis_komunitas: props.komunitas.jenis_komunitas,
    logo: null,
    surel: props.komunitas.surel || '',
    website: props.komunitas.website || '',
    telepon: props.komunitas.telepon || '',
    alamat: props.komunitas.alamat || '',
    social_media: props.komunitas.social_media || '',
    jumlah_anggota: props.komunitas.jumlah_anggota || '',
    tanggal_berdiri: props.komunitas.tanggal_berdiri || '',
    aktif: props.komunitas.aktif,
    _method: 'PUT'
});

const logoPreview = ref(props.komunitas.logo ? `/storage/${props.komunitas.logo}` : null);
const mapContainer = ref(null);
let map = null;
let marker = null;

const handleLogoUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.logo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            logoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const initMap = () => {
    // Use komunitas coordinates if available, otherwise use Indonesia center
    const defaultLat = props.komunitas.latitude || -2.5489;
    const defaultLng = props.komunitas.longitude || 118.0149;
    const defaultZoom = props.komunitas.latitude && props.komunitas.longitude ? 13 : 5;
    
    // Initialize Leaflet map
    map = L.map(mapContainer.value).setView([defaultLat, defaultLng], defaultZoom);
    
    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Add existing marker if coordinates exist
    if (props.komunitas.latitude && props.komunitas.longitude) {
        marker = L.marker([props.komunitas.latitude, props.komunitas.longitude]).addTo(map);
    }
    
    // Add click event to map
    map.on('click', (e) => {
        const { lat, lng } = e.latlng;
        
        // Update form values
        form.latitude = lat.toFixed(8);
        form.longitude = lng.toFixed(8);
        
        // Remove existing marker if any
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Add new marker
        marker = L.marker([lat, lng]).addTo(map);
    });
};

const setLocationFromInput = () => {
    if (form.latitude && form.longitude) {
        const lat = parseFloat(form.latitude);
        const lng = parseFloat(form.longitude);
        
        if (!isNaN(lat) && !isNaN(lng)) {
            map.setView([lat, lng], 13);
            
            // Remove existing marker if any
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new marker
            marker = L.marker([lat, lng]).addTo(map);
        }
    }
};

onMounted(() => {
    // Load Leaflet CSS and JS
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(link);
    
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    script.onload = () => {
        initMap();
    };
    document.head.appendChild(script);
});

const submit = () => {
    form.post(`/list-komunitas/${props.komunitas.id}`, {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Komunitas" />

    <AdminLayout>
        <template #title>Edit Komunitas</template>

        <!-- Breadcrumb -->
        <div class="mb-6 md:mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <Link href="/list-komunitas"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    List Komunitas
                </Link>
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white font-medium">Edit Komunitas</span>
            </nav>
        </div>

        <!-- Form Container -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Edit Informasi Komunitas</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Perbarui data komunitas {{ komunitas.nama_komunitas }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="submit" class="p-6 space-y-6">
                <!-- Informasi Dasar Section -->
                <div class="space-y-6">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Informasi Dasar</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Nama Komunitas -->
                        <div class="lg:col-span-2">
                            <label for="nama_komunitas" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Nama Komunitas <span class="text-red-500">*</span>
                            </label>
                            <input id="nama_komunitas" v-model="form.nama_komunitas" type="text"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                :class="{ 'border-red-300 dark:border-red-600': form.errors.nama_komunitas }"
                                placeholder="Masukkan nama komunitas" />
                            <div v-if="form.errors.nama_komunitas" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.nama_komunitas }}
                            </div>
                        </div>

                        <!-- Jenis Komunitas -->
                        <div>
                            <label for="jenis_komunitas" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Jenis Komunitas <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_komunitas" v-model="form.jenis_komunitas"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200"
                                :class="{ 'border-red-300 dark:border-red-600': form.errors.jenis_komunitas }">
                                <option value="">Pilih Jenis Komunitas</option>
                                <option v-for="jenis in jenisKomunitas" :key="jenis" :value="jenis">{{ jenis }}</option>
                            </select>
                            <div v-if="form.errors.jenis_komunitas" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.jenis_komunitas }}
                            </div>
                        </div>

                        <!-- Tanggal Berdiri -->
                        <div>
                            <label for="tanggal_berdiri" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Tanggal Berdiri
                            </label>
                            <input id="tanggal_berdiri" v-model="form.tanggal_berdiri" type="date"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200" />
                        </div>

                        <!-- Logo Upload -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Logo Komunitas
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-20 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                    <img v-if="logoPreview" :src="logoPreview" alt="Logo Preview" class="w-full h-full object-cover">
                                    <svg v-else class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <label class="cursor-pointer inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Ganti Logo
                                    <input type="file" class="hidden" @change="handleLogoUpload" accept="image/*">
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Biarkan kosong jika tidak ingin mengubah logo</p>
                            <div v-if="form.errors.logo" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.logo }}
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="lg:col-span-2">
                            <label for="deskripsi" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" v-model="form.deskripsi" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="Masukkan deskripsi komunitas"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Kontak Section -->
                <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Informasi Kontak</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="surel" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Email
                            </label>
                            <input id="surel" v-model="form.surel" type="email"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="email@example.com" />
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Telepon
                            </label>
                            <input id="telepon" v-model="form.telepon" type="text"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="+62 xxx xxxx xxxx" />
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Website
                            </label>
                            <input id="website" v-model="form.website" type="url"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="https://example.com" />
                        </div>

                        <!-- Social Media -->
                        <div>
                            <label for="social_media" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Social Media
                            </label>
                            <input id="social_media" v-model="form.social_media" type="text"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="@username" />
                        </div>
                    </div>
                </div>

                <!-- Lokasi Section -->
                <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Informasi Lokasi</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Alamat -->
                        <div class="lg:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Alamat
                            </label>
                            <textarea id="alamat" v-model="form.alamat" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <!-- Latitude -->
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Latitude
                            </label>
                            <div class="flex gap-2">
                                <input id="latitude" v-model="form.latitude" type="text"
                                    class="flex-1 px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                    placeholder="-7.250445" />
                                <button type="button" @click="setLocationFromInput"
                                    class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Longitude -->
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Longitude
                            </label>
                            <input id="longitude" v-model="form.longitude" type="text"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="112.768845" />
                        </div>

                        <!-- Map -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Pilih Lokasi dari Peta <span class="text-slate-500 text-xs">(Klik pada peta untuk menandai lokasi)</span>
                            </label>
                            <div ref="mapContainer" class="w-full h-96 rounded-xl border border-slate-300 dark:border-slate-600"></div>
                        </div>
                    </div>
                </div>

                <!-- Detail Tambahan Section -->
                <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Detail Tambahan</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Jumlah Anggota -->
                        <div>
                            <label for="jumlah_anggota" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Jumlah Anggota
                            </label>
                            <input id="jumlah_anggota" v-model="form.jumlah_anggota" type="number" min="0"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="0" />
                        </div>

                        <!-- Status Aktif -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Status
                            </label>
                            <div class="flex items-center h-12">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="form.aktif" class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ form.aktif ? 'Aktif' : 'Tidak Aktif' }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-slate-200 dark:border-slate-700 gap-3">
                    <Link href="/list-komunitas"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                        Batal
                    </Link>

                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium text-sm rounded-xl hover:from-blue-700 hover:to-purple-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                        <span v-if="form.processing">Memperbarui...</span>
                        <span v-else>Perbarui Komunitas</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>