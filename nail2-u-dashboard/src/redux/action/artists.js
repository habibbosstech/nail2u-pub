//import {GET_ALL_ARTIST} from "./types";
import {Post, SetHeader} from "../api";
import React from "react";
import AlertMessage from '../../components/alerts';

export const getAllArtist = () => (dispatch) => {
    SetHeader();
    return Post('/artist/list-all', {}).then(res => {
        if (res.data._metadata.httpResponseCode === 200) {
            return res;
        } else {

        }
    })
}

export const deleteArtist = (payload) => (dispatch) => {
    SetHeader();
    return Post('/artist/delete', payload).then(res => {
        if (res.data._metadata.httpResponseCode === 200) {
            AlertMessage('success', res.data._metadata.message);
            return res;
        } else {
            AlertMessage('error', res.data._metadata.message);
        }
    })
}

export const getSingleArtist = (payload) => (dispatch) => {
    SetHeader();
    return Post('/user/get-user-detail', {id:3}).then(res => {
        if (res.data._metadata.httpResponseCode === 200) {
            return res;
        } else {
            AlertMessage('error', res.data._metadata.message);
        }
    })
}
