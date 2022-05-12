<template>
    <div class="uppercase space-x-2 select-none text-sm text-gray-400 mb-6">
        <Link
            :class="{'font-bold text-gray-800' : query.all}"
            :href="this.route(routeName, { ...routeParams })"
            class="hover:text-gray-900"
            preserveScroll
            title="GO TO">
            All topics
        </Link>
        <span>|</span>
        <Link
            :class="{'font-bold text-gray-800' : query.done}"
            :href="this.route(routeName, { done: 1, ...routeParams })"
            class="hover:text-gray-900"
            preserveScroll
            title="GO TO">
            Done topics
        </Link>
        <span>|</span>
        <Link
            :class="{'font-bold text-gray-800' : query.undone}"
            :href="this.route(routeName, { undone: 1, ...routeParams })"
            class="hover:text-gray-900"
            preserveScroll
            title="GO TO">
            Undone topics
        </Link>
        <span v-if="$page.props.auth.user.is_admin">|</span>
        <Link
            v-if="$page.props.auth.user.is_admin"
            :class="{'font-bold text-gray-800' : query.trash}"
            :href="this.route(routeName, { trash: 1, ...routeParams })"
            class="hover:text-gray-900"
            preserveScroll
            title="GO TO">
            Trash
        </Link>
    </div>
</template>

<script setup>
import filterTopics from "@/Composables/filterTopics";

let props = defineProps({
    routeName: String,
    routeParams: {
        type: Object,
        default: {}
    }
})

const { query } = filterTopics();
</script>
