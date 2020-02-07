<?php



namespace App\Http\Controllers\Api;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use App\Helpers\ValidationHelper;
use App\Http\Controllers\api_controller;
use App\Http\Controllers\Controller;
use App\Services\IRequestService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class RequestController extends api_controller
{

    protected $service;
    protected $validator;


    /**
     * RequestController constructor.
     * @param IRequestService $service
     * @param ValidationHelper $validator
     */

    public function __construct(IRequestService $service, ValidationHelper $validator) {



        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        {
            ob_start("ob_gzhandler");
        }
        $this->service = $service;
        $this->validator = $validator;

    }


    public function uploadFile(Request $request)
    {



        $messages = [
            'file_json.required' => Lang::get("file.require")
        ];

        $validator = $this->validator->getValidationErrorsWithRequest(
            $request->all(),
            [
                'file_json' => 'required',
            ],
            $messages
        );

        if ($validator !== true)
            return $this->getJsonValidationErrorResponse("", $validator);

        return $this->service->uploadFile($request);

    }

    public function getNames(Request $request)
    {

        return $this->service->getNames($request);

    }


}
