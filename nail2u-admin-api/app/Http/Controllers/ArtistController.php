<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequests\AddRequest;
use App\Http\Requests\ArtistRequests\DeleteRequest;
use App\Http\Requests\ArtistRequests\ListAllRequest;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Libs\Response\GlobalApiResponse;
use App\Services\ArtistService;

class ArtistController extends Controller
{
    public function __construct(ArtistService $ArtistService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->artist_service = $ArtistService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function add(AddRequest $request)
    {
        $email_sent = $this->artist_service->add($request);
        if (!$email_sent)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Email did not send to the address!", $email_sent));
        return ($this->global_api_response->success(1, "Email sent successfully!", $email_sent));
    }

    public function listAll(ListAllRequest $request)
    {
        $artists = $this->artist_service->listAll($request);
        if ($artists === GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS, "Artists not found!", []));
        if (!$artists)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Artists did not fetched!", $artists));
        return ($this->global_api_response->success(count($artists), "Artists fetched successfully!", $artists));
    }

    public function delete(DeleteRequest $request)
    {
        $deleted = $this->artist_service->delete($request);
        if (!$deleted)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Artist did not deleted!", $deleted));
        return ($this->global_api_response->success(1, "Artist deleted successfully!", $deleted));
    }
}