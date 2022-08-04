import React from "react";
import ReactDOM from "react-dom";
import {Provider} from "react-redux";
import {store, persistor} from "./redux/store";
import App from "./App";
import {PersistGate} from "redux-persist/integration/react";
import {ToastContainer} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import './toast.css';
import 'antd/dist/antd.css';

ReactDOM.render(
    <Provider store={store}>
        <PersistGate loading={null} persistor={persistor}>
            <React.StrictMode>
                <ToastContainer
                    position="top-right"
                    // autoClose={5000}
                    newestOnTop={false}
                    closeOnClick
                    rtl={false}
                    pauseOnFocusLoss
                    draggable
                    pauseOnHover
                />
                <App/>
            </React.StrictMode>
        </PersistGate>
    </Provider>,

    document.getElementById("root")
);