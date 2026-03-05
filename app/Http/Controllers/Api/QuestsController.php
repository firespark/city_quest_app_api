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
        $user = null;
        if ($request) {
            $user_id = User::autoriseUserByToken($request);
            $user = User::find($user_id);
        }

        $quests = Quest::select('id', 'title', 'image', 'paid')
            ->where('city_id', $city_id)
            ->when(!$user || $user->role != 1, function ($query) {
                return $query->where('published', 1);
            })
            ->withCount('sights')
            ->get();

        $quest_ids = [];
        if ($user) {
            $quest_ids = Game::select('quest_id', 'finished')->where('user_id', $user->id)->get();
        }

        if ($quests->isNotEmpty()) {
            $data = [];
            foreach ($quests as $quest) {
                $status = null;
                foreach ($quest_ids as $q_id) {
                    if ($q_id->quest_id == $quest->id) {
                        $status = ($q_id->finished) ? 'finished' : 'in_progress';
                    }
                }

                $data[] = [
                    'id' => $quest->id,
                    'title' => $quest->title,
                    'image' => $quest->getImage(),
                    'sights_count' => $quest->sights_count,
                    'status' => $status,
                    'paid' => (bool) $quest->paid,
                ];
            }

            $this->response->setData($data);
            $this->response->toggleSuccess();
        }

        return $this->response->responseData();
    }

    public function featured(Request $request)
    {
        $user = null;
        if ($request) {
            $user_id = User::autoriseUserByToken($request);
            $user = User::find($user_id);
        }

        $quests = Quest::select('id', 'title', 'image', 'paid')
            ->where('featured', 1)
            ->when(!$user || $user->role != 1, function ($query) {
                return $query->where('published', 1);
            })
            ->withCount('sights')
            ->get();

        $quest_ids = [];
        if ($user) {
            $quest_ids = Game::select('quest_id', 'finished')->where('user_id', $user->id)->get();
        }

        if ($quests->isNotEmpty()) {
            $data = [];
            foreach ($quests as $quest) {
                $status = null;
                foreach ($quest_ids as $q_id) {
                    if ($q_id->quest_id == $quest->id) {
                        $status = ($q_id->finished) ? 'finished' : 'in_progress';
                    }
                }
                $data[] = [
                    'id' => $quest->id,
                    'title' => $quest->title,
                    'image' => $quest->getImage(),
                    'sights_count' => $quest->sights_count,
                    'status' => $status,
                    'paid' => (bool) $quest->paid,
                ];
            }

            $this->response->setData($data);
            $this->response->toggleSuccess();
        }

        return $this->response->responseData();
    }

    public function get($id, Request $request)
    {
        $user = null;
        if ($request) {
            $user_id = User::autoriseUserByToken($request);
            $user = User::find($user_id);
        }

        $quest = Quest::select('id', 'title', 'image', 'content', 'city_id', 'start_point', 'end_point')
            ->where('id', $id)
            ->when(!$user || $user->role != 1, function ($query) {
                return $query->where('published', 1);
            })
            ->withCount('sights')
            ->first();

        if (!empty($quest)) {
            $status = null;
            if ($user) {
                $game = Game::where('user_id', $user->id)
                    ->where('quest_id', $quest->id)
                    ->first();
                if ($game) {
                    $status = ($game->finished) ? 'finished' : 'in_progress';
                }
            }

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
                'status' => $status,
            ];

            $this->response->setData($data);
            $this->response->toggleSuccess();
        } else {
            $this->response->setStatus(404);
        }

        return $this->response->responseData();
    }


    public function done(Request $request)
    {

        $user_id = User::autoriseUserByToken($request);

        if ($user_id) {
            $games = Game::select('quest_id', 'step', 'mode_id')->where('user_id', $user_id)->where('finished', 1)->get();

            if (!empty($games)) {

                $data = [];

                foreach ($games as $game) {
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

        } else {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();
    }

    public function opened(Request $request)
    {

        $user_id = User::autoriseUserByToken($request);

        if ($user_id) {
            $games = Game::select('quest_id', 'step', 'mode_id')->where('user_id', $user_id)->where('finished', 0)->get();

            if (!empty($games)) {

                $data = [];

                foreach ($games as $game) {
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

        } else {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();
    }

}
