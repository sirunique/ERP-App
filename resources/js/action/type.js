import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createType = async () => {
    let state = store.state.type;
    let xdata = mapReqData(state.data);

    let data;
    if (!state.editMode) {
        data = await postReq("api/admin/type", xdata);
    } else {
        data = await updateReq("api/admin/type/" + state.type.type_id, xdata);
    }
    return data;
};
export const deleteType = async () => {
    let state = store.state.type;
    if (!state.type) return;
    const data = await deleteReq("api/admin/type/" + state.type.type_id);
    return data;
};
export const getType = async () => {
    let state = store.state.type;
    if (!state.type) return;
    const data = await getReq("api/admin/type/" + state.type.type_id);
    return data;
};
export const getTypes = async () => {
    const data = await getReq("api/admin/type");
    return data;
};
