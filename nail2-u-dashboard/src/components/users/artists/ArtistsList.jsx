import React, {useEffect, useState} from "react";
import "./artistlist.css";
import Search from "../../home/search/search";
import Pagination from "../customers/Pagination";
import {useDispatch, useSelector} from "react-redux";
import {getAllArtist} from "../../../redux/action/artists";
import ArtistCard from "../../system-component/artistCard";
import SpinnerLoader from "../../loaders/Spiner";


const getData = (data) => {
    let dateInstance = new Date(data);
    return dateInstance.getFullYear();
}

export default function ArtistsList() {

    const state = useSelector((state) => state);
    const dispatch = useDispatch();
    const [response, setResponse] = useState("");
    const [artistLoader, setArtistLoader] = useState('');

    useEffect(() => {
        getAllArtists();
    }, []);

    const getAllArtists = () => {
        setArtistLoader(false);
        dispatch(getAllArtist()).then((res) => {
            setResponse(res.data.records)
            setArtistLoader(true)
        })
    }


    return (
        <div>
            <div className="customerDetails">
                <Search/>
                <span style={{color: "#7A7A7A"}} className="pl-1">
          users/artists
        </span>
                <div className="card card-outline-secondary mt-2  artist__detail_pro">
                    <div
                        className="card-header"
                        style={{
                            display: "flex",
                            justifyContent: "space-between",
                        }}
                    >
                        <h3 className=" mb-0 ">List Of All artist</h3>
                        <button className="btn-add-artist">
                            <i className="fa fa-plus" aria-hidden="true"></i>
                            <span>Add new Artist</span>
                        </button>
                    </div>
                    <div className="artist-card-body pl-4">
                        {
                            artistLoader ?
                                response.map((artist, key) => {
                                    return (
                                        <ArtistCard
                                            artistId={artist.id}
                                            phoneNo={artist.phone_no}
                                            bookingsCount={artist.bookings_count}
                                            username={artist.username}
                                            profileImage={artist.absolute_image_url}
                                            workingSince={getData(artist.created_at)}
                                            rating={artist.avg_rating}
                                            getAllArtist={getAllArtists}
                                        />
                                    )
                                })

                                : <SpinnerLoader/>
                        }
                    </div>
                    <Pagination/>
                </div>
            </div>
        </div>
    );
}

