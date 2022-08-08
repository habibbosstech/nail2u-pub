import {SET_ARTIST_ID} from "./types";

export const setArtistId = (payload) => (dispatch) => {
    dispatch({
        type: SET_ARTIST_ID, body: {
            id: payload.id
        }
    });

    return payload;

}