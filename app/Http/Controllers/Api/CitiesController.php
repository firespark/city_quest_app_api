<?php

namespace App\Http\Controllers\Api;


use App\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CitiesController extends ApiController
{
    public function index()
    {
        
        $citiesData = [];
        
        $cities = City::select('id', 'title', 'image', 'parent_id')->get();

        if(!empty($cities))
        {
            $data = [];
            foreach ($cities as $city) {
                $data[$city->parent_id][] = [
                    'id' => $city->id,
                    'title' => $city->title,
                    'image' => URL::to('/') . $city->getImage()
                ];
            }


            foreach ($data[0] as $city) {
                $city['children'] = [];

                if(isset($data[$city['id']])) {
                    $city['children'] = $data[$city['id']];
                }
                $citiesData[] = $city;
            }
            $this->response->setData($citiesData);
            $this->response->toggleSuccess();
        }
        

        return $this->response->responseData();
    }

    public function featured()
    {

        $cities = City::select('id', 'title', 'image')
                ->where('featured', 1)->get();

        if(!empty($cities))
        {
            $data = [];
            foreach ($cities as $city) {
                $data[] = [
                    'id' => $city->id,
                    'title' => $city->title,
                    'image' => URL::to('/') . $city->getImage()
                ];
            }
            $this->response->setData($data);
            $this->response->toggleSuccess();
        }
        

        return $this->response->responseData();
    }

    

    public function search(Request $request)
    {
        
        $data = [];

        $str = $request->get('str');

        if($str)
        {

            $cities = City::select('id', 'title', 'image')->where('title', 'like', '%' . $str . '%')->get();


            if($cities)
            {
                $data = [];
                foreach ($cities as $city) {
                    $data[] = [
                        'id' => $city->id,
                        'title' => $city->title,
                        //'image' => URL::to('/') . $city->getImage()
                    ];
                }
                
            }
        }

        $this->response->setData($data);
        $this->response->toggleSuccess();

        return $this->response->responseData();


    }

    
}
