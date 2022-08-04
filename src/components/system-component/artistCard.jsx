import React, {useState} from "react";
import apic from "../users/artists/a.png";
import trash from "../users/artists/trash.png";
import p from "../users/artists/g.jpg";
import s from "../users/artists/s.png";
import StarRating from 'react-bootstrap-star-rating';
import SpinnerLoader from "../loaders/Spiner";
import {deleteArtist} from "../../redux/action/artists";
import {setArtistId} from "../../redux/action/artist-profile";
import {useDispatch} from "react-redux";
import {useHistory} from "react-router-dom";


const ArtistCard = (props) => {

    const [deleteArtistLoader, setDeleteArtistLoader] = useState(false);
    const dispatch = useDispatch();
    const history = useHistory();
    const {getAllArtist} = props;

    const handleDeleteArtist = (id) => (event) => {
        setDeleteArtistLoader(true);
        dispatch(deleteArtist({id: id})).then(res => {
            setDeleteArtistLoader(false);
            getAllArtist();
        });
    }

    const handleArtistId = (id) => (event) => {
        dispatch(setArtistId({id: id}));
        history.push("/dashboard/artists/artist-profile")
    }

    return (<div className="card m-4 artistlist">
        <img className="card-img-top" src={apic} alt="Card"/>
        <span className="toptrash">
            {(deleteArtistLoader) ? <SpinnerLoader/> :
                <img style={{cursor: 'pointer'}} src={trash} onClick={handleDeleteArtist(props.artistId)}/>}
                </span>
        <div className="card-body artist">
            <img src={props.profileImage}
                 onError={({currentTarget}) => {
                     currentTarget.onerror = null;
                     currentTarget.src = s;
                 }}
                 className="artist-profile"/>

            <p className="card-title text-center mt-3 artist-name">
                {props.username}
            </p>
            <span className="arating mb-2">
                        <StarRating
                            defaultValue={props.rating}/>

                    </span>
            <p className="card-text artist-dec ">
                Expert in Acrylic and French Manicure
            </p>
            <span>Jobs Done</span>
            <span className="float-right">Working Since</span>
            <br/>
            <span className="ml-4 num">{props.bookingsCount}</span>
            <span className="float-right num mr-5">{props.workingSince}</span>
            <p className="text-center para">
                <i className="fa fa-phone" aria-hidden="true"></i>
                <span>{props.phoneNo}</span>
            </p>

            <button
                type="button"
                className="btn btn-artist-info "
                onClick={handleArtistId(props.artistId)}>View Artist
            </button>

        </div>
    </div>);
}


export default ArtistCard;