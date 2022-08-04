import React, {useState} from "react";
import {useDispatch, useSelector} from "react-redux";
import {useForm} from "react-hook-form";
import {profileSetting} from "../../redux/action/settings";
import SpinnerLoader from "../../components/loaders/Spiner";


export default function ProfileSetting() {

    const state = useSelector((state) => state);
    const {register, formState: {errors}, handleSubmit} = useForm();
    const [submitted, setSubmitted] = useState(false);
    const dispatch = useDispatch();

    const onSubmit = (d, e) => {
        e.preventDefault();
        setSubmitted(true);
        dispatch(profileSetting({
            username: d.userName, phone_no: d.phoneNo, address: d.address
        })).then((r) => {
            setSubmitted(false);
        })
    };

    return (<div>
        <div className="">
            <div className="card card-outline-secondary">
                <div className="card-header">
                    <h3 className=" mb-0 ">Profile Settings</h3>
                </div>
                <div>
                    <form style={{display: "flex"}} onSubmit={handleSubmit(onSubmit)}>
                        <div className="setting-image-profile p-3 m-3">
                            <p>Change Profile Picture</p>
                            <img src={state.auth.user.absolute_image_url} alt=""/>
                            <div className="circle-div-setting">
                                <div className="image-upload-setting mt-3">
                                    {" "}
                                    <label htmlFor="file_upload-setting">
                                        {" "}
                                        <img src="" alt="" className="uploaded-image"/>
                                        <div className="h-100">
                                            <div className="dplay-tbl">
                                                <div className="dplay-tbl-cell">
                                                    {" "}
                                                    <i className="fa fa-camera" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <input
                                            data-required="image"
                                            type="file"
                                            name="image_name"
                                            id="file_upload"
                                            className="image-input"
                                            data-traget-resolution="image_resolution"
                                        />
                                    </label>{" "}
                                </div>
                            </div>
                        </div>
                        <div>
                            <div className="form-row pt-4 pl-5">
                                <div className="form-group col-md-5">
                                    <label htmlFor="inputsender">Name</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        id="name"
                                        placeholder="name"
                                        defaultValue={state.auth.user.username}
                                        {...register("name", {required: true, maxLength: 20})}
                                    />
                                    <div
                                        className="validation-error">
                                        {errors.name && errors.name.type === "required" && "Name is required"}
                                        {errors.name && errors.name.type === "maxLength" && "Max length is 20"}
                                    </div>
                                </div>
                                <div className="form-group col-md-5">
                                    <label htmlFor="username">User Name</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        id="CardNumber"
                                        placeholder="username"
                                        defaultValue={state.auth.user.username}
                                        {...register("userName", {required: true, maxLength: 20})}
                                    />
                                    <div
                                        className="validation-error">
                                        {errors.userName && errors.userName.type === "required" && "Username is required"}
                                        {errors.userName && errors.userName.type === "maxLength" && "Max length is 20"}
                                    </div>
                                </div>
                            </div>

                            <div className="form-row pt-4 pl-5">
                                <div className="form-group col-md-5">
                                    <label htmlFor="inputsender">Contact</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        id="contact"
                                        placeholder="no#"
                                        defaultValue={state.auth.user.phone_no}
                                        {...register("phoneNo", {required: true, maxLength: 11})}
                                    />
                                    <div
                                        className="validation-error">
                                        {errors.phoneNo && errors.phoneNo.type === "required" && "Phone Number is required"}
                                        {errors.phoneNo && errors.phoneNo.type === "maxLength" && "Max length is 11"}
                                    </div>
                                </div>
                                <div className="form-group col-md-5">
                                    <label htmlFor="username">Address</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        id="address"
                                        placeholder="address"
                                        defaultValue={(state.auth.user.address) ? "" : state.auth.user.address}
                                        {...register("address", {required: true})}
                                    />
                                    <div
                                        className="validation-error">
                                        {errors.address && errors.address.type === "required" && "Address is required"}
                                    </div>
                                </div>
                                <div className="form-group col-md-4">
                                    <button type="submit" className="btn btn-profile-update">
                                        {submitted ? <SpinnerLoader/> : "Save changes"}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>);
}
