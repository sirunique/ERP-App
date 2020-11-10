import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createColor = async () => {
    let state = store.state.color;
    let xdata = mapReqData(state.data);

    let data;
    if (!state.editMode) {
        data = await postReq("api/admin/color", xdata);
    } else {
        data = await updateReq(
            "api/admin/color/" + state.color.color_id,
            xdata
        );
    }
    return data;
};
export const deleteColor = async () => {
    let state = store.state.color;
    if (!state.color) return;
    const data = await deleteReq("api/admin/color/" + state.color.color_id);
    return data;
};
export const getColor = async () => {
    let state = store.state.color;
    if (!state.color) return;
    const data = await getReq("api/admin/color/" + state.color.color_id);
    return data;
};
export const getColors = async () => {
    const data = await getReq("api/admin/color");
    return data;
};
