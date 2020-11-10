import {
    createMailSetting,
    deleteMailSetting,
    getMailSettings,
    getMailSetting
} from "../action/mailSetting";

// state
const state = {
    editMode: false,
    mailSettings: [],
    index: null,
    mailSetting: null,
    isLoading: false,
    data: {
        mail_host: { value: null },
        mail_port: { value: null },
        mail_address: { value: null },
        mail_password: { value: null },
        mail_from_name: { value: null },
        mail_encryption: { value: null },
        isDefault: { value: false }
    },
    errors: {}
};

// getters
const getters = {};

// actions
const actions = {
    async getMailSettings({ commit }) {
        await commit("loading");
        try {
            const res = await getMailSettings();
            if (res.data.success) {
                await commit("setMailSettings", res.data.data.Settings);
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async deleteMailSetting({ commit, dispatch }) {
        await commit("loading");
        try {
            const res = await deleteMailSetting();
            if (res.data.success) {
                await commit("spliceMailSetting");
                await commit("unsetMailSetting");
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
    async createMailSetting({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await createMailSetting();
            if (res.data.success) {
                dispatch("getMailSettings");
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
        await $("#mailSettingModal").modal("show");
    },
    async hideModal({ commit }) {
        await $("#mailSettingModal").modal("hide");
    },
    async showDeleteModal({ commit }) {
        await $("#deleteMailSettingModal").modal("show");
    },
    async hideDeleteModal({ commit }) {
        await $("#deleteMailSettingModal").modal("hide");
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setMailSettings"](state, data) {
        state.isLoading = false;
        state.mailSettings = data;
    },
    ["setMailSetting"](state, data) {
        state.mailSetting = data;
    },
    ["unsetMailSetting"](state) {
        state.mailSetting = null;
    },
    ["setIndex"](state, data) {
        state.index = data;
    },
    ["unsetIndex"](state) {
        state.index = null;
    },
    ["spliceMailSetting"](state) {
        state.mailSettings.splice(state.index, 1);
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
