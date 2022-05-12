import { computed } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";

const filterTopics = () => {
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

    return { query };
}

export default filterTopics;
