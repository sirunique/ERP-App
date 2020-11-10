import {
    createSmsSetting,
    deleteSmsSetting,
    getSmsSettings,
    getSmsSetting
} from "../action/smsSetting";

// state
const state = {
    editMode: false,
    smsSettings: [],
    index: null,
    smsSetting: null,
    isLoading: false,
    data: {
        sms_twilio_account_sid: { value: null },
        sms_twilio_auth_token: { value: null },
        sms_twilio_number: { value: null },
        isDefault: { value: false }
    },
    errors: {}
};

// getters
const getters = {};

// actions
const actions = {
    async getSmsSettings({ commit }) {
        await commit("loading");
        try {
            const res = await getSmsSettings();
            if (res.data.success) {
                await commit("setSmsSettings", res.data.data.Settings);
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async deleteSmsSetting({ commit, dispatch }) {
        await commit("loading");
        try {
            const res = await deleteSmsSetting();
            if (res.data.success) {
                await commit("spliceSmsSetting");
                await commit("unsetSmsSetting");
                await commit("unsetIndex");
                await dispatch("hideDeleteModal");
                toast.fire({
                    icon: "success",
                    title: res.data.message
                });
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async createSmsSetting({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await createSmsSetting();
            if (res.data.success) {
                dispatch("getSmsSettings");
                dispatch("hideModal");
                toast.fire({
                    icon: "success",
                    title: res.data.message
                });
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
    async showModal({ commit }) {
        await $("#smsSettingModal").modal("show");
    },
    async hideModal({ commit }) {
        await $("#smsSettingModal").modal("hide");
    },
    async showDeleteModal({ commit }) {
        await $("#deleteSmsSettingModal").modal("show");
    },
    async hideDeleteModal({ commit }) {
        await $("#deleteSmsSettingModal").modal("hide");
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setSmsSettings"](state, data) {
        state.isLoading = false;
        state.smsSettings = data;
    },
    ["setSmsSetting"](state, data) {
        state.smsSetting = data;
    },
    ["unsetSmsSetting"](state) {
        state.smsSetting = null;
    },
    ["setIndex"](state, data) {
        state.index = data;
    },
    ["unsetIndex"](state) {
        state.index = null;
    },
    ["spliceSmsSetting"](state) {
        state.smsSettings.splice(state.index, 1);
    },
    ["setEditMode"](state, data) {
        state.editMode = data;
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
