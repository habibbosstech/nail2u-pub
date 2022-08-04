import {Get, Post, SetHeader} from "../api";
import AlertMessage from "../../components/alerts";

export const getAllAdminRaw = () => (dispatch) => {
    SetHeader();
    return Get("/admins/all").then((res) => {
        if (res.data._metadata.httpResponseCode === 200) {
            return res;
        } else {
            AlertMessage("error", res.data._metadata.message);
        }
    });
};

export const createAdmin = (payload) => (dispatch) => {
    SetHeader();
    return Post("/admins/add", payload).then((res) => {
        if (res.data._metadata.httpResponseCode === 200) {
            return res;
        } else {
            AlertMessage("error", res.data._metadata.message);
        }
    });
};

export const getAdminDetails = (payload) => (dispatch) => {
    SetHeader();
    return Get("/admins/get-details/" + payload.id).then((res) => {
        if (res.data._metadata.httpResponseCode === 200) {
            return res;
        } else {
            AlertMessage("error", res.data._metadata.message);
        }
    });
};
