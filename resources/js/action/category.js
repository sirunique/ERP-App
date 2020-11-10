import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createCategory = async () => {
    let state = store.state.category;
    let xdata = mapReqData(state.data);

    let data;
    if (!state.editMode) {
        data = await postReq("api/admin/category", xdata);
    } else {
        data = await updateReq(
            "api/admin/category/" + state.category.category_id,
            xdata
        );
    }
    return data;
};

export const deleteCategory = async () => {
    let state = store.state.category;
    if (!state.category) return;
    const data = await deleteReq(
        "api/admin/category/" + state.category.category_id
    );
    return data;
};

export const getCategory = async () => {
    let state = store.state.category;
    if (!state.category) return;
    const data = await getReq(
        "api/admin/category/" + state.category.category_id
    );
    return data;
};

export const getCategories = async () => {
    const data = await getReq("api/admin/category");
    return data;
};
