import {GET_DETAILS} from "./types";
import APIServices from "../api";

export const getClientInfo = () => (dispatch) => {
    return APIServices.get('/dashboard/get-clients-count').then(res => {
        console.log("API HIT")
        // dispatch({
        //     type: GET_DETAILS, user: res.data.records.user, token: res.data.records.token,
        // });
        return res;
    })
}
