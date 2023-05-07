/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue').default;

import ElementUI  from "element-ui";
import locale from "element-ui/lib/locale/lang/en";
import Vue from "vue";
import App from "./components/core/App";
import routes from "./routes";
import CKEditor from "ckeditor4-vue";
import imagePreview from "image-preview-vue";
import "image-preview-vue/lib/imagepreviewvue.css";

require('./filters');

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

const userApiToken = localStorage.getItem('token');

if (userApiToken) {
    window.axios.defaults.headers.common.Authorization = `Bearer ${userApiToken}`;
} else {
    console.error("User API token not found in a meta tag.");
}

Vue.use(ElementUI, { locale });
Vue.use(CKEditor);
Vue.use(imagePreview);

let base_url = document.head.querySelector('meta[name="base-url"]');

if(base_url) {
    base_url = base_url.content;
}
Vue.prototype.$url = base_url;
Vue.prototype.$csrfToken = token;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: "#app",
    router: routes,
    render: (h) => h(App),
});

window.axios.interceptors.response.use(null, (error) => {
    if (error.response === undefined) {
        return;
    }
    if (error.response.status === 403) {
        app.$message.error('Sesi telah berakhir!. silahkan login terlebih dahulu!');
        axios.post(route('logout')).then((response) => {
            window.location = response.data.redirect
        });
    }
    if (error.response.status === 401) {
        app.$message.error(
            "Silahkan login terlebih dahulu!"
        );
        axios.post(route("logout")).then((response) => {
             window.location = response.data.redirect;
        });
    }

     if (error.response.status === 500) {
         app.$message.error({
             // title: app.$t("core.internal server error"),
             message: 'Terjadi kesalahan pada sistem!',
         });
     }

    return Promise.reject(error);
});