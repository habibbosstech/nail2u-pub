const artistReducer = (state = {}, action) => {

    switch (action.type) {

        case "GET_ALL_ARTIST":
            return {
                ...state,
                isAuthenticated: true,
                user: action.user,
                token: action.token,
                errors: {},
            }

        case "WITHDRAW":
            return state + action.payload;

        default:
            return state;
    }

}

export default artistReducer;