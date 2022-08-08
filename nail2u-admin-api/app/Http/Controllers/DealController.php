<?php

namespace App\Http\Controllers;

use App\Http\Requests\DealRequests\AddNewRequest;
use App\Http\Requests\DealRequests\DeleteRequest;
use App\Http\Requests\DealRequests\UpdateDealsRequest;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\DealService;

class DealController extends Controller
{
    public function __construct(DealService $DealService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->deal_service = $DealService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function addNew(AddNewRequest $request)
    {
        $added = $this->deal_service->addNew($request);
        if (!$added)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Email did not send to the address!", $added));
        return ($this->global_api_response->success(1, "Email sent successfully!", $added));
    }

    public function listOngoing()
    {
        $ongoing_deals = $this->deal_service->listOngoing();
        if ($ongoing_deals === GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS, "Ongoing deals not found!", []));
        if (!$ongoing_deals)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Ongoing deals did not fetched!", $ongoing_deals));
        return ($this->global_api_response->success(count($ongoing_deals), "Ongoing deals fetched successfully!", $ongoing_deals));
    }

    public function listAll()
    {
        $all_deals = $this->deal_service->listAll();
        if ($all_deals === GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS, "Deals not found!", []));
        if (!$all_deals)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Deals did not fetched!", $all_deals));
        return ($this->global_api_response->success(count($all_deals), "All deals fetched successfully!", $all_deals));
    }

    public function delete(DeleteRequest $request)
    {
        $deleted = $this->deal_service->delete($request);
        if (!$deleted)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Deal did not deleted!", $deleted));
        return ($this->global_api_response->success(1, "Deal deleted successfully!", $deleted));
    }

    public function edit(UpdateDealsRequest $request)
    {
        $deleted = $this->deal_service->edit($request);
        if (!$deleted)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Deal did not deleted!", $deleted));
        return ($this->global_api_response->success(1, "Deal deleted successfully!", $deleted));
    }
}
