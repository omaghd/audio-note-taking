<template>
    <Head title="New User"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                New User
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="max-w-md mx-auto mt-8" enctype="multipart/form-data">
                            <div
                                v-if="$page.props.flash.message && !processing"
                                class="bg-indigo-100 border-t-4 border-indigo-500 rounded-b text-indigo-900 px-4 py-3 shadow-md mb-6"
                                role="alert">
                                <div class="flex">
                                    <div class="py-1">
                                        <svg class="fill-current h-6 w-6 text-indigo-500 mr-4"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path
                                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold">
                                            {{ $page.props.flash.message }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="name">
                                    Name
                                </label>

                                <input v-model="form.name"
                                       class="border border-gray-400 rounded p-2 w-full"
                                       type="text"
                                       name="name" id="name"/>
                                <div v-if="errors.name" v-text="errors.name"
                                     class="text-red-500 text-xs mt-1"></div>
                            </div>

                            <div class="mb-6">
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="email">
                                    Email
                                </label>

                                <input v-model="form.email"
                                       class="border border-gray-400 rounded p-2 w-full"
                                       type="email"
                                       name="email" id="email"/>
                                <div v-if="errors.email" v-text="errors.email"
                                     class="text-red-500 text-xs mt-1"></div>
                            </div>

                            <div class="mb-6">
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="password">
                                    Password
                                </label>

                                <input v-model="form.password"
                                       class="border border-gray-400 rounded p-2 w-full"
                                       type="password"
                                       name="password" id="password"/>
                                <div v-if="errors.password" v-text="errors.password"
                                     class="text-red-500 text-xs mt-1"></div>
                            </div>

                            <div class="mb-6">
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="role">
                                    Role
                                </label>

                                <select v-model="form.role"
                                        id="role"
                                        class="border border-gray-400 rounded p-2 w-full">
                                    <option :value="1">Admin</option>
                                    <option :value="0">User</option>
                                </select>
                                <div v-if="errors.role" v-text="errors.role"
                                     class="text-red-500 text-xs mt-1"></div>
                            </div>

                            <div class="mb-6">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-700 hover:bg-indigo-600 transition ease-in-out duration-150"
                                        :class="{'cursor-not-allowed': processing}"
                                        :disabled="processing">
                                    <svg
                                        v-if="processing"
                                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from "vue";

let props = defineProps({
    errors:     Object,
    processing: Boolean
})

const processing = ref(false);

let form = useForm({
    name:     null,
    email:    null,
    password: null,
    role:     0
});

let submit = () => {
    form.post(route('users'), {
        onStart:   () => {
            processing.value = true;
        },
        onSuccess: () => {
            form.reset();
        },
        onFinish:  () => {
            processing.value = false;
        }
    })
}

</script>
