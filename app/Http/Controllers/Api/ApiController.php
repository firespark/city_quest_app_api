<?php

namespace App\Http\Controllers\Api;

use App\Models\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ApiController extends Controller
{
    public $response;

    public function __construct()
    {
        $this->response = new Response;
    }

    
}
