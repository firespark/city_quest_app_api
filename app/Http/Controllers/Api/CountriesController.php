<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\Request;

class CountriesController extends ApiController
{
    public function index()
    {
        $countries = Country::select('id', 'title', 'slug')
            ->where('published', 1)
            ->orderBy('title', 'asc')
            ->get();

        $this->response->setData($countries);
        $this->response->toggleSuccess();

        return $this->response->responseData();
    }
}