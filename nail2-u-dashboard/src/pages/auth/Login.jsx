import React, {useState} from 'react';
import {Container,} from "react-bootstrap";
import {useDispatch, useSelector} from 'react-redux';
import {login} from '../../redux/action/auth';
import SpinnerLoader from "../../components/loaders/Spiner";
import './login.css';
import {useHistory} from "react-router-dom";

export default function Login() {

    const state = useSelector((state) => state);
    const [submitted, setSubmitted] = useState(false);
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [response, setResponse] = useState(false);
    const dispatch = useDispatch();

    const [emailError, setEmailError] = useState("");
    const [passwordError, setPasswordError] = useState("");
    const history = useHistory();

    const required = (value) => {
        if (!value) {
            return (<div className="alert alert-danger" role="alert">
                This field is required!
            </div>);
        }
    };

    return (<Container>
        <form className="loginForm">
            <center class="mt-5">
                <div className='col-md-4 formContent'>
                    <form>
                        <div className="form-group dPadding">
                            <label style={{fontWeight: "bolder"}}>Email address</label>
                            <input
                                onChange={(e) => setEmail(e.target.value)}
                                type="email"
                                name="email"
                                className="form-control"
                                placeholder="Enter email"/>
                        </div>
                        <div className="form-group">
                            <label style={{fontWeight: "bolder"}}>Password</label>
                            <input
                                onChange={(e) => setPassword(e.target.value)} type="password"
                                name="password" className="form-control"
                                placeholder="Password"/>
                        </div>
                        <div className="form-group">
                            <button onClick={handleSubmit} type="button" className="btn btn-login">
                                {submitted ? <SpinnerLoader/> : "Login"}
                            </button>
                            {response}
                        </div>
                    </form>
                </div>
            </center>
        </form>
    </Container>);

    async function handleSubmit(e) {
        e.preventDefault();

        if (email && password) {
            setSubmitted(true);
            setResponse("")
            dispatch(login({email, password})).then((r) => {
                setSubmitted(false);
                setResponse(r.data._metadata.message)
                if (r.data._metadata.httpResponseCode === 200)
                    history.push("/dashboard");
            })
        }
    }
};