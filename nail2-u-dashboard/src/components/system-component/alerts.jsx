import React, {useEffect} from "react";
import { useAlert } from "react-alert";

const Home = () => {
    const alert = useAlert();
    const success = ()=>{
        alert.success("It's ok now!");
    }

    useEffect(() => {
        success()
    },[])

    return (
        null
    );
};

export default Home;