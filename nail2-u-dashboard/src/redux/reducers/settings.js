const settingReducer = (state = "", action) => {
    switch (action.type) {

        case "GENERAL_SETTING":
            return {
                ...state,
                artist_id: action.body.id
            }

        default:
            return state;
    }

}
export default settingReducer;