import {toast} from "react-toastify";
import {CheckmarkCircleOutline} from "react-ionicons";
import React from "react";

const success = (message) => {
    toast.success(<div>
        <CheckmarkCircleOutline color={"green"}/>
        {message}
    </div>, {
        hideProgressBar: true,
    });
}

const error = (message) => {
    toast.error(<div>
        <CheckmarkCircleOutline color={"red"}/>
        {message}
    </div>, {
        hideProgressBar: true,
    });
}

const AlertMessage = (type, message) => {

    switch (type) {
        case "success":
            return success(message);
        case "error":
            return error(message);
        default:
            return "";
    }
}
export default AlertMessage;

