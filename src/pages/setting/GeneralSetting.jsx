import React, {useState} from "react";
import {useForm} from "react-hook-form";
import {useDispatch, useSelector} from "react-redux";
import {generalSetting} from "../../redux/action/settings";
import SpinnerLoader from "../../components/loaders/Spiner";

export default function GeneralSetting() {

    const dispatch = useDispatch();
    const stateRaw = useSelector((state) => state);
    const state = stateRaw.auth.user;
    const [submitted, setSubmitted] = useState(false);
    const {register, formState: {errors}, handleSubmit} = useForm();

    const onSubmit = (d, e) => {
        setSubmitted(true);
        dispatch(generalSetting({
            email: d.email,
            password: d.newPassword,
            conform_password: d.conformPassword
        })).then((r) => {
            setSubmitted(false);
        })
    };

    return (
        <div>
            <div className="">
                <div className="card card-outline-secondary">
                    <div className="card-header">
                        <h3 className=" mb-0 ">General Settings</h3>
                    </div>
                    <div className="p-3 general-setting-form">
                        <form onSubmit={handleSubmit(onSubmit)}>
                            <div className="form-row">
                                <div className="form-group col-md-8">
                                    <label htmlFor="payableAmount">Email Address</label>
                                    <div
                                        style={{
                                            display: "flex",
                                        }}
                                    >
                                        <input
                                            type="email"
                                            className="form-control "
                                            id="PayableAmount"
                                            placeholder="tinafox@gmail.com"
                                            defaultValue={state.email}
                                            {...register("email", {required: true, maxLength: 20})}
                                        />
                                    </div>
                                    <div className="validation-error">
                                        {errors.email && errors.email.type === "required" && "Email is required"}
                                        {errors.email && errors.email.type === "maxLength" && "Max length is 20"}
                                    </div>
                                </div>
                            </div>

                            <div className="form-row pt-4">
                                <div className="form-group col-md-3">
                                    <label htmlFor="inputsender">Current Password</label>
                                    <input
                                        type="password"
                                        className="form-control"
                                        {...register("currentPassword", {required: true, maxLength: 10})}
                                        placeholder="......."
                                    />
                                    <div className="validation-error">
                                        {errors.currentPassword && errors.currentPassword.type === "required" && "Current password is required"}
                                        {errors.currentPassword && errors.currentPassword.type === "maxLength" && "Max length is 10"}
                                    </div>
                                </div>
                                <div className="form-group col-md-3">
                                    <label htmlFor="inputsender">New Password</label>
                                    <input
                                        type="password"
                                        className="form-control"
                                        {...register("newPassword", {required: true, maxLength: 10})}
                                    />
                                    <div className="validation-error">
                                        {errors.newPassword && errors.newPassword.type === "required" && "New password is required"}
                                        {errors.newPassword && errors.newPassword.type === "maxLength" && "Max length is 10"}
                                    </div>
                                </div>
                                <div className="form-group col-md-3">
                                    <label htmlFor="inputreciever">Confirm Password</label>
                                    <input
                                        type="password"
                                        className="form-control"
                                        {...register("conformPassword", {required: true, maxLength: 10})}
                                    />
                                    <div className="validation-error">
                                        {errors.conformPassword && errors.conformPassword.type === "required" && "Conform password is required"}
                                        {errors.conformPassword && errors.conformPassword.type === "maxLength" && "Max length is 10"}
                                    </div>
                                </div>
                                <div className="form-group col-md-3">
                                    <button className="btn ml-4 update-btn">{submitted ?
                                        <SpinnerLoader/> : "update"}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}