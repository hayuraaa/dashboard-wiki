<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const activeTab = ref('pendidikan');

const forms = {
    pendidikan: useForm({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
        website_source: 'pendidikan',
    }),
    komunitas: useForm({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
        website_source: 'komunitas',
    }),
    kebudayaan: useForm({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
        website_source: 'kebudayaan',
    }),
    datatek: useForm({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
        website_source: 'datatek',
    }),
};

const submitForm = (source) => {
    forms[source].post('/contact-forms/submit', {
        onSuccess: () => {
            forms[source].reset();
        },
    });
};

const tabs = [
    { id: 'pendidikan', name: 'Pendidikan', color: 'blue' },
    { id: 'komunitas', name: 'Komunitas', color: 'green' },
    { id: 'kebudayaan', name: 'Kebudayaan', color: 'purple' },
    { id: 'datatek', name: 'DataTek', color: 'orange' },
];
</script>

<template>

    <Head title="Form Kontak" />

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Form Kontak Wikimedia Indonesia</h1>
                <p class="text-slate-600 dark:text-slate-400">Pilih portal dan kirim pesan Anda</p>
            </div>

            <!-- Success Message -->
            <div v-if="$page.props.flash?.success"
                class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl">
                <p class="text-green-800 dark:text-green-300">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Tabs -->
            <div class="mb-6 flex flex-wrap gap-2 justify-center">
                <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" :class="[
                    'px-6 py-2 rounded-lg font-medium text-sm transition-all',
                    activeTab === tab.id
                        ? `bg-${tab.color}-600 text-white shadow-lg`
                        : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'
                ]">
                    {{ tab.name }}
                </button>
            </div>

            <!-- Form Container -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 md:p-8">
                <div v-for="tab in tabs" :key="tab.id" v-show="activeTab === tab.id">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
                        Form Kontak Portal {{ tab.name }}
                    </h2>

                    <form @submit.prevent="submitForm(tab.id)" class="space-y-4">
                        <!-- Nama -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input v-model="forms[tab.id].name" type="text" required
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Nama Anda" />
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input v-model="forms[tab.id].email" type="email" required
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="email@example.com" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Nomor HP <span class="text-red-500">*</span>
                                </label>
                                <input v-model="forms[tab.id].phone" type="text" required
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="08xxxxxxxxxx" />
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Subjek <span class="text-red-500">*</span>
                            </label>
                            <input v-model="forms[tab.id].subject" type="text" required
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Subjek pesan" />
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Pesan <span class="text-red-500">*</span>
                            </label>
                            <textarea v-model="forms[tab.id].message" rows="5" required
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" :disabled="forms[tab.id].processing"
                            :class="`w-full py-3 bg-${tab.color}-600 hover:bg-${tab.color}-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50`">
                            {{ forms[tab.id].processing ? 'Mengirim...' : 'Kirim Pesan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>