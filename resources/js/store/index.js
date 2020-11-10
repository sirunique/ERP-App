import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
import VueAxios from "vue-axios";
import setup from "./setup";
import auth from "./auth";
import category from "./category";
import brand from "./brand";
import color from "./color";
import size from "./size";
import type from "./type";
import mailSetting from "./mailSetting";
import smsSetting from "./smsSetting";
import supplier from "./supplier";

Vue.use(Vuex);
// Vue.use(Vuex, VueAxios, axios);

export default new Vuex.Store({
    namedspaced: true,
    modules: {
        setup,
        auth,
        category,
        brand,
        color,
        size,
        type,
        mailSetting,
        smsSetting,
        supplier
    }
    // state: {},
    // actions: {},
    // mutations: {},
    // getters: {}
});
