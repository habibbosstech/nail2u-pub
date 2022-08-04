import "./home.css";
import Search from "../../components/home/search/search";
import ClientInfoSection from "../../components/home/clientInfoSection/ClientInfoSection";
import RecentActivity from "../../components/home/recentActivity/RecentActivity";
import ArtistDetail from "../../components/home/artistDetail/ArtistDetail";
import JobPost from "../../components/home/jobPost/JobPost";
import {useSelector} from "react-redux";
export default function Home() {


    // const state = useSelector((state) => state);
    // console.log('This is State data',state.auth)


  return (
    <div className="home">
      <Search />
      <ClientInfoSection />
      <RecentActivity />
      <ArtistDetail />
      <JobPost />
    </div>
  );
}
