import React from 'react';
import Topbar from "../components/topbar/Topbar";
import Sidebar from "../components/sidebar/Sidebar";

const LayoutLogin = (props) => (
    <div>
        <h1 style={{textAlign:'center',fontWeight:'bolder'}}>Welcome To Nail 2 You Dashboard</h1>
        {props.children}
    </div>
)

const LayoutBasic = (props) => (
    <div>
        <Topbar/>
        <div className="container-flex">
            <Sidebar/>
            {props.children}
        </div>
    </div>
)

export {LayoutLogin, LayoutBasic};