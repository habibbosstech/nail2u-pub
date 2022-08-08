<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests\ForgotPasswordRequest;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Http\Requests\AuthRequests\ResetPasswordRequest;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\SocialIdentity;
use App\Services\AuthService;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function __construct(AuthService $AuthService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->auth_service = $AuthService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function register(RegisterRequest $request)
    {
        $register = $this->auth_service->register($request);
        if (!$register)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "User did not registered!", $register));
        return ($this->global_api_response->success(1, "Artist registered successfully!", $register));
    }

    /**
     * @param LoginRequest $request
     * @return GlobalApiResponse
     */
    public function login(LoginRequest $request)
    {
        $login = $this->auth_service->login($request);
        if ($login['outcomeCode'] === GlobalApiResponseCodeBook::INVALID_CREDENTIALS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INVALID_CREDENTIALS, "Your email or password is invalid!", 'Your email or password is invalid!'));
        if ($login['outcomeCode'] === GlobalApiResponseCodeBook::EMAIL_NOT_VERIFIED['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::EMAIL_NOT_VERIFIED, "Your email is not verified!", $login['record']));
        if (!$login)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Login not successful!", $login['record']));
        return ($this->global_api_response->success(1, "Login successfully!", $login['record']));
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $forgot_password = $this->auth_service->forgotPassword($request);

        if (!$forgot_password)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Email for sending resetting password did not sent!", $forgot_password));

        return ($this->global_api_response->success(1, "Email for sending resetting password sent successfully!", $forgot_password));
    }

    public function resetPassword(ResetPasswordRequest $request, string $token, string $email)
    {
        $reset_password = $this->auth_service->resetPassword($request, $token, $email);

        if ($reset_password == GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']) {
            return $this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS, "Record not found for resetting password!", $reset_password);
        }

        if (!$reset_password) {
            return $this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Password didn't reset!", $reset_password);
        }

        return $this->global_api_response->success(1, "Password has been reset successfully!", $reset_password);
    }

    public function logout()
    {
        $logout = $this->auth_service->logout();

        if ($logout === GlobalApiResponseCodeBook::NOT_AUTHORIZED['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::NOT_AUTHORIZED, "User is not authorized to logout!", $logout));

        if (!$logout)
            return (new GlobalApiResponse())->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Logout not successful!", $logout);

        return (new GlobalApiResponse())->success(1, "User logout successfully!", $logout);
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }


    public function findOrCreateUser($providerUser, $provider)
    {
        $account = SocialIdentity::whereProviderName($provider)
            ->whereProviderId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {
            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                ]);
            }

            $user->identities()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            return $user;
        }
    }
}
