/**
 * Vue entry point
 *
 * See: https://vuejs.org/v2/guide/
 *
 * Load the Vue router: https://router.vuejs.org/
 * Load the Vuex store: https://vuex.vuejs.org/guide/
 */

import Vue from 'vue';
import VueRouter from 'vue-router';
import { store } from './store';
import ExampleComponent from "./components/ExampleComponent";
import config from './config';

function init() {

    __webpack_public_path__ = M.cfg.wwwroot + `/${config.plugintype}/${config.pluginname}/amd/build/`;

    Vue.use(VueRouter);

    // TODO Add your routes here
    const routes = [
        { path: '/', component: ExampleComponent }
    ];

    const currenturl = window.location.pathname;
    const base = currenturl.substr(0, currenturl.indexOf('.php')) + '.php';

    const router = new VueRouter({
        mode: 'history',
        routes,
        base
    });

    router.beforeEach((to, from, next) => {
        // Find a translation for the title.
        if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
            if (store.state.strings.hasOwnProperty(to.meta.title)) {
                document.title = store.state.strings[to.meta.title];
            }
        }
        next();
    });

    new Vue({
        el: '#lti-dashboard-app',
        store,
        router,
    });
}

export { init };
