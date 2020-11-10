import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createBrand = async () => {
    let state = store.state.brand;
    let xdata = mapReqData(state.data);

    let data;
    if (!state.editMode) {
        data = await postReq("api/admin/brand", xdata);
    } else {
        data = await updateReq(
            "api/admin/brand/" + state.brand.brand_id,
            xdata
        );
    }
    return data;
};
export const deleteBrand = async () => {
    let state = store.state.brand;
    if (!state.brand) return;
    const data = await deleteReq("api/admin/brand/" + state.brand.brand_id);
    return data;
};
export const getBrand = async () => {
    let state = store.state.brand;
    if (!state.brand) return;
    const data = await getReq("api/admin/brand/" + state.brand.brand_id);
    return data;
};
export const getBrands = async () => {
    const data = await getReq("api/admin/brand");
    return data;
};
