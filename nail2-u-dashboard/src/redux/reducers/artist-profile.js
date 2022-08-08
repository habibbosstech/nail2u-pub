const artistProfileReducer = (state = "", action) => {
    switch (action.type) {

        case "SET_ARTIST_ID":
            return {
                ...state,
                artist_id: action.body.id
            }
        default:
            return state;
    }

}

export default artistProfileReducer;