<?php

namespace App\Services;

use Exception;
use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class PaymentService extends BaseService
{
    public function getDetails()
    {
        try {
            $bookings = Booking::with([
                'service:id,artist_id,name,price'
            ])
                ->where('artist_id', Auth::id())
                ->get(['id', 'service_id', 'artist_id', 'client_id', 'started_at', 'ended_at', 'status']);

            if ($bookings) {
                return $bookings;
            }
            return intval(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']);
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("PaymentService: getDetails", $error);
            return false;
        }
    }

    public function getTotalEarning()
    {
        try {
            $data = [
                "total_earning" => Auth::user()->total_balance,
                "pending" => Auth::user()->transections()->with('Booking:id,service_id','Booking.service:id,name')->where('transaction_status',0)->orderBy('created_at', 'desc')->get(['id','booking_id','amount','created_at']),
                "completed" => Auth::user()->transections()->where('transaction_status',1)->orderBy('created_at', 'desc')->get(),
            ];

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $data);

        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("PaymentService: getTotalEarning", $error);
            return false;
        }
    }
}
