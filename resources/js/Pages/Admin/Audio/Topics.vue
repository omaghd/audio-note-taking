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
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form class="max-w-md mx-auto mt-8" @submit.prevent="submit">
                            <div class="mb-6">
                                <vue-plyr>
                                    <audio
                                        crossorigin
                                        id="audio"
                                        :src="`${route('root')}${audio.path.replace('public', '/storage')}`"
                                        class="flex mb-3 m-auto"
                                        type="audio/mp3"
                                        controls>
                                    </audio>
                                </vue-plyr>


                                <section class="flex justify-evenly">
                                    <button
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-sky-700 hover:bg-sky-600 transition ease-in-out duration-150"
                                        title="Mark"
                                        type="button"
                                        @click="mark"
                                    >
                                        <font-awesome-icon icon="bookmark" />
                                    </button>

                                    <button
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-gray-700 hover:bg-gray-600 transition ease-in-out duration-150"
                                        title="Forward"
                                        type="button"
                                        @click="forward"
                                    >
                                        <font-awesome-icon icon="forward" />
                                    </button>

                                    <button
                                        class="block items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-gray-700 hover:bg-gray-600 transition ease-in-out duration-150"
                                        title="Backward"
                                        type="button"
                                        @click="backward"
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

                                <input id="title"
                                       v-model="form.title"
                                       class="border border-gray-400 rounded p-2 w-full"
                                       name="title"
                                       required type="text" />
                                <div v-if="errors.title" class="text-red-500 text-xs mt-1"
                                     v-text="errors.title"></div>
                            </div>
                            <div class="mb-6">
                                <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="time">
                                    Time
                                </label>

                                <div class="flex flex-wrap items-stretch w-full relative">
                                    <input id="time"
                                           v-model="form.time"
                                           class="flex-shrink flex-grow flex-auto leading-normal w-px flex-1 border border-gray-400 rounded rounded-r-none p-2 w-full relative"
                                           name="time" required
                                           type="text">
                                    <div class="flex -mr-px">
                                    <span
                                        class="flex items-center leading-normal bg-grey-lighter rounded rounded-l-none border border-l-0 border-gray-400 px-3 whitespace-no-wrap text-grey-dark text-sm">
                                        {{ formatSeconds(form.time) }}
                                    </span>
                                    </div>
                                </div>

                                <div v-if="errors.time" class="text-red-500 text-xs mt-1" v-text="errors.time"></div>
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

                        <div class="space-x-2 select-none text-sm text-gray-400 mb-6">
                            <button
                                :class="{'font-bold text-gray-800' : query.all}"
                                class="hover:text-gray-900 uppercase"
                                @click.prevent="getAll">
                                All topics
                            </button>
                            <span>|</span>
                            <button
                                :class="{'font-bold text-gray-800' : query.done}"
                                class="hover:text-gray-900 uppercase"
                                @click.prevent="getDoneTopics">
                                Done topics
                            </button>
                            <span>|</span>
                            <button
                                :class="{'font-bold text-gray-800' : query.undone}"
                                class="hover:text-gray-900 uppercase"
                                @click.prevent="getUndoneTopics">
                                Undone topics
                            </button>
                            <span v-if="$page.props.auth.user.is_admin">|</span>
                            <button
                                v-if="$page.props.auth.user.is_admin"
                                :class="{'font-bold text-gray-800' : query.trash}"
                                class="hover:text-gray-900 uppercase"
                                @click.prevent="getTrashTopics">
                                Trash
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
                                                    <th class="relative px-6 py-3" scope="col">
                                                        <span class="sr-only">Options</span>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="topic in audio.topics" :key="topic.id">
                                                    <td :class="{'border-l-4 border-green-400': topic.is_done}"
                                                        class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="font-medium text-gray-900">
                                                                {{ topic.title }}
                                                                <p v-if="topic.done_at" class="text-xs text-gray-400">
                                                                    DONE AT: {{ topic.done_at }}
                                                                </p>
                                                                <p v-else class="text-xs text-gray-400">
                                                                    <font-awesome-icon icon="minus" />
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <a
                                                                    class="text-sky-600 hover:text-sky-900 mr-6"
                                                                    href="#audio"
                                                                    title="GO TO"
                                                                    @click="goTo(topic.time)">
                                                                    {{ formatSeconds(topic.time) }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <Link :href="this.route('users.topics', topic.user.id)"
                                                                      class="text-sky-600 hover:text-sky-900"
                                                                      title="See all topics">
                                                                    {{ topic.user.name }}
                                                                </Link>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 space-x-3 whitespace-nowrap text-right text-sm font-medium">
                                                        <Link
                                                            v-if="$page.props.auth.user.is_admin && !topic.deleted_at"
                                                            :class="{
                                                                'text-sky-900 bg-sky-200 hover:bg-sky-300': topic.is_done,
                                                                'text-green-900 bg-green-200 hover:bg-green-300': !topic.is_done,
                                                            }"
                                                            :href="topic.is_done ? route('topics.undone', topic.id) : route('topics.done', topic.id)"
                                                            :title="topic.is_done ? 'Mark as undone' : 'Mark as Done'"
                                                            class="px-4 py-2 rounded"
                                                            method="patch"
                                                            preserve-scroll>
                                                            <font-awesome-icon v-if="topic.is_done"
                                                                               icon="square-xmark" />
                                                            <font-awesome-icon v-else icon="square-check" />
                                                        </Link>
                                                        <Link
                                                            v-if="topic.deleted_at && $page.props.auth.user.is_admin"
                                                            :href="this.route('topics.restore', topic.id)"
                                                            class="px-4 py-2 rounded text-green-900 bg-green-200 hover:bg-green-300"
                                                            title="Restore"
                                                            method="patch"
                                                            preserveScroll>
                                                            <font-awesome-icon icon="arrow-rotate-left" />
                                                        </Link>
                                                        <button
                                                            v-else-if="(topic.user_id === $page.props.auth.user.id) || $page.props.auth.user.is_admin"
                                                            @click.prevent="destroy(topic.id)"
                                                            title="Delete"
                                                            class="px-4 py-2 rounded text-red-900 bg-red-200 hover:bg-red-300">
                                                            <font-awesome-icon icon="trash" />
                                                        </button>
                                                        <button
                                                            v-if="topic.deleted_at"
                                                            @click.prevent="forceDelete(topic.id)"
                                                            class="px-4 py-2 rounded text-gray-900 bg-gray-200 hover:bg-gray-300"
                                                            title="Permanently Delete">
                                                            <font-awesome-icon icon="minus" />
                                                        </button>
                                                    </td>
                                                </tr>

                                                <tr v-if="!audio.topics.length">
                                                    <td class="px-6 py-4 whitespace-nowrap" colspan="4">
                                                        <div class="flex items-center">
                                                            <strong class="text-sm font-bold text-gray-900">
                                                                No Data...
                                                            </strong>
                                                        </div>
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
import { computed, onMounted, ref, inject } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useToast } from "vue-toastification";
import formatters from "@/Utils/formatters";

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

