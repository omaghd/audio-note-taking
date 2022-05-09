require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { library } from "@fortawesome/fontawesome-svg-core";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from '@fortawesome/free-solid-svg-icons'
import { dom } from "@fortawesome/fontawesome-svg-core";

library.add(fas);
dom.watch();

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'OAudio';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .component('Head', Head)
            .component('Link', Link)
            .component('BreezeAuthenticatedLayout', BreezeAuthenticatedLayout)
            .component("font-awesome-icon", FontAwesomeIcon)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
