<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Login" />

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/Logo_WMID_2018_Mendatar_SVG.svg" alt="Wikimedia Indonesia" class="h-20 w-auto" />
            </div>
            <p class="mt-2 text-center text-sm text-gray-600">
                Selamat datang kembali
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-200">

                <div v-if="status" class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200">
                    <p class="text-sm font-medium text-green-800">{{ status }}</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6" autocomplete="on">
                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" name="email" class="mt-1 block w-full" v-model="form.email"
                            required autofocus autocomplete="email" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" />
                        <TextInput id="password" type="password" name="password" class="mt-1 block w-full"
                            v-model="form.password" required autocomplete="current-password" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>

                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="text-sm font-medium text-[#006699] hover:text-[#005580]">
                        Lupa password?
                        </Link>
                    </div>

                    <div>
                        <PrimaryButton class="w-full justify-center bg-[#006699] hover:bg-[#005580]"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Masuk
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>