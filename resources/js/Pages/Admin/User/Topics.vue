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
                            <input v-model="search" class="border px-2 rounded-lg" placeholder="Search..."
                                   type="text" />
                        </div>

                        <FilterNav route-name="users.topics" :route-params="{user: user.id}" />

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
                                                    <th class="relative px-6 py-3" scope="col">
                                                        <span class="sr-only">Options</span>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="topic in topics.data" :key="topic.id">
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
                                                                {{ formatSeconds(topic.time) }}
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <Link
                                                                    :href="route('audios.topics', topic.audio.id)"
                                                                    class="text-sky-600 hover:text-sky-900"
                                                                    title="GO TO">
                                                                    {{ topic.audio.title }}
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
                                                            v-else-if="topic.user_id === $page.props.auth.user.id || $page.props.auth.user.is_admin"
                                                            @click.prevent="destroy(topic.id)"
                                                            class="px-4 py-2 rounded text-red-900 bg-red-200 hover:bg-red-300"
                                                            title="Delete">
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

                                                <tr v-if="!topics.data.length">
                                                    <td class="px-6 py-4 whitespace-nowrap" colspan="5">
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

                        <Pagination :links="topics.links" class="mt-6"></Pagination>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import Pagination from '@/Shared/Pagination';
import { ref, watch, inject } from "vue";
import { Inertia } from "@inertiajs/inertia";
import debounce from "lodash/debounce";
import FilterNav from "@/Components/Topics/FilterNav";
import filterTopics from "@/Composables/filterTopics";
import formatters from "@/Utils/formatters";
import { useToast } from "vue-toastification";

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
            undone: query.value.undone,
            trash: query.value.trash,
        }),
        { search: value },
        { preserveState: true, replace: true });
}, 300));

const { formatSeconds } = formatters();

const { query } = filterTopics();

const toast = useToast();
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
