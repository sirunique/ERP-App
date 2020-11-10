/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import { sync } from "vuex-router-sync";
import router from "./routes";
import store from "./store";
// import Jquery from "jquery";

require("./bootstrap");

window.Vue = require("vue");
// create global custom event listener variable
window.Fire = new Vue();
// window.$ = Jquery;

// ===== Converter ====
Vue.filter("isAvailable", function(text) {
    if (text) {
        return "True";
    } else if (!text) {
        return "False";
    }
});

import swal from "sweetalert2";
window.swal = swal;
const toast = swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: toast => {
        toast.addEventListener("mouseenter", swal.stopTimer);
        toast.addEventListener("mouseleave", swal.resumeTimer);
    }
});
window.toast = toast;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component("app", require("./components/AppComponent.vue").default);

sync(store, router);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    store,
    el: "#app"
});
