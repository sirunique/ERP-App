import { createSize, deleteSize, getSizes, getSize } from "../action/size";

// state
const state = {
    editMode: false,
    sizes: [],
    index: null,
    size: null,
    isLoading: false,
    data: {
        size_name: { value: null },
        size_short_name: { value: null },
        isAvailable: { value: true }
    },
    errors: {}
};

// getters
const getters = {};

// actions
const actions = {
    async getSizes({ commit }) {
        await commit("loading");
        try {
            const res = await getSizes();
            if (res.data.success) {
                await commit("setSizes", res.data.data.Sizes);
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async deleteSize({ commit, dispatch }) {
        await commit("loading");
        try {
            const res = await deleteSize();
            if (res.data.success) {
                await commit("spliceSize");
                await commit("unsetSize");
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
    async createSize({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await createSize();
            if (res.data.success) {
                dispatch("getSizes");
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
        await $("#sizeModal").modal("show");
    },
    async hideModal({ commit }) {
        await $("#sizeModal").modal("hide");
    },
    async showDeleteModal({ commit }) {
        await $("#deleteSizeModal").modal("show");
    },
    async hideDeleteModal({ commit }) {
        await $("#deleteSizeModal").modal("hide");
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setSizes"](state, data) {
        state.isLoading = false;
        state.sizes = data;
    },
    ["setSize"](state, data) {
        state.size = data;
    },
    ["unsetSize"](state) {
        state.size = null;
    },
    ["setIndex"](state, data) {
        state.index = data;
    },
    ["unsetIndex"](state) {
        state.index = null;
    },
    ["spliceSize"](state) {
        state.sizes.splice(state.index, 1);
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
