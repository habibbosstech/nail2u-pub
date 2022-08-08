import {
    INVALID_CREDENTIALS, SUCCESS_LOGIN
} from "./types";
import {Post} from "../api";

export const login = (payload) => (dispatch) => {

    return Post('/auth/login', payload).then(res => {

        if (res.data._metadata.httpResponseCode === 200) {
            dispatch({
                type: SUCCESS_LOGIN, setting: res.data.records.setting,user: res.data.records.user, token: res.data.records.token,
            });
        } else {
            dispatch({
                type: INVALID_CREDENTIALS, errors: res.data._metadata.message
            });
        }

        return res;
    })
}
