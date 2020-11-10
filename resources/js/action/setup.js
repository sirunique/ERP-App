import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createSetup = async () => {
    let state = store.state.setup;
    let xdata = mapReqData(state.data);
    let data = await postReq("api/setup", xdata);
    return data;
};

export const getSubTypes = async () => {
    let data = await getReq("api/get-sub-type");
    return data;
};
