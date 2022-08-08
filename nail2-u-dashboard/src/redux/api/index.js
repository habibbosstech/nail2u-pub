import axios from 'axios';
import {store} from "../store";
import {handleSessionEnd} from "../../components/middleware";

const APIServices = 'http://127.0.0.1:8000/api';

export function SetHeader() {
    let storeData = store.getState();
    axios.defaults.headers.common["Authorization"] = 'Bearer ' + storeData.auth.token;
}

export const Post = (r, p) => {
    return new Promise(resolve => {

        axios.post(APIServices + r, p).then(res => {
            (res.data._metadata.httpResponseCode === 401) ? handleSessionEnd() : resolve(res)
        })
    })
}

export function Get(r) {
    return new Promise(resolve => {
        axios.get(APIServices + r).then(res => {
            (res.data._metadata.httpResponseCode === 401) ? handleSessionEnd() : resolve(res)
        })
    })
}

export default {Post, Get, SetHeader};