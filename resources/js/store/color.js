import { createColor, deleteColor, getColors, getColor } from "../action/color";

// state
const state = {
    editMode: false,
    colors: [],
    index: null,
    color: null,
    isLoading: false,
    data: {
        color_name: { value: null },
        color_hexcode: { value: null },
        isAvailable: { value: true }
    },
    errors: {}
};

// getters
const getters = {};

// actions
const actions = {
    async getColors({ commit }) {
        await commit("loading");
        try {
            const res = await getColors();
            if (res.data.success) {
                await commit("setColors", res.data.data.Colors);
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async deleteColor({ commit, dispatch }) {
        await commit("loading");
        try {
            const res = await deleteColor();
            if (res.data.success) {
                await commit("spliceColor");
                await commit("unsetColor");
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
    async createColor({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await createColor();
            if (res.data.success) {
                dispatch("getColors");
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
        await $("#colorModal").modal("show");
    },
    async hideModal({ commit }) {
        await $("#colorModal").modal("hide");
    },
    async showDeleteModal({ commit }) {
        await $("#deleteColorModal").modal("show");
    },
    async hideDeleteModal({ commit }) {
        await $("#deleteColorModal").modal("hide");
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setColors"](state, data) {
        state.isLoading = false;
        state.colors = data;
    },
    ["setColor"](state, data) {
        state.color = data;
    },
    ["unsetColor"](state) {
        state.color = null;
    },
    ["setIndex"](state, data) {
        state.index = data;
    },
    ["unsetIndex"](state) {
        state.index = null;
    },
    ["spliceColor"](state) {
        state.colors.splice(state.index, 1);
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
