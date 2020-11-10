import { createBrand, deleteBrand, getBrands, getBrand } from "../action/brand";

// state
const state = {
    editMode: false,
    brands: [],
    index: null,
    brand: null,
    isLoading: false,
    data: {
        brand_name: { value: null },
        isAvailable: { value: true }
    },
    errors: {}
};

// getters
const getters = {};

// actions
const actions = {
    async getBrands({ commit }) {
        await commit("loading");
        try {
            const res = await getBrands();
            if (res.data.success) {
                await commit("setBrands", res.data.data.Brands);
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async deleteBrand({ commit, dispatch }) {
        await commit("loading");
        try {
            const res = await deleteBrand();
            if (res.data.success) {
                await commit("spliceBrand");
                await commit("unsetBrand");
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
    async createBrand({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await createBrand();
            if (res.data.success) {
                dispatch("getBrands");
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
        await $("#brandModal").modal("show");
    },
    async hideModal({ commit }) {
        await $("#brandModal").modal("hide");
    },
    async showDeleteModal({ commit }) {
        await $("#deleteBrandModal").modal("show");
    },
    async hideDeleteModal({ commit }) {
        await $("#deleteBrandModal").modal("hide");
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setBrands"](state, data) {
        state.isLoading = false;
        state.brands = data;
    },
    ["setBrand"](state, data) {
        state.brand = data;
    },
    ["unsetBrand"](state) {
        state.brand = null;
    },
    ["setIndex"](state, data) {
        state.index = data;
    },
    ["unsetIndex"](state) {
        state.index = null;
    },
    ["spliceBrand"](state) {
        state.brands.splice(state.index, 1);
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
