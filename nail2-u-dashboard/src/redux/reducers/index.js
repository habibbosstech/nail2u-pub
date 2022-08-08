import {combineReducers} from "redux";
import auth from "./auth";
import dashboard from "./dashboard";
import artistProfile from "./artist-profile";
import setting from "./settings";

export default combineReducers({
    auth,
    dashboard,
    artistProfile,
    setting
});
