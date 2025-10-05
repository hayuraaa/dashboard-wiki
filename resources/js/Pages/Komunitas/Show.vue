<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslations } from '@/composables/useTranslations';
import { ref, onMounted } from 'vue';

const props = defineProps({
    komunitas: Object,
});

const { can } = usePermissions();
const { t } = useTranslations();
const mapContainer = ref(null);

const deleteKomunitas = () => {
    if (confirm(t('messages.delete_community_confirm'))) {
        router.delete(`/list-komunitas/${props.komunitas.id}`);
    }
};

const getBadgeColor = (jenis) => {
    const colors = {
        'Daerah': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
        'Bahasa': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
        'Proyek': 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300',
        'KlubWiki': 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300'
    };
    return colors[jenis] || 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300';
};

const initMap = () => {
    if (props.komunitas.latitude && props.komunitas.longitude) {
        const map = L.map(mapContainer.value).setView([props.komunitas.latitude, props.komunitas.longitude], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        L.marker([props.komunitas.latitude, props.komunitas.longitude])
            .addTo(map)
            .bindPopup(props.komunitas.nama_komunitas)
            .openPopup();
    }
};

onMounted(() => {
    if (props.komunitas.latitude && props.komunitas.longitude) {
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
    }
});
</script>

<template>
    <Head :title="`${t('messages.detail')} ${komunitas.nama_komunitas}`" />

    <AdminLayout>
        <template #title>{{ t('messages.community_detail') }}</template>

        <!-- Breadcrumb -->
        <div class="mb-6 md:mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <Link href="/list-komunitas"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    {{ t('messages.community_list') }}
                </Link>
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white font-medium">{{ t('messages.community_detail') }}</span>
            </nav>
        </div>

        <!-- Header with Actions -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center">
                    <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden mr-4">
                        <img v-if="komunitas.logo" :src="`/storage/${komunitas.logo}`" :alt="komunitas.nama_komunitas" class="w-full h-full object-cover">
                        <svg v-else class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">{{ komunitas.nama_komunitas }}</h2>
                        <div class="flex items-center gap-3 mt-2">
                            <span :class="getBadgeColor(komunitas.jenis_komunitas)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                                {{ komunitas.jenis_komunitas }}
                            </span>
                            <span v-if="komunitas.aktif" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                {{ t('messages.active') }}
                            </span>
                            <span v-else class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300">
                                <span class="w-2 h-2 bg-slate-500 rounded-full mr-2"></span>
                                {{ t('messages.inactive') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <Link v-if="can('edit komunitas')" :href="`/list-komunitas/${komunitas.id}/edit`"
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ t('messages.edit') }}
                    </Link>
                    <button v-if="can('delete komunitas')" @click="deleteKomunitas"
                        class="inline-flex items-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-xl transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ t('messages.delete') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.basic_information') }}</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-if="komunitas.deskripsi">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.description') }}</label>
                            <p class="text-slate-900 dark:text-slate-200">{{ komunitas.deskripsi }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div v-if="komunitas.tanggal_berdiri">
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.established_date') }}</label>
                                <p class="text-slate-900 dark:text-slate-200">
                                    {{ new Date(komunitas.tanggal_berdiri).toLocaleDateString('id-ID', { 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    }) }}
                                </p>
                            </div>
                            
                            <div v-if="komunitas.jumlah_anggota">
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.member_count') }}</label>
                                <p class="text-slate-900 dark:text-slate-200">{{ komunitas.jumlah_anggota }} {{ t('messages.people') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontak -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.contact_information') }}</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-if="komunitas.surel" class="flex items-start">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Email</label>
                                <a :href="`mailto:${komunitas.surel}`" class="text-blue-600 dark:text-blue-400 hover:underline">{{ komunitas.surel }}</a>
                            </div>
                        </div>

                        <div v-if="komunitas.telepon" class="flex items-start">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.phone') }}</label>
                                <a :href="`tel:${komunitas.telepon}`" class="text-slate-900 dark:text-slate-200">{{ komunitas.telepon }}</a>
                            </div>
                        </div>

                        <div v-if="komunitas.website" class="flex items-start">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.website') }}</label>
                                <a :href="komunitas.website" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">{{ komunitas.website }}</a>
                            </div>
                        </div>

                        <div v-if="komunitas.social_media" class="flex items-start">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.social_media') }}</label>
                                <p class="text-slate-900 dark:text-slate-200">{{ komunitas.social_media }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi & Peta -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.location') }}</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-if="komunitas.alamat">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.address') }}</label>
                            <p class="text-slate-900 dark:text-slate-200">{{ komunitas.alamat }}</p>
                        </div>

                        <div v-if="komunitas.latitude && komunitas.longitude" class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.latitude') }}</label>
                                <p class="text-slate-900 dark:text-slate-200 font-mono text-sm">{{ komunitas.latitude }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.longitude') }}</label>
                                <p class="text-slate-900 dark:text-slate-200 font-mono text-sm">{{ komunitas.longitude }}</p>
                            </div>
                        </div>

                        <div v-if="komunitas.latitude && komunitas.longitude" class="mt-4">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">{{ t('messages.location_map') }}</label>
                            <div ref="mapContainer" class="w-full h-64 rounded-xl border border-slate-200 dark:border-slate-600"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Meta Info -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.system_information') }}</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.created_by') }}</label>
                            <p class="text-slate-900 dark:text-slate-200">{{ komunitas.creator?.name || '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.created_at') }}</label>
                            <p class="text-slate-900 dark:text-slate-200">
                                {{ new Date(komunitas.created_at).toLocaleDateString('id-ID', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) }}
                            </p>
                        </div>

                        <div v-if="komunitas.updater">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.last_updated_by') }}</label>
                            <p class="text-slate-900 dark:text-slate-200">{{ komunitas.updater?.name || '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">{{ t('messages.last_updated') }}</label>
                            <p class="text-slate-900 dark:text-slate-200">
                                {{ new Date(komunitas.updated_at).toLocaleDateString('id-ID', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">{{ t('messages.statistics') }}</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ t('messages.total_members') }}</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ komunitas.jumlah_anggota || 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ t('messages.status') }}</span>
                            <span class="text-lg font-bold" :class="komunitas.aktif ? 'text-green-600 dark:text-green-400' : 'text-slate-600 dark:text-slate-400'">
                                {{ komunitas.aktif ? t('messages.active') : t('messages.inactive') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>