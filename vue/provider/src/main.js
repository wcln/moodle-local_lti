import Vue from 'vue'
import App from "@/App";
import './scss/main.scss';
import '../node_modules/@fortawesome/fontawesome-free/js/all'; // Require font-awesome

Vue.config.productionTip = false

new Vue({
  el: '#app',
  components: {App: App}
});
