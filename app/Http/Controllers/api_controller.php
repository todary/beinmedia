<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class api_controller extends Controller
{


    const DEFAULT_LIMIT_PER_PAGE = 15;


    public function __construct()
    {
        dump('sdfsdf');
        die();
    }


    public function getErrorResponse($msg,$data)
    {
        $response=[
            'Status' => $this->api_error,
            'Message' => $msg];
        if($data)
        {
            $response['Errors']=$data;
        }
        return $response;
    }
    public function getJsonSuccessResponse($msg = "", $data = []) {
        return response()->json([
            'Status' => $this->api_success,
            'Message' => $msg,
            'Data' => $data], Response::HTTP_OK);
    }

    public function getJsonNotAuthorizedResponse($msg = "", $data = []) {
        $response=$this->getErrorResponse($msg,$data);
        return response()->json($response, Response::HTTP_FORBIDDEN);
    }

    public function postJsonSuccessResponse($msg = "", $data = []) {
        return response()->json([
            'Status' => $this->api_success,
            'Message' => $msg,
            'Data' => $data], Response::HTTP_CREATED);
    }

    public function getJsonLogicalErrorResponse($msg = "", $data = []) {
        $response=$this->getErrorResponse($msg,$data);
        return response()->json($response, Response::HTTP_CONFLICT);
    }

    public function getJsonNotFoundErrorResponse($msg = "", $data = []) {
        $response=$this->getErrorResponse($msg,$data);
        return response()->json($response, Response::HTTP_NOT_FOUND);
    }

    public function getJsonSessionExpiredErrorResponse($msg = "", $data = []) {
        $response=$this->getErrorResponse($msg,$data);
        return response()->json($response, Response::HTTP_UNAUTHORIZED);
    }

    public function getJsonValidationErrorResponse($msg = "Validation Errors", $data = []) {
        $response=$this->getErrorResponse($msg,$data);
        return response()->json($response, Response::HTTP_OK);
    }

    public function getStringLength($str) {
        return strlen($str);
    }

    public function checkValidationRules($request, $rules) {
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->getJsonValidationErrorResponse($validator->errors()->first());
        }
        return '0';
    }

}
