<?php

namespace App\Helpers;

use Carbon\Carbon;
//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\MessageHandleHelper;
//use Validator;

class ValidationHelper {

    public function __construct( MessageHandleHelper $messageHandle) {
        $this->messageHandle = $messageHandle;
    }

    public function getValidationErrors($request, $rules) {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $messages = $validator->errors();
            $errors = [];
            foreach ($messages->jsonSerialize() as $field => $msg) {
                $errors[] = [
                    "field" => $field,
                    "errorCode" => "Validation",
                    "errorMsg" => $msg[0]
                ];
            }
            return $errors;
        }

        return true;
    }



    public function getValidationErrorsWithRequest($request, $rules,$messages=[]) {
        $validator = Validator::make($request, $rules,$messages);
        if ($validator->fails()) {

            $messages = $validator->errors();
            $errors = [];
            foreach ($messages->jsonSerialize() as $field => $msg) {
                $errors[] = [
                    "field" => $field,
                    "errorCode" => "Validation",
                    "errorMsg" => $msg[0]
                ];
            }
            return $errors;
        }

        return true;
    }

}
