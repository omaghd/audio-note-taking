<template>
    <Head title="New Audio" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                New Audio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form class="max-w-md mx-auto mt-8" enctype="multipart/form-data" @submit.prevent="submit">
                            <div
                                v-if="$page.props.flash.message && !processing"
                                class="bg-sky-100 border-t-4 border-sky-500 rounded-b text-sky-900 px-4 py-3 shadow-md mb-6"
                                role="alert">
                                <div class="flex">
                                    <div class="py-1">
                                        <svg class="fill-current h-6 w-6 text-sky-500 mr-4"
                                             viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
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
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="title">
                                    Title
                                </label>

                                <input id="title"
                                       v-model="form.title"
                                       class="border border-gray-400 rounded p-2 w-full"
                                       name="title"
                                       required type="text" />
                                <div v-if="errors.title" class="text-red-500 text-xs mt-1"
                                     v-text="errors.title"></div>
                            </div>

                            <div class="mb-6">
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="audio">
                                    Audio file
                                </label>

                                <input id="audio"
                                       accept="audio/mpeg"
                                       class="border border-gray-400 rounded p-2 w-full"
                                       name="audio"
                                       required
                                       type="file"
                                       @change="changeAudio" @input="form.audio = $event.target.files[0]" />
                                <div v-if="errors.audio" class="text-red-500 text-xs mt-1"
                                     v-text="errors.audio"></div>
                            </div>

                            <div class="mb-6">
                                <button :class="{'cursor-not-allowed': processing}"
                                        :disabled="processing"
                                        class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-sky-700 hover:bg-sky-600 transition ease-in-out duration-150"
                                        type="submit">
                                    <svg
                                        v-if="processing"
                                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                        <path class="opacity-75"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                              fill="currentColor"></path>
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
    errors: Object,
    processing: Boolean
})

const processing = ref(false);
const audioInput = ref(null);

let form = useForm({
    title: null,
    audio: null,
});

let changeAudio = (event) => {
    audioInput.value = event;
}

let submit = () => {
    form.post(route('audios'), {
        onStart: () => {
            processing.value = true;
        },
        onSuccess: () => {
            form.reset();
            audioInput.value.target.value = null;
        },
        onFinish: () => {
            processing.value = false;
        }
    })
}

</script>
