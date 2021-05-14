import Vue from 'vue'
import VueRouter from 'vue-router';
import App from "@/App";
import './scss/main.scss';
import '../node_modules/@fortawesome/fontawesome-free/js/all'; // Require font-awesome

Vue.config.productionTip = false

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {path: '/:page?', component: App, name: 'app'}
    ]
});

new Vue({
    el: '#app',
    components: {App: App},
    router
});
