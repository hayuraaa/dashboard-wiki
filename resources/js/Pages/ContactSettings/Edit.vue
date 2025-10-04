<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    setting: Object,
});

const form = useForm({
    recipient_email: props.setting.recipient_email,
    cc_emails: props.setting.cc_emails || [],
    is_active: props.setting.is_active,
    send_auto_reply: props.setting.send_auto_reply,
    auto_reply_message: props.setting.auto_reply_message,
});

const newCcEmail = ref('');

const addCcEmail = () => {
    if (newCcEmail.value && isValidEmail(newCcEmail.value)) {
        if (!form.cc_emails.includes(newCcEmail.value)) {
            form.cc_emails.push(newCcEmail.value);
            newCcEmail.value = '';
        }
    }
};

const removeCcEmail = (index) => {
    form.cc_emails.splice(index, 1);
};

const isValidEmail = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
};

const submit = () => {
    form.put(`/contact-settings/${props.setting.id}`);
};

const getPortalColor = (source) => {
    const colors = {
        'pendidikan': 'from-blue-500 to-cyan-500',
        'komunitas': 'from-green-500 to-emerald-500',
        'kebudayaan': 'from-purple-500 to-pink-500',
        'datatek': 'from-orange-500 to-red-500'
    };
    return colors[source] || 'from-slate-500 to-slate-600';
};
</script>

<template>
    <Head :title="`Edit Pengaturan ${setting.website_source}`" />

    <AdminLayout>
        <template #title>Edit Pengaturan Kontak</template>

        <!-- Breadcrumb -->
        <div class="mb-6 md:mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <Link href="/contact-settings"
                    class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    Pengaturan Kontak
                </Link>
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white font-medium capitalize">Edit {{ setting.website_source }}</span>
            </nav>
        </div>

        <!-- Form Container -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700"
                :class="`bg-gradient-to-r ${getPortalColor(setting.website_source)}`">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white capitalize">Portal {{ setting.website_source }}</h3>
                        <p class="text-sm text-white/80">{{ setting.website_source }}.wikimedia.or.id</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="submit" class="p-6 space-y-6">
                <!-- Email Penerima Utama -->
                <div>
                    <label for="recipient_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Email Penerima Utama <span class="text-red-500">*</span>
                    </label>
                    <input id="recipient_email" v-model="form.recipient_email" type="email"
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                        :class="{ 'border-red-300 dark:border-red-600': form.errors.recipient_email }"
                        placeholder="email@example.com" />
                    <div v-if="form.errors.recipient_email" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.recipient_email }}
                    </div>
                </div>

                <!-- CC Emails -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Email CC (Carbon Copy)
                    </label>
                    <div class="flex gap-2 mb-3">
                        <input v-model="newCcEmail" type="email"
                            class="flex-1 px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                            placeholder="Tambah email CC"
                            @keyup.enter="addCcEmail" />
                        <button type="button" @click="addCcEmail"
                            class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah
                        </button>
                    </div>
                    
                    <div v-if="form.cc_emails.length > 0" class="space-y-2">
                        <div v-for="(email, index) in form.cc_emails" :key="index"
                            class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600">
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ email }}</span>
                            <button type="button" @click="removeCcEmail(index)"
                                class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p v-else class="text-sm text-slate-500 dark:text-slate-400 italic">Belum ada email CC</p>
                </div>

                <!-- Status Pengiriman Email - SIMPLE TOGGLE -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-1">
                                Aktifkan Pengiriman Email
                            </h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                {{ form.is_active ? 'Email akan dikirim otomatis' : 'Email tidak akan dikirim' }}
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-400 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>
                </div>

                <!-- Auto Reply - SIMPLE TOGGLE -->
                <div>
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-1">
                                Balasan Otomatis
                            </h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                {{ form.send_auto_reply ? 'Pengirim akan terima email balasan' : 'Tidak ada balasan otomatis' }}
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" v-model="form.send_auto_reply" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-400 rounded-full peer peer-checked:bg-green-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>
                </div>

                <!-- Pesan Auto Reply -->
                <div v-if="form.send_auto_reply">
                    <label for="auto_reply_message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Pesan Balasan Otomatis
                    </label>
                    <textarea id="auto_reply_message" v-model="form.auto_reply_message" rows="5"
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 transition-colors duration-200 placeholder:text-slate-400 dark:placeholder:text-slate-500"
                        placeholder="Masukkan pesan yang akan dikirim otomatis ke pengirim..."></textarea>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-slate-200 dark:border-slate-700 gap-3">
                    <Link href="/contact-settings"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                        Batal
                    </Link>

                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium text-sm rounded-xl hover:from-blue-700 hover:to-purple-700 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md">
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Pengaturan</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>