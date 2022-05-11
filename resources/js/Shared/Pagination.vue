<template>
    <div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="link in links"
            :href="link.url"
            v-html="link.label"
            class="px-1 hover:text-sky-700"
            :class="{ 'text-gray-500': ! link.url, 'font-bold text-sky-700' : link.active }"
        />
    </div>
</template>

<script>
export default {
    props: {
        links: Array
    }
};
</script>
