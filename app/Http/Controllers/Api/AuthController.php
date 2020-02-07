<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\api_controller;
use App\Http\Controllers\Controller;
use App\Services\IAuthService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lang;

class AuthController extends api_controller
{
    /**
     * @var IAuthService
     */
    protected $service;
    protected $validator;

    /**
     * AuthController constructor.
     * @param IAuthService $service
     * @param ValidationHelper $validator
     */
    public function __construct(IAuthService $service, ValidationHelper $validator) {

        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        {
            ob_start("ob_gzhandler");
        }
        $this->service = $service;
        $this->validator = $validator;

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){


        $data = $request->all();

        $messages = [
//            'mobile_number.required' => Lang::get("auth.require_mobile_number"),
//            'mobile_number.unique' => Lang::get("auth.unique_mobile_number"),
//            'mobile_number.numeric' => Lang::get("auth.numeric_mobile_number"),
            'email.required' => Lang::get("auth.require_email"),
            'email.unique' => Lang::get("auth.unique_email"),
            'email.email' => Lang::get("auth.email_email"),
            'password.required' => Lang::get("auth.require_password"),
            'username.required' => Lang::get("auth.require_username"),
        ];

//        $data["mobile_number"]  = ltrim($data["mobile_number"], '0');

        $validator = $this->validator->getValidationErrorsWithRequest(
            $data,
            [
//                'mobile_number' => 'required|unique:users|numeric',
                'email' => 'required|email|max:255|unique:users,email,NULL,user_id,deleted_at,NULL',
                'password' => 'required',
                'username' => 'required',
            ],
            $messages );

        if ($validator !== true)
            return $this->getJsonValidationErrorResponse("", $validator);

        return $this->service->register($data);

    }



    public function login(Request $request)
    {


        $data = $request->all();

        $messages = [
            'email.required' => Lang::get("auth.require_mobile_number"),
            'password.required' => Lang::get("auth.require_password"),
        ];


        $validator = $this->validator->getValidationErrorsWithRequest(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            $messages);

        if ($validator !== true)
            return $this->getJsonValidationErrorResponse("", $validator);


        return $this->service->login($data);

    }

    public function loginSocial(Request $request)
    {
        $data = $request->all();
        $messages = [
            'social_id.required' => Lang::get("auth.require_social_id"),
            'social_provider.required' => Lang::get("auth.require_social_provider"),
        ];
        $validator = $this->validator->getValidationErrorsWithRequest(
            $request->all(),
            [
                'social_id' => 'required',
                'social_provider' => 'required',
            ],
            $messages);

        if ($validator !== true)
            return $this->getJsonValidationErrorResponse("", $validator);


        return $this->service->loginSocial($data);

    }


    public function verification(Request $request)
    {

        $data = $request->all();

        $validator = $this->validator->getValidationErrorsWithRequest(
            $request->all(),
            [
                'email' => 'required|email|max:255',
                'number_verify' => 'required|numeric',
            ]);

        if ($validator !== true)
            return $this->getJsonValidationErrorResponse("", $validator);

        return $this->service->verification($data);

    }


    public function resendCode($field, $user_type='user')
    {
        return $this->service->resendCode($field, $user_type);

    }



    public function updatePushToken(Request $request)
    {

        $data = $request->all();
        $user = Auth::user();
        $validator = $this->validator->getValidationErrorsWithRequest(
            $request->all(),
            [

                'push_token' => 'required',
                'device_type' => 'required|in:ios,android',
            ]);

        if ($validator !== true)
            return $this->getJsonValidationErrorResponse("", $validator);


        return $this->service->updatePushToken($data,$user);

    }





    public function passwordForget($field)
    {
        return $this->service->passwordForget($field);

    }

    public function logout(Request $request)
    {
        $userTokens = Auth::user()->tokens;
        foreach($userTokens as $token) {

            $token->revoke();
        }

        $json = [
            'Status' => 'success',
            'code' => 200,
            'message' => 'You are Logged out.',
        ];
        return response()->json($json, '200');

    }



}
