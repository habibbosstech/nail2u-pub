import Search from "../../components/home/search/search";
import "./admin.css";
import React, {useEffect, useState} from "react";
import {Modal} from "react-bootstrap";
import {useDispatch} from "react-redux";
import {createAdmin, getAdminDetails, getAllAdminRaw} from "../../redux/action/admin";
import MUIDataTable from "mui-datatables";
import SpinnerLoader from "../../components/loaders/Spiner";
import {useForm} from "react-hook-form";

export default function Admin() {
    const [show, setShow] = useState(false);
    const [editFlag, setEditFlag] = useState(false);
    const dispatch = useDispatch();
    const [data, setData] = useState([]);
    const [editData, setEditData] = useState(null);
    const [adminLoader, setAdminLoader] = useState("");
    const [createAdminLoader, setCreateAdminLoader] = useState("");
    const {register, formState: {errors}, handleSubmit, reset} = useForm();
    const columns = [{name: "id", label: "ID", options: {filter: true, sort: true}}, {
        name: "username", label: "Name", options: {filter: true, sort: true},
    }, {name: "email", label: "Email", options: {filter: true, sort: true}}, {
        name: "phone_no", label: "Phone no#", options: {filter: true, sort: true},
    }, {
        label: "Status", name: "status", options: {
            filter: true, customBodyRender: (value, tableMeta, updateValue) => {
                return value == 1 ? (<div className="admin-status">
                    <div className="admin-active"></div>
                </div>) : (<div className="admin-status">
                    <div className="admin-offline"></div>
                </div>);
            },
        },
    }, {
        label: "Actions", name: "id", options: {
            filter: true, customBodyRender: (dataIndex, rowIndex) => {
                return (<span className="cursor-active" style={{color: "#1890ff"}} onClick={() => {
                    handleEditModel(dataIndex)
                }}>
              view Detail
            </span>);
            }, setCellProps: () => ({
                style: {
                    // whiteSpace: "nowrap",
                    // position: "sticky",
                    // left: 0,
                    // background: "white",
                    // zIndex: 100,
                    // border: "1px solid rgba(224,224,224,1)",
                },
            }), setCellHeaderProps: () => ({
                style: {
                    cursor: "pointer",
                },
            }),
        },
    },];
    const options = {
        textLabels: {
            body: {
                noMatch: adminLoader ? (<SpinnerLoader/>) : ("Sorry, there is no matching data to display"),
            },
        }, //customToolbar: () => (
        // <button className="btn-add-artist" onClick={handleModal}>
        //     <i className="fa fa-plus" aria-hidden="true"></i>
        //     <span>Add new Admin</span>
        // </button>
        //),
    };

    const handleEditModel = (id) => {
        setEditFlag(true);
        let defaultValues = {};
        dispatch(
            getAdminDetails({id: id}))
            .then((res) => {
                setEditData(res.data.records);
            });
        //console.log(editData);
        let data = editData[0];
        defaultValues.username = data.username;
        defaultValues.email = data.email;
        defaultValues.phone_no = data.phone_no;
        reset(defaultValues);
        setShow(true);
    }

    useEffect(() => {
        getAllAdmins();
    }, []);

    const handleModal = () => {
        setShow(!show);
    };

    const getAllAdmins = () => {
        setAdminLoader(true);
        dispatch(getAllAdminRaw()).then((res) => {
            setAdminLoader(false);
            setData(res.data.records.data);
        });
    };

    const resetFields = () => {
        let defaultValues = {};
        defaultValues.email = '';
        defaultValues.password = '';
        defaultValues.phone_no = '';
        reset(defaultValues);
    }

    const onSubmit = (d, e) => {
        e.preventDefault();

        setCreateAdminLoader(true);

        dispatch(createAdmin({
            username: d.username, email: d.email, password: d.password, phone_no: d.phone_no
        })).then(() => {
            setCreateAdminLoader(false);
            resetFields();
            handleModal();
            getAllAdmins();
        });
    };

    return (<div className="admin">
        <div>
            <Modal show={show} onHide={handleModal}>
                <Modal.Header closeButton>
                    <b>Add Admin</b>
                </Modal.Header>
                <form onSubmit={handleSubmit(onSubmit)} id="create-admin-form">
                    <Modal.Body>
                        <div className="form-group dPadding">
                            <label style={{fontWeight: "bolder"}}>Username</label>
                            <input
                                type="text"
                                className="form-control"
                                placeholder="Enter username"
                                {...register("username", {required: true, maxLength: 20})}
                            />
                            <div className="validation-error">
                                {errors.username && errors.username.type === "required" && "Username is required"}
                                {errors.username && errors.username.type === "maxLength" && "Max length is 20"}
                            </div>
                        </div>

                        <div className="form-group dPadding">
                            <label style={{fontWeight: "bolder"}}>Email</label>
                            <input
                                type="email"
                                className="form-control"
                                placeholder="Enter email"
                                {...register("email", {required: true, maxLength: 20})}
                            />
                            <div className="validation-error">
                                {errors.email && errors.email.type === "required" && "Email is required"}
                                {errors.email && errors.email.type === "maxLength" && "Max length is 20"}
                            </div>
                        </div>

                        <div className="form-group dPadding">
                            <label style={{fontWeight: "bolder"}}>Password</label>
                            <input
                                type="text"
                                className="form-control"
                                placeholder="Enter password"
                                {...register("password", {required: true, maxLength: 20})}
                            />
                            <div className="validation-error">
                                {errors.password && errors.password.type === "required" && "Password is required"}
                                {errors.password && errors.password.type === "maxLength" && "Max length is 20"}
                            </div>
                        </div>

                        <div className="form-group dPadding">
                            <label style={{fontWeight: "bolder"}}>Phone Number</label>
                            <input
                                type="number"
                                className="form-control"
                                placeholder="Enter phone no#"
                                {...register("phone_no", {required: true, maxLength: 11, minLength: 11})}
                            />
                            <div className="validation-error">
                                {errors.phone_no && errors.phone_no.type === "required" && "Phone no is required"}
                                {errors.phone_no && errors.phone_no.type === "maxLength" && "Max length is 11"}
                                {errors.phone_no && errors.phone_no.type === "minLength" && "Min length is 11"}
                            </div>
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <button className="btn btn-model" onClick={handleModal}>
                            Close
                        </button>
                        <button type="submit" className="btn btn-model">
                            {createAdminLoader ? <SpinnerLoader/> : "Save"}
                        </button>
                    </Modal.Footer>
                </form>
            </Modal>
        </div>
        <Search/>
        <div className="card card-outline-secondary mt-2 ">
            <div
                className="card-header"
                style={{
                    display: "flex", justifyContent: "space-between",
                }}
            >
                <h3 className=" mb-0 ">List Of Admins</h3>
                <button className="btn-add-artist" onClick={handleModal}>
                    <i className="fa fa-plus" aria-hidden="true"></i>
                    <span>Add new Admin</span>
                </button>
            </div>
            <div id="yes">
                <MUIDataTable data={data} columns={columns} options={options}/>
            </div>
        </div>
    </div>);
}
