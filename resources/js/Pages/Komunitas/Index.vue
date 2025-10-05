<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslations } from '@/composables/useTranslations';
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    komunitas: Array,
});

const { can } = usePermissions();
const { t } = useTranslations();
const viewMode = ref('table');
const mapContainer = ref(null);
let map = null;
const markers = [];

const deleteKomunitas = (id) => {
    if (confirm(t('messages.delete_community_confirm'))) {
        router.delete(`/list-komunitas/${id}`);
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

const getMarkerColor = (jenis) => {
    const colors = {
        'Daerah': 'blue',
        'Bahasa': 'green',
        'Proyek': 'purple',
        'KlubWiki': 'orange'
    };
    return colors[jenis] || 'gray';
};

const komunitasWithCoordinates = computed(() => {
    return props.komunitas.filter(k => k.latitude && k.longitude);
});

const komunitasWithoutCoordinates = computed(() => {
    return props.komunitas.filter(k => !k.latitude || !k.longitude);
});

const initMap = () => {
    markers.forEach(marker => marker.remove());
    markers.length = 0;

    if (map) {
        map.remove();
    }

    map = L.map(mapContainer.value, {
        minZoom: 5,
        maxZoom: 18,
        maxBounds: [
            [-11.5, 95],
            [6.5, 141]
        ],
        maxBoundsViscosity: 1.0
    }).setView([-2.5, 120.5], 5.2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    komunitasWithCoordinates.value.forEach(k => {
        const color = getMarkerColor(k.jenis_komunitas);
        const logoUrl = k.logo ? `/storage/${k.logo}` : null;

        const iconHtml = logoUrl ? `
            <div class="relative">
                <div class="w-10 h-10 rounded-full border-3 border-white shadow-lg overflow-hidden bg-white ring-2 ring-${color}-500">
                    <img src="${logoUrl}" alt="${k.nama_komunitas}" class="w-full h-full object-cover">
                </div>
            </div>
        ` : `
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-${color}-500 border-3 border-white shadow-lg flex items-center justify-center ring-2 ring-${color}-600">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        `;

        const customIcon = L.divIcon({
            html: iconHtml,
            className: 'custom-marker',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });

        const marker = L.marker([k.latitude, k.longitude], { icon: customIcon })
            .addTo(map)
            .bindPopup(`
                <div class="p-2">
                    <div class="flex items-center gap-2 mb-2">
                        ${logoUrl ? `<img src="${logoUrl}" alt="${k.nama_komunitas}" class="w-8 h-8 rounded-full object-cover">` : ''}
                        <div>
                            <h3 class="font-bold text-sm">${k.nama_komunitas}</h3>
                            <p class="text-xs text-gray-600">${k.jenis_komunitas}</p>
                        </div>
                    </div>
                    ${k.alamat ? `<p class="text-xs text-gray-500 mt-1">${k.alamat}</p>` : ''}
                    <a href="/list-komunitas/${k.id}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">${t('messages.view_detail')} →</a>
                </div>
            `);

        markers.push(marker);
    });

    if (markers.length > 0) {
        const group = L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
};

const switchView = (mode) => {
    viewMode.value = mode;
    if (mode === 'map') {
        setTimeout(() => {
            initMap();
        }, 100);
    }
};

onMounted(() => {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(link);

    const script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    document.head.appendChild(script);
});
</script>

<template>

    <Head :title="t('messages.community_list')" />

    <AdminLayout>
        <template #title>{{ t('messages.community_management') }}</template>

        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">{{
                        t('messages.community_list') }}
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ t('messages.manage_community_data') }}
                    </p>
                </div>
                <Link v-if="can('create komunitas')" href="/list-komunitas/create"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium text-sm rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ t('messages.add_community') }}
                </Link>
            </div>
        </div>

        <!-- Information Alert -->
        <div class="mb-6 md:mb-8">
            <div
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 md:p-5">
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
                            Data komunitas yang Anda kelola di sini akan otomatis ditampilkan di peta komunitas publik
                            di
                            <a href="https://komunitas.wikimedia.or.id" target="_blank"
                                class="font-semibold underline hover:text-blue-600 dark:hover:text-blue-200 transition-colors">
                                komunitas.wikimedia.or.id
                            </a>
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

        <!-- View Toggle -->
        <div class="mb-6">
            <div class="inline-flex rounded-xl bg-slate-100 dark:bg-slate-800 p-1">
                <button @click="switchView('table')" :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    viewMode === 'table'
                        ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                ]">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    {{ t('messages.table') }}
                </button>
                <button @click="switchView('map')" :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    viewMode === 'map'
                        ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                ]">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    {{ t('messages.map') }}
                </button>
            </div>
        </div>

        <!-- Table View -->
        <div v-if="viewMode === 'table'"
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.community_list') }}</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                {{ t('messages.community') }}</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                {{ t('messages.type') }}</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                {{ t('messages.contact') }}</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                {{ t('messages.status') }}</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                {{ t('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        <tr v-for="item in komunitas" :key="item.id"
                            class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden bg-slate-100 dark:bg-slate-700">
                                        <img v-if="item.logo" :src="`/storage/${item.logo}`" :alt="item.nama_komunitas"
                                            class="w-full h-full object-cover">
                                        <svg v-else class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{
                                            item.nama_komunitas
                                            }}</div>
                                        <div v-if="item.alamat"
                                            class="text-sm text-slate-500 dark:text-slate-400 line-clamp-1">
                                            {{ item.alamat }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getBadgeColor(item.jenis_komunitas)"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium">
                                    {{ item.jenis_komunitas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 dark:text-slate-200">{{ item.surel || '-' }}</div>
                                <div v-if="item.telepon" class="text-sm text-slate-500 dark:text-slate-400">{{
                                    item.telepon }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="item.aktif"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    {{ t('messages.active') }}
                                </span>
                                <span v-else
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300">
                                    <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                    {{ t('messages.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <Link v-if="can('view komunitas')" :href="`/list-komunitas/${item.id}`"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ t('messages.view') }}
                                    </Link>

                                    <Link v-if="can('edit komunitas')" :href="`/list-komunitas/${item.id}/edit`"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors duration-150">
                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ t('messages.edit') }}
                                    </Link>

                                    <button v-if="can('delete komunitas')" @click="deleteKomunitas(item.id)"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors duration-150">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        {{ t('messages.delete') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="komunitas.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">{{
                        t('messages.no_community_yet') }}
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6">{{ t('messages.start_adding_community') }}</p>
                    <Link href="/list-komunitas/create"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium text-sm rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ t('messages.add_community') }}
                    </Link>
                </div>
            </div>
        </div>

        <!-- Map View -->
        <div v-if="viewMode === 'map'"
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="relative z-0">
                <div ref="mapContainer" class="w-full h-[700px]"></div>

                <!-- List Komunitas Proyek/KlubWiki - Bottom Right Inside Map -->
                <div v-if="komunitasWithoutCoordinates.length > 0"
                    class="absolute bottom-4 right-4 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 z-[1000] w-80 max-h-96">
                    <h3
                        class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center border-b border-slate-200 dark:border-slate-700 pb-2">
                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ t('messages.project_community') }} ({{ komunitasWithoutCoordinates.length }})
                    </h3>
                    <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                        <Link v-for="item in komunitasWithoutCoordinates" :key="item.id"
                            :href="`/list-komunitas/${item.id}`"
                            class="flex items-center p-2.5 bg-slate-50 dark:bg-slate-900/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-600 group">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden bg-slate-100 dark:bg-slate-700 mr-2">
                            <img v-if="item.logo" :src="`/storage/${item.logo}`" :alt="item.nama_komunitas"
                                class="w-full h-full object-cover">
                            <svg v-else class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h4
                                    class="text-xs font-medium text-slate-900 dark:text-white line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ item.nama_komunitas }}</h4>
                                <span :class="getBadgeColor(item.jenis_komunitas)"
                                    class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium flex-shrink-0">
                                    {{ item.jenis_komunitas }}
                                </span>
                            </div>
                            <p v-if="item.deskripsi"
                                class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">{{
                                item.deskripsi }}</p>
                        </div>
                        <svg class="w-3 h-3 text-slate-400 ml-1.5 flex-shrink-0 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
.custom-marker {
    background: transparent;
    border: none;
}

/* Custom scrollbar for list */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #475569;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>