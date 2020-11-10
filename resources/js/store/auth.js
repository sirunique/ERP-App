import { login, logout, loadUser } from "../action/auth";
import { getToken, removeToken, saveToken } from "../action/localStorage";
import router from "../routes";

// state
const state = {
    isAuthenticated: !!getToken(),
    isLoading: false,
    data: {
        email: { value: null },
        password: { value: null }
    },
    user: null,
    test: null,
    errors: {}
};

// getters
const getters = {
    isAuthenticated: state => {
        return state.isAuthenticated;
    },
    isLoading: state => {
        return state.isLoading;
    },
    getUser: state => {
        return state.user;
    }
};

// actions
const actions = {
    login({ commit }) {
        console.log("load user");
        commit("loading");
        login()
            .then(res => {
                console.log("load user");
                console.log(res);
                if (res.data.success && res.data.data.token) {
                    saveToken(res.data.data.token);
                    commit("loginSuccess");
                    toast.fire({
                        icon: "success",
                        title: res.data.data.message
                    });
                    router.push("/dashboard");
                }
            })
            .catch(err => {
                commit("loginFail");
                if (!err.response.data.success) {
                    toast.fire({
                        icon: "error",
                        title: err.response.data.message
                    });
                }
            });
    },
    logout({ commit }) {
        commit("loading");
        logout()
            .then(res => {
                commit("logout");
                removeToken();
                router.push("/login");
            })
            .catch(err => {
                console.log(err);
            });
    },
    loadUser({ commit }) {
        commit("loading");
        // console.log(state.isAuthenticated);
        if (!state.isAuthenticated) {
            commit("logout");
            removeToken();
            router.push("/login");
        }
        loadUser()
            .then(res => {
                if (res.data.success && res.data.data.Users.length == 1) {
                    commit("loginSuccess");
                    commit("getUser", res.data.data.Users[0]);
                    // state.user = res.data.data.Users[0];
                }
            })
            .catch(err => {
                console.log(err);
                commit("loginFail");
                router.push("/login");
            });
    }
};

// mutations
const mutations = {
    ["loading"](state) {
        state.isLoading = true;
    },
    ["logout"](state) {
        state.isAuthenticated = false;
        state.isLoading = false;
        state.user = null;
    },
    ["loginSuccess"](state) {
        state.isLoading = false;
        state.isAuthenticated = true;
    },
    ["loginFail"](state) {
        state.isLoading = false;
        state.isAuthenticated = false;
    },
    ["getUser"](state, data) {
        state.isLoading = false;
        state.user = data;
        state.test = "Test Data";
        // console.log(state);
    },
    ["setData"](state, e) {
        state.data[e.target.name].value = e.target.value;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
