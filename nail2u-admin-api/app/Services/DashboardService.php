<?php

namespace App\Services;

use Exception;
use App\Helper\Helper;
use App\Models\Booking;
use App\Models\User;
use App\Models\UserPostedService;
use Carbon\Carbon;

class DashboardService extends BaseService
{
    public function getClientsCount()
    {
        try {
            return [
                'clients_count' => User::role('user')->count(),
                'today_registered_clients' => User::role('user')->where('created_at', Carbon::today())->count()
            ];
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DashboardService: getClientsCount", $error);
            return false;
        }
    }

    public function getArtistsCount()
    {
        try {
            return [
                'artists_count' => User::role('artist')->count(),
                'today_registered_artists' => User::role('artist')->where('created_at', Carbon::today())->count()
            ];
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DashboardService: getArtistsCount", $error);
            return false;
        }
    }

    public function getBookingsCount()
    {
        try {
            return [
                'today_bookings' => Booking::where('created_at', Carbon::today())->count(),
                'pre_bookings' => Booking::where('status', 'pre_booking')->count(),
                'cancelled_bookings' => Booking::where('status', 'cancel')->count()
            ];
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DashboardService: getBookingsCount", $error);
            return false;
        }
    }

    public function getJobPostsCount()
    {
        try {
            $job_posts = UserPostedService::get();
            return [
                'job_posts' => $job_posts,
                'job_posts_count' => count($job_posts),
                'today_job_posts' => UserPostedService::where('created_at', Carbon::today())->count()
            ];
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DashboardService: getJobPostsCount", $error);
            return false;
        }
    }
}
