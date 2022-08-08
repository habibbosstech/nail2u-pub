<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserService extends BaseService
{
    public function getProfileDetails()
    {
        try {
            $expert_in_id = DB::table('booking_services')
                ->select('service_id', DB::raw('count(*) as total'))
                ->whereIn('booking_id', Auth::user()->jobs()->pluck('id'))
                ->groupBy('service_id')
                ->pluck('total')->toArray();

            $expert_in = Auth::user()->services()->where('id', max($expert_in_id))->pluck('name');

            return [
                'username' => Auth::user()->id,
                'absolute_image_url' => url(Auth::user()->image_url),
                'phone_no' => Auth::user()->phone_no,
                'email' => Auth::user()->email,
                'address' => Auth::user()->address,
                'rating' => Rating::where('artist_id', Auth::id())->avg('rating'),
                'expert' => (isset($expert_in[0])) ? $expert_in[0] : 'Nail paint'
            ];
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:UserService: getProfileDetails", $error);
            return false;
        }
    }

    public function editProfile($request)
    {
        try {
            DB::begintransaction();

            $user = User::find(Auth::id());

            if (isset($request->username)) {
                $user->username = $request->username;
            }

            if (isset($request->phone_no)) {
                $user->phone_no = $request->phone_no;
            }

            if (isset($request->email)) {
                $user->email = $request->email;
            }

            if (isset($request->password)) {
                $user->password = Hash::make($request->password);
            }

            if (isset($request->address)) {
                $user->address = $request->address;
            }

            if (isset($request->image_url)) {
                $path = Helper::storeImageUrl($request, $user);
                $user->image_url = $path;
            }

            $user->save();
            DB::commit();
            return $user;
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:UserService: editProfile", $error);
            return false;
        }
    }
}
