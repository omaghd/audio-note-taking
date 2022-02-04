<template>
    <Head :title="`&quot;${audio.title}&quot; Topics`"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                &quot;{{ audio.title }}&quot; Topics
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
                                <audio id="audio" controls class="flex mb-3 m-auto"
                                       :src="audio.path">
                                </audio>

                                <section class="flex justify-between">
                                    <button
                                        type="button"
                                        @click="mark"
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-700 hover:bg-indigo-600 transition ease-in-out duration-150"
                                    >
                                        Mark
                                    </button>

                                    <button
                                        type="button"
                                        @click="forward"
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-gray-700 hover:bg-gray-600 transition ease-in-out duration-150"
                                    >
                                        Forward
                                    </button>

                                    <button
                                        type="button"
                                        @click="backward"
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-gray-700 hover:bg-gray-600 transition ease-in-out duration-150"
                                    >
                                        Backward
                                    </button>

                                    <button
                                        type="button"
                                        @click="play"
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-gray-700 hover:bg-gray-600 transition ease-in-out duration-150"
                                    >
                                        Play
                                    </button>

                                    <button
                                        type="button"
                                        @click="pause"
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-gray-700 hover:bg-gray-600 transition ease-in-out duration-150"
                                    >
                                        Pause
                                    </button>
                                </section>
                            </div>

                            <hr>

                            <div class="my-6">
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="title">
                                    Title
                                </label>

                                <input v-model="form.title"
                                       class="border border-gray-400 rounded p-2 w-full"
                                       type="text"
                                       name="title" id="title"/>
                                <div v-if="errors.title" v-text="errors.title"
                                     class="text-red-500 text-xs mt-1"></div>
                            </div>
                            <div class="mb-6">
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="time">
                                    Time
                                </label>

                                <div class="flex flex-wrap items-stretch w-full relative">
                                    <input type="text"
                                           v-model="form.time"
                                           name="time" id="time"
                                           class="flex-shrink flex-grow flex-auto leading-normal w-px flex-1 border border-gray-400 rounded rounded-r-none p-2 w-full relative">
                                    <div class="flex -mr-px">
                                    <span
                                        class="flex items-center leading-normal bg-grey-lighter rounded rounded-l-none border border-l-0 border-gray-400 px-3 whitespace-no-wrap text-grey-dark text-sm">
                                        {{ formatSeconds(form.time) }}
                                    </span>
                                    </div>
                                </div>

                                <div v-if="errors.time" v-text="errors.time" class="text-red-500 text-xs mt-1"></div>
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

                        <div class="flex flex-col">
                            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="table-auto min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Title
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Time
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    User
                                                </th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Options</span>
                                                </th>
                                            </tr>
                                            </thead>

                                            <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="topic in audio.topics" :key="topic.id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ topic.title }}
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <a
                                                                href="#audio"
                                                                title="GO TO"
                                                                @click="goTo(topic.time)"
                                                                class="text-indigo-600 hover:text-indigo-900 mr-6">
                                                                {{ formatSeconds(topic.time) }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ topic.user.name }}
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Link :href="`/topics/${topic.id}`"
                                                          preserve-scroll
                                                          method="delete"
                                                          class="text-indigo-600 hover:text-indigo-900">
                                                        Delete
                                                    </Link>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { onMounted, ref } from "vue";

const processing = ref(false);
let audioElement;

let props = defineProps({
    audio:      Object,
    topics:     Object,
    errors:     Object,
    processing: Boolean
});

onMounted(() => {
    audioElement = document.querySelector('#audio');
});

let form = useForm({
    title: null,
    time:  null,
});

let submit = () => {
    form.post(`/audios/${props.audio.id}/topics`, {
        onStart:        () => {
            processing.value = true;
        },
        preserveScroll: true,
        onSuccess:      () => {
            form.reset();
        },
        onFinish:       () => {
            processing.value = false;
        }
    })
}

let mark = () => {
    audioElement.pause();
    form.time = audioElement.currentTime;
}

let backward = () => {
    audioElement.currentTime -= 1;
}

let forward = () => {
    audioElement.currentTime += 1;
}

let pause = () => {
    audioElement.pause();
}

let play = () => {
    audioElement.play();
}

let goTo = (seconds) => {
    console.log('CLICKED');
    console.log(seconds);
    console.log(audioElement.currentTime);
    audioElement.currentTime = seconds;
    console.log(audioElement.currentTime);
}

let formatSeconds = (seconds) => {
    if (isNaN(seconds)) seconds = 0;
    return new Date(seconds * 1000).toISOString().substr(11, 8);
}
</script>
