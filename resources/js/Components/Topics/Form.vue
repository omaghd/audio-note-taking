<template>
    <form @submit.prevent="submit" class="mb-16">
        <div class="my-6">
            <label class="block mb-2 uppercase font-bold text-xs text-gray-700" for="title">
                Title
            </label>

            <input id="title"
                   v-model="data.title"
                   dir="rtl"
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
                       v-model="data.time"
                       class="flex-shrink flex-grow flex-auto leading-normal w-px flex-1 border border-gray-400 rounded rounded-r-none p-2 w-full relative"
                       name="time" required
                       type="text">
                <div class="flex -mr-px">
                                    <span
                                        class="flex items-center leading-normal bg-grey-lighter rounded rounded-l-none border border-l-0 border-gray-400 px-3 whitespace-no-wrap text-grey-dark text-sm">
                                        {{ formatSeconds(data.time) }}
                                    </span>
                </div>
            </div>

            <div v-if="errors.time" class="text-red-500 text-xs mt-1"
                 v-text="errors.time"></div>
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
                {{ isEditing ? 'Edit' : 'Create' }}
            </button>
            <button :class="{'cursor-not-allowed': processing}"
                    v-if="isEditing"
                    @click="$emit('cancel')"
                    class="ml-3 inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-sky-700 bg-sky-50 hover:bg-sky-100 transition ease-in-out duration-150"
                    :disabled="processing"
                    type="button">
                Cancel
            </button>
        </div>
    </form>
</template>

<script setup>
defineProps({
    submit: Function,
    data: Object,
    errors: Object,
    processing: Boolean,
    formatSeconds: Function,
    isEditing: Boolean
})

defineEmits(['cancel'])
</script>

<style scoped>

</style>