const toast = useToast();

let submit = () => {
    form.post(route('audios.topics', props.audio.id), {
        onStart: () => {
            processing.value = true;
        },
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success('Created successfully!', { timeout: 2500 });
        },
        onFinish: () => {
            processing.value = false;
        }
    })
}

const { formatSeconds } = formatters();

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

let goTo = (seconds) => {
    audioElement.currentTime = seconds;
}

const currentUrl = computed(() => usePage().url.value);

const query = computed(() => {
    if (currentUrl.value.includes('/topics?done'))
        return { done: 1 };
    else if (currentUrl.value.includes('/topics?undone'))
        return { undone: 1 };
    else if (currentUrl.value.includes('/topics?trash'))
        return { trash: 1 };
    return { all: 1 };
});

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

const getTrashTopics = () => {
    Inertia.get(route('audios.topics', { audio: props.audio.id, trash: 1 }), {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}

const swal = inject('$swal');

const showConfirmation = () => {
    return swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0ea5e9',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes!',
        focusCancel: true
    });
}

const destroy = id => {
    showConfirmation().then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route('topics.destroy', id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Deleted successfully!', { timeout: 3000 });
                }
            });
        }
    })
}

const forceDelete = id => {
    showConfirmation().then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route('topics.forceDelete', id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Permanently deleted successfully!', { timeout: 3000 });
                }
            });
        }
    })
}
</script>
