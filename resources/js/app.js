import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
  resolve: name => import(`./Pages/${name}.vue`),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el);
  },
});

InertiaProgress.init({ color: '#4B5563' });
