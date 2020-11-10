import axios from "axios";
import { getToken } from "./localStorage";
import store from "../store/index";

export function login() {
    let state = store.state.auth;
    let data = mapReqData(state.data);

    return postReq("/api/login", data);
}

export function logout() {
    return postReq("/api/logout");
}

export function loadUser() {
    return getReq("/api/admin/user");
}

export const mapReqData = d => {
    let data = {};
    for (const key in d) {
        data[key] = d[key].value;
    }
    return data;
};

export const postReq = (url, data) => {
    return axios.post(url, data, headersConfig());
};

export const updateReq = (url, data) => {
    return axios.put(url, data, headersConfig());
};

export const deleteReq = (url, data) => {
    return axios.delete(url, headersConfig());
};

export const getReq = url => {
    return axios.get(url, headersConfig());
};

const headersConfig = () => {
    const config = {
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json"
        }
    };
    if (getToken()) {
        config.headers["Authorization"] = getToken();
    }
    return config;
};
