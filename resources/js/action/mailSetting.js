import store from "../store/index";
import { mapReqData, getReq, postReq, updateReq, deleteReq } from "./auth";

export const createMailSetting = async () => {
    let state = store.state.mailSetting;
    let xdata = mapReqData(state.data);

    let data;
    if (!state.editMode) {
        data = await postReq("api/admin/mail-setting", xdata);
    } else {
        data = await updateReq(
            "api/admin/mail-setting/" + state.mailSetting.mail_setting_id,
            xdata
        );
    }
    return data;
};
export const deleteMailSetting = async () => {
    let state = store.state.mailSetting;
    if (!state.mailSetting) return;
    const data = await deleteReq(
        "api/admin/mail-setting/" + state.mailSetting.mail_setting_id
    );
    return data;
};
export const getMailSetting = async () => {
    let state = store.state.mailSetting;
    if (!state.mailSetting) return;
    const data = await getReq(
        "api/admin/mail-setting/" + state.mailSetting.mail_setting_id
    );
    return data;
};
export const getMailSettings = async () => {
    const data = await getReq("api/admin/mail-setting");
    return data;
};
