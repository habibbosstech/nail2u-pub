<?php

namespace App\Services;

use Exception;
use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingService extends BaseService
{
    public function listAll($request)
    {
        try {
            $items_per_page = 10;
            if ($request->has('items_per_page'))
                $items_per_page = $request->items_per_page;
            $bookings = Booking::with([
                'Client:id,username,address,cv_url,image_url',
                'Scheduler:id,time',
                'BookingService:id,name,price'
            ])
                ->select('id', 'artist_id', 'client_id', 'started_at', 'created_at')
                ->where('id', '=', $request->search)
                ->orWhereHas('Client', function ($query) use ($request) {
                    $query->where('username', 'like', '%' . $request->search . '%');
                })
                ->paginate($items_per_page);
            if ($bookings->isEmpty()) {
                return GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'];
            } else {
                return $bookings;
            }
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("BookingService: listAll", $error);
            return Helper::returnRecord(false, []);
        }
    }

    public function delete($request)
    {
        try {
            DB::beginTransaction();
            $booking = Booking::find($request->id);
            $booking->delete();
            DB::commit();
            return $booking;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("BookingService: delete", $error);
            return Helper::returnRecord(false, []);
        }
    }
}
