<?php

namespace App\Services;

use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\Booking;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class PaymentService extends BaseService
{
    // public function getDetails()
    // {
    //     try {
    //         $bookings = Booking::with([
    //             'service:id,artist_id,name,price'
    //         ])
    //             ->where('artist_id', Auth::id())
    //             ->get(['id', 'service_id', 'artist_id', 'client_id', 'started_at', 'ended_at', 'status']);

    //         if ($bookings) {
    //             return $bookings;
    //         }
    //         return intval(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']);
    //     } catch (Exception $e) {

    //         $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
    //         Helper::errorLogs("Artist:PaymentService: getDetails", $error);
    //         return false;
    //     }
    // }

    public function getTotalEarning()
    {
        try {
            $pending = Auth::user()->transections()->with('Booking', 'Booking.BookingService')->where('transaction_status', '=', 0)->get()->toArray();
            $pending_list = [];
            $booking_service = $pending[0]['booking']['booking_service'];

            foreach ($booking_service as $services) {

                $pending_list[] = [
                    'services_name' => $services['name'],
                    'created_time' => date("h:i a", strtotime($pending[0]['booking']['created_at'])),
                    'created_day' => Helper::getDays(Carbon::parse($pending[0]['booking']['created_at'])),
                    'price' => $services['price'],
                ];
            }

            $completed = Auth::user()->transections()->with('Booking', 'Booking.BookingService')->where('transaction_status', '=', 1)->get()->toArray();
            $completed_list = [];
            $complete_service = $completed[0]['booking']['booking_service'];

            foreach ($complete_service as $services) {

                $completed_list[] = [
                    'services_name' => $services['name'],
                    'created_time' => date("h:i a", strtotime($pending[0]['booking']['created_at'])),
                    'created_day' => Helper::getDays(Carbon::parse($pending[0]['booking']['created_at'])),
                    'price' => $services['price'],
                ];
            }

            $data = [
                "total_earning" => Auth::user()->total_balance,
                "pending" => $pending_list,
                "completed" => $completed_list,
            ];

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $data);

        } catch (Exception $e) {
            dd($e->getMessage());
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:PaymentService: getTotalEarning", $error);
            return false;
        }
    }
}
