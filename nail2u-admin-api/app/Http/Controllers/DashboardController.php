<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
class DashboardController extends Controller
{
    public function __construct(DashboardService $DashboardService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->dashboard_service = $DashboardService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function getClientsCount()
    {
        $clients = $this->dashboard_service->getClientsCount();
        if (!$clients)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Clients count did not fetched!", $clients));
        return ($this->global_api_response->success($clients['clients_count'], "Clients count fetched successfully!", $clients));
    }

    public function getArtistsCount()
    {
        $artists = $this->dashboard_service->getArtistsCount();
        if (!$artists)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Artists count did not fetched!", $artists));
        return ($this->global_api_response->success($artists['artists_count'], "Artists count fetched successfully!", $artists));
    }

    public function getBookingsCount()
    {
        $bookings = $this->dashboard_service->getBookingsCount();
        if (!$bookings)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Bookings count did not fetched!", $bookings));
        return ($this->global_api_response->success(1, "Bookings count fetched successfully!", $bookings));
    }

    public function getJobPostsCount()
    {
        $job_posts = $this->dashboard_service->getJobPostsCount();
        if (!$job_posts)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Job posts count did not fetched!", $job_posts));
        return ($this->global_api_response->success(count($job_posts['job_posts']), "Job posts count fetched successfully!", $job_posts));
    }
}
