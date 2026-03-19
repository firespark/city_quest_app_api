<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CitiesController extends ApiController
{
    public function index(Request $request)
    {
        $citiesData = [];
        $country_id = $this->getCountryId($request);

        $cities = City::select('id', 'title', 'image', 'parent_id')
            ->where('published', 1)
            ->where('country_id', $country_id)
            ->get();

        $data = [];
        foreach ($cities as $city) {
            $data[$city->parent_id][] = [
                'id' => $city->id,
                'title' => $city->title,
                'image' => URL::to('/') . $city->getImage()
            ];
        }

        if (isset($data[0])) {
            foreach ($data[0] as $city) {
                $city['children'] = $data[$city['id']] ?? [];
                $citiesData[] = $city;
            }
        }

        $this->response->setData($citiesData);
        $this->response->toggleSuccess();

        return $this->response->responseData();
    }

    public function featured(Request $request)
    {
        $country_id = $this->getCountryId($request);

        $cities = City::select('id', 'title', 'image')
            ->where('published', 1)
            ->where('featured', 1)
            ->where('country_id', $country_id)
            ->get();

        $data = $cities->map(function ($city) {
            return [
                'id' => $city->id,
                'title' => $city->title,
                'image' => URL::to('/') . $city->getImage()
            ];
        });

        $this->response->setData($data);
        $this->response->toggleSuccess();

        return $this->response->responseData();
    }

    public function search(Request $request)
    {
        $data = [];
        $str = $request->get('str');
        $country_id = $this->getCountryId($request);

        if ($str) {
            $cities = City::select('id', 'title', 'image')
                ->where('published', 1)
                ->where('title', 'like', '%' . $str . '%')
                ->where('country_id', $country_id)
                ->get();

            foreach ($cities as $city) {
                $data[] = [
                    'id' => $city->id,
                    'title' => $city->title,
                ];
            }
        }

        $this->response->setData($data);
        $this->response->toggleSuccess();

        return $this->response->responseData();
    }

    private function getCountryId(Request $request)
    {
        $country_id = $request->get('country_id');

        if (!$country_id) {
            $defaultCountry = Country::where('published', 1)->first();
            $country_id = $defaultCountry ? $defaultCountry->id : 0;
        }

        return $country_id;
    }
}