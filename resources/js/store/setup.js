import { createSetup, getSubTypes } from "../action/setup";
import router from "../routes";

// state
const state = {
    data: {
        sub_type_id: { value: 1 },
        business_name: { value: null },
        business_email: { value: null },
        business_phone: { value: null },
        business_address: { value: null },
        business_country: { value: null },
        business_timezone: { value: "Africa/Lagos" },
        business_default_language: { value: "en" },
        business_currency_symbol: { value: "&#8358;" },
        user_fullname: { value: null },
        user_phone: { value: null },
        user_address: { value: null },
        user_city: { value: null },
        user_country: { value: null },
        email: { value: null },
        password: { value: null }
    },
    isLoading: false,
    errors: {},
    subTypes: []
};

// getters
const getters = {};

// actions
const actions = {
    async createSetup({ commit }) {
        commit("loading");
        try {
            const res = await createSetup();
            if (res.data.success) {
                toast.fire({
                    icon: "success",
                    title: res.data.message
                });
                router.push("/login");
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });

            if (!err.response.data.success) {
                $("#error_msg").text(err.response.data.message);
            }

            if (err.response.status === 422) {
                for (let key in err.response.data.errors) {
                    if (Object.keys(state.data).some(x => x == key)) {
                        $("#" + key + "_error").text(
                            err.response.data.errors[key][0]
                        );
                        $("#" + key).addClass("is-invalid");
                    }
                }
            }
        }
    },
    async getSubTypes({ commit }) {
        try {
            commit("loading");
            const res = await getSubTypes();
            console.log(res);
        } catch (err) {
            console.log(err);
            console.log(err.response);
        }
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setData"](state, e) {
        state.data[e.target.name].value = e.target.value;
    },
    ["xsetdata"](state, datax) {
        for (let key in datax[0]) {
            state.data[datax[0][key]].value = datax[1][datax[0][key]];
        }
    },
    ["ysetdata"](state, datax) {
        // state.data[datax[0]].value = null;
    },
    ["resetError"](state) {
        $("#error_msg").text("");
        for (let key in state.data) {
            $("#" + key + "_error").text("");
            $("#" + key).removeClass("is-invalid");
        }
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
