<template>
    <Head :title="`&quot;${audio.title}&quot; Topics`" />

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
                        <form @submit.prevent="submit" class="max-w-md mx-auto mt-8">
                            <div
                                v-if="$page.props.flash.message && !processing"
                                class="bg-indigo-100 border-t-4 border-indigo-500 rounded-b text-indigo-900 px-4 py-3 shadow-md mb-6"
                                role="alert">
                                <div class="flex">
                                    <div class="py-1">
                                        <svg class="fill-current h-6 w-6 text-indigo-500 mr-4"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
                                <audio id="audio" controls class="flex mb-3 m-auto"
                                       :src="`${route('root')}${audio.path.replace('public', '/storage')}`">
                                </audio>

                                <section class="flex justify-evenly">
                                    <button
                                        type="button"
                                        @click="mark"
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-700 hover:bg-indigo-600 transition ease-in-out duration-150"
                                        title="Mark"
                                    >
                                        <font-awesome-icon icon="bookmark" />
                                    </button>

                                    <button
                                        type="button"
                                        @click="forward"
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-gray-700 hover:bg-gray-600 transition ease-in-out duration-150"
                                        title="Forward"
                                    >
                                        <font-awesome-icon icon="forward" />
                                    </button>

                                    <button
                                        type="button"
                                        @click="backward"
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-gray-700 hover:bg-gray-600 transition ease-in-out duration-150"
                                        title="Backward"
                                    >
                                        <font-awesome-icon icon="backward" />
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
                                       name="title" id="title" />
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

                        <div class="flex space-x-2 select-none text-sm text-gray-400 mb-6">
                            <button
                                @click.prevent="getAll"
                                :class="{'font-bold text-gray-800' : currentUrl === `/audios/${audio.id}/topics`}"
                                class="hover:text-gray-900 uppercase">
                                All topics
                            </button>
                            <span>|</span>
                            <button
                                @click.prevent="getDoneTopics"
                                :class="{'font-bold text-gray-800' : currentUrl.startsWith(`/audios/${audio.id}/topics?done=1`)}"
                                class="hover:text-gray-900 uppercase">
                                Done topics
                            </button>
                            <span>|</span>
                            <button
                                @click.prevent="getUndoneTopics"
                                :class="{'font-bold text-gray-800' : currentUrl.startsWith(`/audios/${audio.id}/topics?undone=1`)}"
                                class="hover:text-gray-900 uppercase">
                                Undone topics
                            </button>
                        </div>

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
                                                    <td class="px-6 py-4 whitespace-nowrap"
                                                        :class="{'border-l-4 border-green-600': topic.is_done}">
                                                        <div class="flex items-center">
                                                            <div class="font-medium text-gray-900">
                                                                {{ topic.title }}
                                                                <p v-if="topic.done_at" class="text-xs text-gray-400">
                                                                    DONE AT: {{ topic.done_at }}</p>
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
                                                                <Link :href="this.route('users.topics', topic.user.id)"
                                                                      title="See all topics"
                                                                      class="text-indigo-600 hover:text-indigo-900">
                                                                    {{ topic.user.name }}
                                                                </Link>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 space-x-3 whitespace-nowrap text-right text-sm font-medium">
                                                        <Link
                                                            v-if="(topic.user_id === $page.props.auth.user.id || $page.props.auth.user.is_admin) && $page.props.auth.user.is_admin"
                                                            :href="topic.is_done ? route('topics.undone', topic.id) : route('topics.done', topic.id)"
                                                            preserve-scroll
                                                            method="patch"
                                                            class="text-green-600 hover:text-indigo-900">
                                                            {{ topic.is_done ? 'Undone' : 'Done' }}
                                                        </Link>

                                                        <Link
                                                            v-if="topic.user_id === $page.props.auth.user.id || $page.props.auth.user.is_admin"
                                                            :href="route('topics.destroy', topic.id)"
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
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { computed, onMounted, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

const processing = ref(false);
let audioElement;

let props = defineProps({
    audio: Object,
    errors: Object,
    processing: Boolean
});

onMounted(() => {
    audioElement = document.querySelector('#audio');
});

let form = useForm({
    title: null,
    time: null,
});

let submit = () => {
    form.post(route('audios.topics', props.audio.id), {
        onStart: () => {
            processing.value = true;
        },
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onFinish: () => {
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
    audioElement.currentTime += 10;
}

let pause = () => {
    audioElement.pause();
}

let play = () => {
    audioElement.play();
}

let goTo = (seconds) => {
    audioElement.currentTime = seconds;
}

let formatSeconds = (seconds) => {
    if (isNaN(seconds)) seconds = 0;
    return new Date(seconds * 1000).toISOString().substr(11, 8);
}

const currentUrl = computed(() => usePage().url.value);

const getAll = () => {
    Inertia.get(route('audios.topics', { audio: props.audio.id }), {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}

const getDoneTopics = () => {
    Inertia.get(route('audios.topics', { audio: props.audio.id, done: 1 }), {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}

const getUndoneTopics = () => {
    Inertia.get(route('audios.topics', { audio: props.audio.id, undone: 1 }), {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}
</script>
