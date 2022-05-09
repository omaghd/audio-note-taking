<template>
    <Head :title="`&quot;${user.name}&quot; Topics`" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                &quot;{{ user.name }}&quot; Topics
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-end mb-6">
                            <input v-model="search" type="text" placeholder="Search..."
                                   class="border px-2 rounded-lg" />
                        </div>

                        <div class="flex uppercase space-x-2 select-none text-sm text-gray-400 mb-6">
                            <Link
                                title="GO TO"
                                :href="this.route('users.topics', { user: user.id })"
                                preserveScroll
                                :class="{'font-bold text-gray-800' : currentUrl === `/users/${user.id}/topics` || currentUrl.startsWith(`/users/${user.id}/topics?search`)}"
                                class="hover:text-gray-900">
                                All topics
                            </Link>
                            <span>|</span>
                            <Link
                                title="GO TO"
                                :href="this.route('users.topics', { user: user.id, done: 1 })"
                                preserveScroll
                                :class="{'font-bold text-gray-800' : currentUrl.startsWith(`/users/${user.id}/topics?done=1`)}"
                                class="hover:text-gray-900">
                                Done topics
                            </Link>
                            <span>|</span>
                            <Link
                                title="GO TO"
                                :href="this.route('users.topics', { user: user.id, undone: 1 })"
                                preserveScroll
                                :class="{'font-bold text-gray-800' : currentUrl.startsWith(`/users/${user.id}/topics?undone=1`)}"
                                class="hover:text-gray-900">
                                Undone topics
                            </Link>
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
                                                        Audio
                                                    </th>
                                                    <th scope="col" class="relative px-6 py-3">
                                                        <span class="sr-only">Options</span>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="topic in topics.data" :key="topic.id">
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
                                                                {{ formatSeconds(topic.time) }}
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <Link
                                                                    :href="route('audios.topics', topic.audio.id)"
                                                                    title="GO TO"
                                                                    class="text-indigo-600 hover:text-indigo-900">
                                                                    {{ topic.audio.title }}
                                                                </Link>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
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

                        <Pagination :links="topics.links" class="mt-6"></Pagination>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import Pagination from '@/Shared/Pagination';
import { computed, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import debounce from "lodash/debounce";
import { usePage } from "@inertiajs/inertia-vue3";

let props = defineProps({
    user: Object,
    topics: Object,
    filters: Object
});

let search = ref(props.filters.search);

watch(search, debounce(function (value) {
    Inertia.get(route('users.topics', {
            user: props.user.id,
            done: query.value.done,
            undone: query.value.undone
        }),
        { search: value },
        { preserveState: true, replace: true });
}, 300));

let formatSeconds = (seconds) => {
    if (isNaN(seconds)) seconds = 0;
    return new Date(seconds * 1000).toISOString().substr(11, 8);
}

const currentUrl = computed(() => usePage().url.value);

const query = computed(() => {
    if (currentUrl.value.startsWith(`/users/${props.user.id}/topics?done`))
        return { done: 1 };
    else if (currentUrl.value.startsWith(`/users/${props.user.id}/topics?undone`))
        return { undone: 1 };
    return {};
});
</script>
