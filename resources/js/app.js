

import { createApp } from "vue";
import App from "./app.vue";
import router from "./router";
import axios from "axios";
import store from "./store/store";




import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import Cleave from 'vue-cleave-component';
import loading from "./components/loading.vue";

import pagination from "./components/pagination.vue";
import Multiselect from '@vueform/multiselect';

require('./bootstrap');



const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router);
app.use(store);
app.use(VueSweetalert2);
app.use(Cleave);
app.component('loading', loading);
app.component('pagination',pagination);
app.component('Multiselect', Multiselect);
app.mount("#app");
