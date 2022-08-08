<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\deleteRequest;
use App\Http\Requests\Booking\ListAllRequest;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\BookingService;

class BookingController extends Controller
{
    public function __construct(BookingService $BookingService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->booking_service = $BookingService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function listAll(ListAllRequest $request)
    {
        $bookings = $this->booking_service->listAll($request);
        if ($bookings === GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS, "Bookings not found!", []));
        if (!$bookings)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Bookings did not fetched!", $bookings));
        return ($this->global_api_response->success(count($bookings), "Bookings fetched successfully!", $bookings));
    }

    public function delete(deleteRequest $request)
    {
        $deleted = $this->booking_service->delete($request);
        if (!$deleted)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Booking did not deleted!", $deleted));
        return ($this->global_api_response->success(1, "Booking deleted successfully!", $deleted));
    }
}