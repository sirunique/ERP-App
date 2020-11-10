import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createSize = async () => {
    let state = store.state.size;
    let xdata = mapReqData(state.data);

    let data;
    if (!state.editMode) {
        data = await postReq("api/admin/size", xdata);
    } else {
        data = await updateReq("api/admin/size/" + state.size.size_id, xdata);
    }
    return data;
};
export const deleteSize = async () => {
    let state = store.state.size;
    if (!state.size) return;
    const data = await deleteReq("api/admin/size/" + state.size.size_id);
    return data;
};
export const getSize = async () => {
    let state = store.state.size;
    if (!state.size) return;
    const data = await getReq("api/admin/size/" + state.size.size_id);
    return data;
};
export const getSizes = async () => {
    const data = await getReq("api/admin/size");
    return data;
};
