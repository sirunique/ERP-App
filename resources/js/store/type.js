import { createType, deleteType, getTypes, getType } from "../action/type";

// state
const state = {
    editMode: false,
    types: [],
    index: null,
    type: null,
    isLoading: false,
    data: {
        type_name: { value: null },
        isAvailable: { value: true }
    },
    errors: {}
};

// getters
const getters = {};

// actions
const actions = {
    async getTypes({ commit }) {
        await commit("loading");
        try {
            const res = await getTypes();
            if (res.data.success) {
                await commit("setTypes", res.data.data.Types);
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async deleteType({ commit, dispatch }) {
        await commit("loading");
        try {
            const res = await deleteType();
            if (res.data.success) {
                await commit("spliceType");
                await commit("unsetType");
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
    async createType({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await createType();
            if (res.data.success) {
                dispatch("getTypes");
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
        await $("#typeModal").modal("show");
    },
    async hideModal({ commit }) {
        await $("#typeModal").modal("hide");
    },
    async showDeleteModal({ commit }) {
        await $("#deleteTypeModal").modal("show");
    },
    async hideDeleteModal({ commit }) {
        await $("#deleteTypeModal").modal("hide");
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setTypes"](state, data) {
        state.isLoading = false;
        state.types = data;
    },
    ["setType"](state, data) {
        state.type = data;
    },
    ["unsetType"](state) {
        state.type = null;
    },
    ["setIndex"](state, data) {
        state.index = data;
    },
    ["unsetIndex"](state) {
        state.index = null;
    },
    ["spliceType"](state) {
        state.types.splice(state.index, 1);
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
