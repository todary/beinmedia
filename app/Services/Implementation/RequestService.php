<?php

namespace App\Services\Implementation;

use App\Adapters\IRequestAdapter;
use App\Helpers\MessageHandleHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\traits\imagesTrait;
use App\Jobs\send_email;
use App\Jobs\upload_file;
use App\Services\IRequestService;
use App\Transformers\RequestTransformer;
use Illuminate\Support\Facades\Auth;
use Lang;

class RequestService implements IRequestService
{
    use imagesTrait;

    protected $adapter;
    protected $transformer;

    public function __construct(IRequestAdapter $adapter, MessageHandleHelper $messageHandle, RequestTransformer $transformer = null)
    {
        $this->adapter = $adapter;
        $this->messageHandler = $messageHandle;
        $this->transformer = $transformer;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile($request)
    {
        $upload_img = $this->cms_upload($request,"1",'file_json','/files',0,0,0);

        if(count($upload_img)){
            $upload_img = $upload_img[0];
        }


       dispatch(new upload_file(
           url($upload_img)
        ));

        $msg = Lang::get("file.upload_successfully");

        return $this->messageHandler->postJsonSuccessResponse($msg, []);

    }


    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNames($request)
    {


        $names = $this->adapter->getNames($request);

        $nameData = $this->transformer->transform($names);

        return $this->messageHandler->getJsonSuccessResponse("", $nameData);


    }

}
