import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createSupplier = async () => {
    let state = store.state.supplier;
    let xdata = mapReqData(state.data);

    let data;
    if (!state.editMode) {
        data = await postReq("api/admin/supplier", xdata);
    } else {
        data = await updateReq(
            "api/admin/supplier/" + state.supplier.supplier_id,
            xdata
        );
    }
    return data;
};
export const deleteSupplier = async () => {
    let state = store.state.supplier;
    if (!state.supplier) return;
    const data = await deleteReq(
        "api/admin/supplier/" + state.supplier.supplier_id
    );
    return data;
};
export const getSupplier = async () => {
    let state = store.state.supplier;
    if (!state.supplier) return;
    const data = await getReq(
        "api/admin/supplier/" + state.supplier.supplier_id
    );
    return data;
};
export const getSuppliers = async () => {
    const data = await getReq("api/admin/supplier");
    return data;
};
