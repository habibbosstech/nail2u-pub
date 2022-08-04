const dashboardReducer = (state = "", action) => {

    switch (action.type) {

        case "GET_DETAILS":
            return {
                ...state,
                data:action
            }

        default:
            return state;
    }

}

export default dashboardReducer;