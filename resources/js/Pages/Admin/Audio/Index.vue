<template>
    <Head title="Audios" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Audios
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <Link
                                    v-if="$page.props.auth.user.is_admin"
                                    :href="this.route('audios.create')"
                                    class="px-4 py-1 text-sm text-sky-600 font-semibold rounded-full border border-sky-200 hover:text-white hover:bg-sky-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-600 focus:ring-offset-2">
                                    New Audio
                                </Link>
                            </div>
                            <input v-model="search" class="border px-2 rounded-lg" placeholder="Search..."
                                   type="text" />
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
                                                        Topics
                                                    </th>
                                                    <th
                                                        v-if="$page.props.auth.user.is_admin"
                                                        class="relative px-6 py-3" scope="col">
                                                        <span class="sr-only">Options</span>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="audio in audios.data" :key="audio.id">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <Link :href="this.route('audios.topics', audio.id)"
                                                                      class="text-sky-600 hover:text-sky-900"
                                                                      title="GO TO">
                                                                    {{ audio.title }}
                                                                </Link>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ audio.topics_count }}
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td
                                                        v-if="$page.props.auth.user.is_admin"
                                                        class="px-6 py-4 whitespace-nowrap space-x-3 text-right text-sm font-medium">
                                                        <Link :href="this.route('audios.edit', audio.id)"
                                                              title="Edit"
                                                              class="px-4 py-2 rounded text-amber-900 bg-amber-200 hover:bg-amber-300">
                                                            <font-awesome-icon icon="pen-to-square" />
                                                        </Link>

                                                        <Link :href="this.route('audios.destroy', audio.id)"
                                                              title="Delete"
                                                              class="px-4 py-2 rounded text-red-900 bg-red-200 hover:bg-red-300"
                                                              method="delete">
                                                            <font-awesome-icon icon="trash" />
                                                        </Link>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Pagination :links="audios.links" class="mt-6"></Pagination>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import Pagination from '@/Shared/Pagination';
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import debounce from "lodash/debounce";

let props = defineProps({
    audios: Object,
    filters: Object
})

let search  = ref(props.filters.search);

watch(search, debounce(function (value) {
    Inertia.get(route('audios'), { search: value }, { preserveState: true, replace: true });
}, 300));
</script>

