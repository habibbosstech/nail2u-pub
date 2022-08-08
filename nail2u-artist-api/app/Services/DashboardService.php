<?php

namespace App\Services;

use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardService extends BaseService
{
    public function userData()
    {
        try {

            $data = User::all();

            foreach ($data as $user) {
                $user->reviews()->avg('rating');
            }

            $latest_jobs = Auth::user()->jobs()->whereDate('created_at', Carbon::today())->where('status', 'in-process')->take(10)->get()->toArray();

            $jobs_details = [
                'today' => Auth::user()->jobs()->with('BookingService:id,name as service_name,price', 'Client:id,username,phone_no,address,image_url')->whereDate('created_at', Carbon::today())->where('status', 'in-process')->take(10)->get(['id', 'artist_id', 'client_id', 'started_at', 'ended_at', 'status'])->toArray(),
                'requested' => Auth::user()->jobs()->with('BookingService:id,name as service_name,price', 'Client:id,username,phone_no,address,image_url')->where('status', 'new')->orderBy('id', 'desc')->take(10)->get(['id', 'artist_id', 'client_id', 'started_at', 'ended_at', 'status'])->toArray(),
                'job_history' => Auth::user()->jobs()->with('BookingService:id,name as service_name,price', 'Client:id,username,phone_no,address,image_url')->where('status', 'done')->orderBy('id', 'desc')->take(10)->get(['id', 'artist_id', 'client_id', 'started_at', 'ended_at', 'status'])->toArray()
            ];

            $user = [
                'latest_jobs_notification' => $latest_jobs,
                'user_data' => Auth::user()->only(['id','username','experience','total_balance']),
                'rating_reviews' => Auth::user()->reviews()->avg('rating'),
                'jobs_done' => Auth::user()->jobs()->where('status', 'done')->count(),
                'jobs_details' => $jobs_details
            ];
            //comment


            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_CREATED['outcomeCode'], $user);
        } catch (\Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:DashboardService: userData", $error);
            return false;
        }
    }

    public function userJobsDetails()
    {
        try {
            $date1 = Carbon::today()->toDateString();
            $date2 = Carbon::today()->subDays(3)->toDateString();

            $latest_jobs = Auth::user()->jobs()->whereBetween('created_at', [$date2, $date1])->get();

            $user_data = User::find(Auth::id())
                ->first(['id', 'username', 'total_balance', 'experience', 'image_url']);

            $user = [
                'user_data' => $user_data,
                'rating_reviews' => Auth::user()->reviews()->avg('rating'),
                'jobs_done' => Auth::user()->jobs()->where('status', 'done')->count(),
                'latest_jobs' => $latest_jobs
            ];

            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_CREATED['outcomeCode'], $user);
        } catch (\Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:DashboardService: userData", $error);
            return false;
        }
    }

    public function acceptJob($id)
    {
        try {
            $auth_job = Auth::user()->jobs()->where('id', $id)->where('status', 'new')->first();

            if ($auth_job) {
                $auth_job->status = 'accepted';
                $auth_job->save();

                return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_UPDATED['outcomeCode'], $auth_job->toArray());
            }

            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'], []);
        } catch (\Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:DashboardService: userData", $error);
            return false;
        }
    }
}
