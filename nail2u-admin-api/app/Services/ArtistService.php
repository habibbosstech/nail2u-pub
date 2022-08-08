<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Helper\Helper;
use App\Mail\AddArtistInvitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Libs\Response\GlobalApiResponseCodeBook;

class ArtistService extends BaseService
{
    public function add($request)
    {
        try {
            Mail::to($request->email)->send(new AddArtistInvitation());
            return true;
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ArtistService: add", $error);
            return Helper::returnRecord(false, []);
        }
    }

    public function listAll($request)
    {
        try {
            $items_per_page = 10;
            if ($request->has('items_per_page'))
                $items_per_page = $request->items_per_page;
            $artists = User::select('id', 'username', 'created_at', 'email', 'address', 'cv_url', 'image_url', 'phone_no')
                ->whereHas("roles", function ($q) {
                    $q->where("name", "artist");
                })
                ->withCount(['bookings' => function ($query) {
                    $query->where("status", "done");
                }])
                ->whereHas('jobs.BookingService', function ($query) {
                    // $query->groupBy('service_id');
                })
                // ->where(function ($query) use ($request) {
                //     $query->where('username', 'like', '%' . $request->search . '%');
                //     $query->orWhere('email', 'like', '%' . $request->search . '%');
                // })
                ->get();
            // ->paginate($items_per_page);
            if ($artists->isEmpty()) {
                return GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'];
            } else {
                return $artists;
            }
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ArtistService: listAll", $error);
            return Helper::returnRecord(false, []);
        }
    }

    public function delete($request)
    {
        try {
            DB::beginTransaction();
            $artist = User::where('id', $request->id)
                ->whereHas("roles", function ($q) {
                    $q->where("name", "artist");
                })->first();
            $artist->roles()->detach();
            $artist->delete();
            DB::commit();
            return $artist;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ArtistService: delete", $error);
            return Helper::returnRecord(false, []);
        }
    }
}
