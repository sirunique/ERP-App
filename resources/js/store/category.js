import {
    createCategory,
    deleteCategory,
    getCategories,
    getCategory
} from "../action/category";

// state
const state = {
    editMode: false,
    categories: [
        // {
        //     category_id: "1",
        //     category_name: "1",
        //     isAvailable: "1"
        // },
        // {
        //     category_id: "11",
        //     category_name: "1",
        //     isAvailable: "1"
        // }
    ],
    index: null,
    category: null,
    isLoading: false,
    data: {
        category_name: { value: null },
        isAvailable: { value: true }
    },
    errors: {}
};

// getters
const getters = {
    // getCategoriesFromGetter(state) {
    //     return state.categories;
    // }
    // getCategoriesFromGetter: state => {
    //     return state.categories;
    // }
};

// actions
const actions = {
    async getCategories({ commit }) {
        commit("loading");
        try {
            const res = await getCategories();
            if (res.data.success) {
                await commit("setCategories", res.data.data.Categories);
            }
        } catch (err) {
            // console.log(err);
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async deleteCategory({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await deleteCategory();
            if (res.data.success) {
                await commit("spliceCategory");
                await commit("unsetCategory");
                await commit("unsetIndex");
                await dispatch("hideDeleteModal");
                toast.fire({
                    icon: "success",
                    title: res.data.message
                });
            }
        } catch (err) {
            // console.log(err);
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async createCategory({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await createCategory();
            if (res.data.success) {
                dispatch("getCategories");
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
        await $("#categoryModal").modal("show");
    },
    async hideModal({ commit }) {
        await $("#categoryModal").modal("hide");
    },
    async showDeleteModal({ commit }) {
        await $("#deleteCategoryModal").modal("show");
    },
    async hideDeleteModal({ commit }) {
        await $("#deleteCategoryModal").modal("hide");
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setCategories"](state, data) {
        state.isLoading = false;
        state.categories = data;
        // console.log(data);
        // console.log(state.categories);
        // let xx = [
        //     {
        //         category_id: "31",
        //         category_name: "13",
        //         isAvailable: "31"
        //     },
        //     {
        //         category_id: "115",
        //         category_name: "15",
        //         isAvailable: "15"
        //     }
        // ];
        // for (let key in xx) {
        //     state.categories.unshift(xx[key]);
        // }
        // console.log(state.categories);
    },
    ["setCategory"](state, data) {
        state.category = data;
    },
    ["unsetCategory"](state) {
        state.category = null;
    },
    ["setIndex"](state, data) {
        state.index = data;
    },
    ["unsetIndex"](state) {
        state.index = null;
    },
    ["spliceCategory"](state) {
        state.categories.splice(state.index, 1);
    },
    // ["getCategorory"](state, data) {
    //     state.isLoading = false;
    //     state.category = data;
    // }
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
