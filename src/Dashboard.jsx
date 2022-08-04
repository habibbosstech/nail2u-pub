import Sidebar from "./components/sidebar/Sidebar";
import Topbar from "./components/topbar/Topbar";
import "./App.css";
import Home from "./pages/home/Home";
import Customers from "./pages/users/Customers";
import Booking from "./pages/booking/Booking";
import DailyDeal from "./pages/services/DailyDeal";
import AllServices from "./pages/services/AllServices";
import Team from "./pages/team/Team";
import Chat from "./pages/chat/Chat";
import Admin from "./pages/admin/Admin";
import Setting from "./pages/setting/Setting";
import Artists from "./pages/users/Artists";
import Pcustomers from "./pages/payment/Pcustomers";
import Partists from "./pages/payment/Partists";
import ArtistProfile from "./pages/users/ArtistProfile";
import Login from "./pages/auth/Login";


import {BrowserRouter as Router, Switch, Route} from "react-router-dom";
import CustomerProfile from "./pages/users/CustomerProfile";
import AdminProfile from "./pages/admin/AdminProfile";


//layouts
import {LayoutLogin, LayoutBasic} from './layouts/layout';
import {ProtectedRoute} from "./components/middleware";



export default function Dashborad() {
    return (
        <Router>
            <Switch>
                <Route exact path="/login"
                       render={() => <LayoutLogin><Login/></LayoutLogin>}
                />
                <Route exact path="/dashboard"
                       render={() => <LayoutBasic><Home/></LayoutBasic>}
                />
                <Route exact path="/dashboard/customers"
                       render={() => <LayoutBasic><Customers/></LayoutBasic>}
                />
                <Route exact path="/dashboard/customers/customer-profile"
                       render={() => <LayoutBasic><CustomerProfile/></LayoutBasic>}
                />
                <Route exact path="/dashboard/artists"
                       render={() => <LayoutBasic><Artists/></LayoutBasic>}
                />

                <Route exact path="/dashboard/artists/artist-profile"
                render={() => <LayoutBasic><ArtistProfile/></LayoutBasic>}
                />
                <Route path="/dashboard/booking"
                render={() => <LayoutBasic><Booking/></LayoutBasic>}
                />
                <Route path="/dashboard/daily-deals"
                render={() => <LayoutBasic><DailyDeal/></LayoutBasic>}
                />
                <Route path="/dashboard/all-services"
                render={() => <LayoutBasic><AllServices/></LayoutBasic>}
                />
                <Route path="/dashboard/payments/customers"
                render={() => <LayoutBasic><Pcustomers/></LayoutBasic>}
                />
                <Route path="/dashboard/payments/artists"
                render={() => <LayoutBasic><Partists/></LayoutBasic>}
                />
                <Route path="/dashboard/team"
                render={() => <LayoutBasic><Team/></LayoutBasic>}
                />
                <Route path="/dashboard/chat"
                render={() => <LayoutBasic><Chat/></LayoutBasic>}
                />
                <ProtectedRoute exact path="/dashboard/admin"
                render={() => <LayoutBasic><Admin/></LayoutBasic>}
                />
                <Route exact path="/dashboard/admin/admin-profile"
                render={() => <LayoutBasic><AdminProfile/></LayoutBasic>}
                />
                <Route path="/dashboard/setting"
                render={() => <LayoutBasic><Setting/></LayoutBasic>}
                />
            </Switch>
        </Router>
    );
}
