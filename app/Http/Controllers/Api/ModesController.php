<?php

namespace App\Http\Controllers\Api;


use App\Models\Mode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ModesController extends ApiController
{
    public function index()
    {
        $modes = Mode::select('id', 'title', 'description')->get();

        if(!empty($modes))
        {
            $this->response->setData($modes);
            $this->response->toggleSuccess();
        }

        return $this->response->responseData();
    }

    
}
