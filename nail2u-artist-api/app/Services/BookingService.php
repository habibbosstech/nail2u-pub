<?php

namespace App\Services;

use Exception;
use App\Helper\Helper;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Libs\Response\GlobalApiResponseCodeBook;

class BookingService extends BaseService
{
    public function getJobHistory($request)
    {
        try {
            $response = [];
            $value = Auth::id();
            $transactions = Transaction::with([
                'booking'
            ])
                ->whereHas('booking',  function ($q) use ($request, $value) {
                    $q->where('status', $request->status);
                    $q->where('artist_id', $value);
                })
                ->get();

            if ($transactions->isNotEmpty()) {
                foreach ($transactions as $transaction) {
                    $temp['client_name'] = $transaction->booking->client->username;
                    $temp['amount'] = $transaction->amount;
                    $temp['time'] = date('h:i a', strtotime($transaction->created_at));
                    $temp['day'] = Helper::getDays($transaction->created_at);
                    array_push($response, $temp);
                }
                return $response;
            }
            return GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'];
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:BookingService: getJobHistory", $error);
            return false;
        }
    }
}
