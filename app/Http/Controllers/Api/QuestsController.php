<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\City;
use App\Models\Game;
use App\Models\Quest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class QuestsController extends ApiController
{
    

    public function index($city_id, Request $request)
    {
        $quest_ids = [];
        $quests = Quest::select('id', 'title', 'image')
                ->where('city_id', $city_id)->withCount('sights')->get();

        if ($request){
            $user_id = User::autoriseUserByToken($request);

        }

        if($user_id) 
        {
            $quest_ids = Game::select('quest_id', 'finished')->where('user_id', $user_id)->get();
        }

        if(!empty($quests))
        {
            $data = [];
            foreach ($quests as $quest) {
                $status = null;
                if (!empty($quest_ids))
                {
                    foreach ($quest_ids as $quest_id) {
                        if ($quest_id->quest_id == $quest->id) {
                            $status = ($quest_id->finished) ? 'finished' : 'in_progress';
                        }
                    }
                }
                $data[] = [
                    'id' => $quest->id,
                    'title' => $quest->title,
                    'image' => $quest->getImage(),
                    'sights_count' => $quest->sights_count,
                    'status' => $status,
                    //'city' => $result->city->title,
                ];
            }

            $this->response->setData($data);
            $this->response->toggleSuccess();

        }
        

        return $this->response->responseData();
    }

    public function featured(Request $request)
    {
        $quest_ids = [];
        $quests = Quest::select('id', 'title', 'image')
                ->where('featured', 1)->withCount('sights')->get();
        if ($request){
            $user_id = User::autoriseUserByToken($request);

        }

        if($user_id) 
        {
            $quest_ids = Game::select('quest_id', 'finished')->where('user_id', $user_id)->get();
        }

        if(!empty($quests))
        {
            $data = [];
            foreach ($quests as $quest) {
                $status = null;
                if (!empty($quest_ids))
                {
                    foreach ($quest_ids as $quest_id) {
                        if ($quest_id->quest_id == $quest->id) {
                            $status = ($quest_id->finished) ? 'finished' : 'in_progress';
                        }
                    }
                }
                $data[] = [
                    'id' => $quest->id,
                    'title' => $quest->title,
                    'image' => $quest->getImage(),
                    'sights_count' => $quest->sights_count,
                    'status' => $status,
                    //'city' => $quest->city->title,
                ];
            }

            $this->response->setData($data);
            $this->response->toggleSuccess();

        }
        

        return $this->response->responseData();
    }

    public function get($id)
    {

        $quest = Quest::select('id', 'title', 'image', 'content', 'city_id', 'start_point', 'end_point')
            ->where('id', $id)->withCount('sights')->first();

        if(!empty($quest))
        {

            $data = [
                'id' => $quest->id,
                'title' => $quest->title,
                'image' => $quest->getImage(), 
                'content' => $quest->getPs(), 
                'city_id' => $quest->city_id,
                'city' => $quest->city->title,
                'start_point' => $quest->start_point, 
                'end_point' => $quest->end_point,
                'sights_count' => $quest->sights_count, 
            ];

            $this->response->setData($data);
            $this->response->toggleSuccess();

        }
        else
        {
            $this->response->setStatus(404);
        }

        
        
        return $this->response->responseData();


    }

    public function done(Request $request)
    {

        $user_id = User::autoriseUserByToken($request);

        if($user_id) 
        {
            $games = Game::select('quest_id', 'step', 'mode_id')->where('user_id', $user_id)->where('finished', 1)->get();
            
            if(!empty($games))
            {

                $data = [];

                foreach($games as $game) {
                    $quest = Quest::select(
                        'id',
                        'title',
                        'image',
                        'city_id', 
                    )->where('id', $game->quest_id)->first();

                    $data[] = $quest->getData($game->step, $game->mode_id);
                }


                $this->response->setData($data);
                $this->response->toggleSuccess();
            }
            
        }

        else
        {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();
    }

    public function opened(Request $request)
    {

        $user_id = User::autoriseUserByToken($request);

        if($user_id) 
        {
            $games = Game::select('quest_id', 'step', 'mode_id')->where('user_id', $user_id)->where('finished', 0)->get();
            
            if(!empty($games))
            {

                $data = [];

                foreach($games as $game) {
                    $quest = Quest::select(
                        'id',
                        'title',
                        'image',
                        'city_id', 
                    )->where('id', $game->quest_id)->first();

                    $data[] = $quest->getData($game->step, $game->mode_id);
                }


                $this->response->setData($data);
                $this->response->toggleSuccess();
            }
            
        }

        else
        {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();
    }

}
