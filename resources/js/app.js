require('./bootstrap');

import { createApp, h } from 'vue';
import {createInertiaApp, Head} from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .component('Head', Head)
            .component('BreezeAuthenticatedLayout', BreezeAuthenticatedLayout)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
