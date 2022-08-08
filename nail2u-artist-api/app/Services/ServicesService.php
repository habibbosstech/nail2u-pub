<?php

namespace App\Services;

use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\ArtistService;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ServicesService extends BaseService
{
    public function allRaw()
    {
        try {
            $services_all = Service::orderBy('id', 'desc')->get(['id','name']);

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $services_all->toArray());

        } catch (\Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs(__FUNCTION__ . ' : ' . __CLASS__, $error);
            return false;
        }
    }

    public function all($request)
    {
        try {
            $item_per_page = ($request->item_per_page) ? $request->item_per_page : 10;

            $services_all = Service::orderBy('id', 'desc')->paginate($item_per_page);

            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_CREATED['outcomeCode'], $services_all->toArray());

        } catch (\Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:ServicesService: add", $error);
            return false;
        }
    }

    public function add($request)
    {
        try {
            $services = new ArtistService();
            $services->artist_id = Auth::id();
            $services->service_id = $request->service_id;
            $services->price = $request->price;
            $services->save();

            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_CREATED['outcomeCode'], $services->toArray());

        } catch (\Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:ServicesService: add", $error);
            return false;
        }
    }

    public function edit($request)
    {
        try {

            $update_services = Service::
            where('artist_id', Auth::id())
                ->where('id', $request['services_id'])->first();

            if ($update_services) {
                $update_services->discount_percentage = $request['discount_percentage'];
                $update_services->start_date = $request['start_date'];
                $update_services->end_date = $request['end_date'];
                $update_services->save();

                return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_UPDATED, $update_services->toArray());
            }

            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']);

        } catch (\Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:ServicesService: add", $error);
            return false;
        }
    }

    public function removeDiscount($id)
    {
        try {

            $update_services = Service::
            where('artist_id', Auth::id())
                ->where('id', $id)->first();

            if ($update_services) {
                $update_services->discount_percentage = null;
                $update_services->start_date = null;
                $update_services->end_date = null;

                return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_UPDATED, $update_services->toArray());
            }

            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']);

        } catch (\Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:ServicesService: add", $error);
            return false;
        }
    }
}
