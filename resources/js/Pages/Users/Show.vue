<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslations } from '@/composables/useTranslations';


const props = defineProps({
    user: Object,
});

const { can, hasRole } = usePermissions();
const { t } = useTranslations();


const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(`/users/${id}`);
    }
};
</script>

<template>
    <Head :title="`User: ${user.name}`" />

    <AdminLayout>
        <template #title>User Details</template>

        <!-- Breadcrumb -->
        <div class="mb-6 md:mb-8">
            <nav class="flex items-center space-x-2 text-sm">
                <Link href="/users" class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">{{ t('messages.users') }}</Link>
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white font-medium">{{ user.name }}</span>
            </nav>
        </div>

        <!-- User Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Information -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.user_information') }}</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">User ID</dt>
                                <dd class="text-sm text-slate-900 dark:text-slate-200 font-mono bg-slate-100 dark:bg-slate-700/50 px-2 py-1 rounded">{{ user.id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Full Name</dt>
                                <dd class="text-sm text-slate-900 dark:text-slate-200">{{ user.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Email Address</dt>
                                <dd class="text-sm text-slate-900 dark:text-slate-200">{{ user.email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Account Status</dt>
                                <dd>
                                    <span v-if="user.email_verified_at" 
                                          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        Active & Verified
                                    </span>
                                    <span v-else 
                                          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        Pending Verification
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Member Since</dt>
                                <dd class="text-sm text-slate-900 dark:text-slate-200">
                                    {{ new Date(user.created_at).toLocaleDateString('id-ID', { 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    }) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Last Updated</dt>
                                <dd class="text-sm text-slate-900 dark:text-slate-200">
                                    {{ new Date(user.updated_at).toLocaleDateString('id-ID', { 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    }) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div>
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ t('messages.roles') }} & {{ t('messages.permissions') }}</h3>
                    </div>
                    <div class="p-6">
                        <div v-if="user.roles && user.roles.length > 0">
                            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Assigned Roles</h4>
                            <div class="space-y-2">
                                <div v-for="role in user.roles" :key="role.id" 
                                     class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-600 dark:bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ role.name }}</div>
                                            <div v-if="role.description" class="text-xs text-slate-500 dark:text-slate-400">{{ role.description }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-6">
                            <svg class="w-12 h-12 text-slate-400 dark:text-slate-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <h4 class="text-sm font-medium text-slate-900 dark:text-white mb-1">No Roles Assigned</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">This user doesn't have any roles assigned yet.</p>
                            <Link 
                                v-if="can('edit users')"
                                :href="`/users/${user.id}/edit`" 
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors duration-150"
                            >
                                Assign Roles
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <Link 
                href="/users" 
                class="inline-flex items-center px-4 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200"
            >
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
            </Link>
        </div>
    </AdminLayout>
</template>