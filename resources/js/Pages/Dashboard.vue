<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const getCurrentTime = () => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Selamat Pagi';
    if (hour < 15) return 'Selamat Siang';
    if (hour < 18) return 'Selamat Sore';
    return 'Selamat Malam';
};

const portalLinks = [
    {
        name: 'Portal Pendidikan',
        url: 'https://pendidikan.wikimedia.or.id',
        description: 'Program pendidikan dan pelatihan',
        icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        gradient: 'from-blue-500 to-cyan-500',
        bgGradient: 'from-blue-50 to-cyan-50 dark:from-blue-950 dark:to-cyan-950'
    },
    {
        name: 'Portal Komunitas',
        url: 'https://komunitas.wikimedia.or.id',
        description: 'Jaringan komunitas Wikimedia',
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        gradient: 'from-green-500 to-emerald-500',
        bgGradient: 'from-green-50 to-emerald-50 dark:from-green-950 dark:to-emerald-950'
    },
    {
        name: 'Portal Kebudayaan',
        url: 'https://kebudayaan.wikimedia.or.id',
        description: 'Pelestarian budaya Indonesia',
        icon: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
        gradient: 'from-purple-500 to-pink-500',
        bgGradient: 'from-purple-50 to-pink-50 dark:from-purple-950 dark:to-pink-950'
    },
    {
        name: 'Portal DataTek',
        url: 'https://datatek.wikimedia.or.id',
        description: 'Data dan teknologi Wikimedia',
        icon: 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
        gradient: 'from-orange-500 to-red-500',
        bgGradient: 'from-orange-50 to-red-50 dark:from-orange-950 dark:to-red-950'
    }
];

defineProps({
    events: {
        type: Array,
        default: () => []
    }
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

const stripHtml = (html) => {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};

const isPastEvent = (endDate) => {
    return new Date(endDate) < new Date();
};

</script>

<template>

    <Head title="Dashboard" />

    <AdminLayout>
        <template #title>Dashboard</template>

        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">
                        {{ getCurrentTime() }}, {{ user.name }}
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        {{ new Date().toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        }) }}
                    </p>
                </div>

                <!-- Wikimedia Tagline -->
                <div
                    class="bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-700 dark:to-purple-700 rounded-2xl p-6 md:p-8 text-white shadow-lg">
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <h3 class="text-xl md:text-2xl font-bold mb-2">Wikimedia Indonesia</h3>
                            <p class="text-sm md:text-base text-white/90 leading-relaxed italic">
                                "Bayangkan suatu dunia tempat setiap manusia bisa berbagi beragam pengetahuan secara
                                bebas"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portal Links Section -->
        <div class="mb-6 md:mb-8">
            <h3 class="text-lg md:text-xl font-bold text-slate-800 dark:text-white mb-4">Portal Situs Wikimedia
                Indonesia</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <a v-for="portal in portalLinks" :key="portal.name" :href="portal.url" target="_blank"
                    class="group bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex flex-col h-full">
                        <div
                            :class="`w-14 h-14 bg-gradient-to-br ${portal.gradient} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300`">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="portal.icon" />
                            </svg>
                        </div>
                        <h4
                            class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ portal.name }}
                        </h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 flex-grow">
                            {{ portal.description }}
                        </p>
                        <div
                            class="flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300">
                            <span>Kunjungi Situs</span>
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">

            

            <!-- Event Calendar Section -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-slate-800 dark:to-slate-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Event Calendar</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Kegiatan Wikimedia Indonesia</p>
                            </div>
                        </div>
                        <a href="https://wikimedia.or.id/acara" target="_blank"
                            class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-1">
                            Lihat Semua
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Events List -->
                    <div v-if="events.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a v-for="event in events" :key="event.id" :href="event.url" target="_blank"
                            class="group bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-300 border border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-600"
                            :class="{ 'opacity-75': isPastEvent(event.end_date) }">

                            <!-- Event Date Badge -->
                            <div class="flex items-start gap-3 mb-3">
                                <div class="flex-shrink-0 w-14 h-14 rounded-lg flex flex-col items-center justify-center text-white"
                                    :class="isPastEvent(event.end_date)
                                        ? 'bg-gradient-to-br from-slate-400 to-slate-500'
                                        : 'bg-gradient-to-br from-blue-500 to-purple-500'">
                                    <span class="text-xs font-medium">{{ new
                                        Date(event.start_date).toLocaleDateString('id-ID',
                                        { month: 'short' }).toUpperCase() }}</span>
                                    <span class="text-xl font-bold leading-none">{{ new Date(event.start_date).getDate()
                                        }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4
                                            class="font-semibold text-slate-900 dark:text-white text-sm line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ event.title }}
                                        </h4>
                                        <span v-if="isPastEvent(event.end_date)"
                                            class="flex-shrink-0 text-[10px] px-2 py-0.5 bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-full font-medium">
                                            Selesai
                                        </span>
                                    </div>
                                    <div
                                        class="flex items-center gap-1 mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span v-if="event.all_day">Sepanjang Hari</span>
                                        <span v-else>{{ formatTime(event.start_date) }} WIB</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Description -->
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 mb-3">
                                {{ stripHtml(event.description) }}
                            </p>

                            <!-- Event Meta -->
                            <div
                                class="flex items-center justify-between pt-3 border-t border-slate-200 dark:border-slate-600">
                                <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span v-if="event.venue.length > 0">{{ event.venue[0].venue }}</span>
                                    <span v-else>Online</span>
                                </div>
                                <span
                                    class="text-xs font-medium text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform flex items-center gap-1">
                                    Detail
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <div
                            class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Tidak Ada Event</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada event yang dijadwalkan untuk
                            periode ini
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Aktivitas Terbaru</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Komunitas baru ditambahkan
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Wikimedia Surabaya - 2 jam
                                    yang lalu
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Program pendidikan dimulai
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Wikipedia Goes to Campus - 5
                                    jam yang
                                    lalu</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Data budaya diperbarui</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Portal Kebudayaan - 1 hari
                                    yang lalu
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

    </AdminLayout>
</template>