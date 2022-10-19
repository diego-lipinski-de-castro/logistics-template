require('./bootstrap');

if (process.env.MIX_ENV !== "production") {
    require('clockwork-browser/metrics');
}

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Spinner from './Components/Spinner';
import Maska, { mask } from 'maska';
import money, { format, unformat } from 'v-money3';
import mitt from 'mitt';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; 

window.emitter = mitt();
window.tippy = tippy

window.isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'SpeedApp';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        const _app = createApp({ render: () => h(app, props) });

        _app.component('Spinner', Spinner);

        _app.config.globalProperties.$filters = {
            maskIt(v, m) {
                return mask(v, m);
            },
            currency(v) {
                return format(v, {
                    prefix: 'R$ ',
                    suffix: '',
                    thousands: '.',
                    decimal: ',',
                    precision: 2,
                    disableNegative: false,
                    disabled: false,
                    min: null,
                    max: null,
                    allowBlank: true,
                    minimumNumberOfCharacters: 0,
                });
            },
            rawCurrency(v) {
                return unformat(v);
            }
        };

        return _app
            .use(Maska)
            .use(money)
            .use(plugin)
            .mixin({
                methods: {
                    route,
                    goBack: () => window.history.back(),
                } 
            })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#F57C00' });
