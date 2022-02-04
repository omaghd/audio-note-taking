<template>
    <Head title="Audios"/>

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
                                    href="/audios/create"
                                    class="px-4 py-1 text-sm text-indigo-600 font-semibold rounded-full border border-indigo-200 hover:text-white hover:bg-indigo-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                                    New Audio
                                </Link>
                            </div>
                            <input v-model="search" type="text" placeholder="Search..." class="border px-2 rounded-lg"/>
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
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Options</span>
                                                </th>
                                            </tr>
                                            </thead>

                                            <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="audio in audios.data" :key="audio.id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <Link :href="`/audios/${audio.id}/topics`"
                                                                  title="GO TO"
                                                                  class="text-indigo-600 hover:text-indigo-900">
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

                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Link :href="`/audios/${audio.id}/edit`"
                                                          class="text-indigo-600 hover:text-indigo-900 mr-6">
                                                        Edit
                                                    </Link>

                                                    <Link :href="`/audios/${audio.id}`" method="delete"
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
    audios:  Object,
    filters: Object
})

let search = ref(props.filters.search);

watch(search, debounce(function (value) {
    Inertia.get('/audios', { search: value }, { preserveState: true, replace: true });
}, 300));
</script>

