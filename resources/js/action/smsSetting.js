import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createSmsSetting = async () => {
    let state = store.state.smsSetting;
    let xdata = mapReqData(state.data);

    let data;
    if (!state.editMode) {
        data = await postReq("api/admin/sms-setting", xdata);
    } else {
        data = await updateReq(
            "api/admin/sms-setting/" + state.smsSetting.sms_setting_id,
            xdata
        );
    }
    return data;
};
export const deleteSmsSetting = async () => {
    let state = store.state.smsSetting;
    if (!state.smsSetting) return;
    const data = await deleteReq(
        "api/admin/sms-setting/" + state.smsSetting.sms_setting_id
    );
    return data;
};
export const getSmsSetting = async () => {
    let state = store.state.smsSetting;
    if (!state.smsSetting) return;
    const data = await getReq(
        "api/admin/sms-setting/" + state.smsSetting.sms_setting_id
    );
    return data;
};
export const getSmsSettings = async () => {
    const data = await getReq("api/admin/sms-setting");
    return data;
};
