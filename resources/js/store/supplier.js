import {
    createSupplier,
    deleteSupplier,
    getSuppliers,
    getSupplier
} from "../action/supplier";

// state
const state = {
    editMode: false,
    suppliers: [],
    index: null,
    supplier: null,
    isLoading: false,
    data: {
        supplier_company_name: { value: null },
        supplier_vat_no: { value: null },
        supplier_email: { value: null },
        supplier_phone_no: { value: null },
        supplier_address: { value: null },
        supplier_country: { value: null },
        supplier_city: { value: null },
        supplier_state: { value: null },
        supplier_postal_code: { value: null },
        isAvailable: { value: true }
    },
    errors: {}
};

// getters
const getters = {};

// actions
const actions = {
    async getSuppliers({ commit }) {
        await commit("loading");
        try {
            const res = await getSuppliers();
            if (res.data.success) {
                await commit("setSuppliers", res.data.data.Suppliers);
            }
        } catch (err) {
            toast.fire({
                icon: "error",
                title: err.response.data.message
            });
        }
    },
    async deleteSupplier({ commit, dispatch }) {
        await commit("loading");
        try {
            const res = await deleteSupplier();
            if (res.data.success) {
                await commit("spliceSupplier");
                await commit("unsetSupplier");
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
    async createSupplier({ commit, dispatch }) {
        commit("loading");
        try {
            const res = await createSupplier();
            if (res.data.success) {
                dispatch("getSuppliers");
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
        await $("#supplierModal").modal("show");
    },
    async hideModal({ commit }) {
        await $("#supplierModal").modal("hide");
    },
    async showDeleteModal({ commit }) {
        await $("#deleteSupplierModal").modal("show");
    },
    async hideDeleteModal({ commit }) {
        await $("#deleteSupplierModal").modal("hide");
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["setSuppliers"](state, data) {
        state.isLoading = false;
        state.suppliers = data;
    },
    ["setSupplier"](state, data) {
        state.supplier = data;
    },
    ["unsetSupplier"](state) {
        state.supplier = null;
    },
    ["setIndex"](state, data) {
        state.index = data;
    },
    ["unsetIndex"](state) {
        state.index = null;
    },
    ["spliceSupplier"](state) {
        state.suppliers.splice(state.index, 1);
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
