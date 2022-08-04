import {store} from "../store";

const ID_TOKEN_KEY = "token";

function getToken() {
    return store.getState();
};

const saveToken = token => {
    window.localStorage.setItem(ID_TOKEN_KEY, token);
};

const destroyToken = () => {
    window.localStorage.removeItem(ID_TOKEN_KEY);
};

export default {
    getToken,
    saveToken,
    destroyToken
};