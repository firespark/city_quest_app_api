<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\City;
use App\Models\Game;
use App\Models\Quest;
use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class QuestsController extends ApiController
{

    public function index($city_id, Request $request)
    {
        $user = null;
        $purchased_ids = [];

        if ($request) {
            $user_id = User::autoriseUserByToken($request);
            $user = User::with('purchasedQuests')->find($user_id);
            if ($user) {
                $purchased_ids = $user->purchasedQuests->pluck('id')->toArray();
            }
        }

        $quests = Quest::select('id', 'title', 'image', 'paid')
            ->where('city_id', $city_id)
            ->when(!$user || $user->role != 1, function ($query) {
                return $query->where('published', 1);
            })
            ->orderBy('order_number', 'asc')
            ->withCount('sights')
            ->get();

        $game_data = $user ? Game::select('quest_id', 'finished')->where('user_id', $user->id)->get() : collect();

        $data = [];
        foreach ($quests as $quest) {
            $status = null;
            foreach ($game_data as $g) {
                if ($g->quest_id == $quest->id) {
                    $status = ($g->finished) ? 'finished' : 'in_progress';
                }
            }

            $available = !$quest->paid || in_array($quest->id, $purchased_ids);

            $data[] = [
                'id' => $quest->id,
                'title' => $quest->title,
                'image' => $quest->getImage(),
                'sights_count' => $quest->sights_count,
                'status' => $status,
                'paid' => (bool) $quest->paid,
                'available' => $available,
            ];
        }

        $this->response->setData($data);
        $this->response->toggleSuccess();

        return $this->response->responseData();
    }

    public function featured(Request $request)
    {
        $user = null;
        $purchased_ids = [];
        $country_id = $this->getCountryId($request);

        if ($request->header('Authorization')) {
            $user_id = User::autoriseUserByToken($request);
            $user = User::with('purchasedQuests')->find($user_id);
            if ($user) {
                $purchased_ids = $user->purchasedQuests->pluck('id')->toArray();
            }
        }

        $quests = Quest::select('quests.id', 'quests.title', 'quests.image', 'quests.paid')
            ->join('cities', 'quests.city_id', '=', 'cities.id')
            ->where('quests.featured', 1)
            ->where('cities.country_id', $country_id)
            ->when(!$user || $user->role != 1, function ($query) {
                return $query->where('quests.published', 1);
            })
            ->orderBy('quests.order_number', 'asc')
            ->withCount('sights')
            ->get();

        $game_data = $user ? Game::select('quest_id', 'finished')->where('user_id', $user->id)->get() : collect();

        $data = [];
        foreach ($quests as $quest) {
            $status = null;
            foreach ($game_data as $g) {
                if ($g->quest_id == $quest->id) {
                    $status = ($g->finished) ? 'finished' : 'in_progress';
                }
            }

            $available = !$quest->paid || in_array($quest->id, $purchased_ids);

            $data[] = [
                'id' => $quest->id,
                'title' => $quest->title,
                'image' => $quest->getImage(),
                'sights_count' => $quest->sights_count,
                'status' => $status,
                'paid' => (bool) $quest->paid,
                'available' => $available,
            ];
        }

        $this->response->setData($data);
        $this->response->toggleSuccess();

        return $this->response->responseData();
    }

    public function get($id, Request $request)
    {
        $user = null;
        if ($request) {
            $user_id = User::autoriseUserByToken($request);
            $user = User::find($user_id);
        }

        $quest = Quest::select('id', 'title', 'image', 'content', 'city_id', 'paid', 'start_point', 'end_point')
            ->where('id', $id)
            ->when(!$user || $user->role != 1, function ($query) {
                return $query->where('published', 1);
            })
            ->withCount('sights')
            ->first();

        if ($quest) {
            $status = null;
            $available = !$quest->paid;

            if ($user) {
                $game = Game::where('user_id', $user->id)->where('quest_id', $quest->id)->first();
                if ($game)
                    $status = ($game->finished) ? 'finished' : 'in_progress';

                if ($quest->paid) {
                    $available = $user->purchasedQuests()->where('quest_id', $quest->id)->exists();
                }
            }

            $data = [
                'id' => $quest->id,
                'title' => $quest->title,
                'image' => $quest->getImage(),
                'content' => $quest->getPs(),
                'city_id' => $quest->city_id,
                'city' => $quest->city->title ?? '',
                'start_point' => $quest->start_point,
                'end_point' => $quest->end_point,
                'paid' => (bool) $quest->paid,
                'available' => $available,
                'sights_count' => $quest->sights_count,
                'status' => $status,
                'is_auth' => ($user && $user->email) ? true : false,
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
        $data = [];

        if ($user_id) {
            $games = Game::select('quest_id', 'step', 'mode_id')
                ->where('user_id', $user_id)
                ->where('finished', 1)
                ->get();

            foreach ($games as $game) {
                $quest = Quest::with('city.country')
                    ->select('id', 'title', 'image', 'city_id', 'paid')
                    ->where('id', $game->quest_id)
                    ->first();

                if ($quest) {
                    $questData = $quest->getData($game->step, $game->mode_id);
                    $questData['paid'] = (bool) $quest->paid;
                    $questData['available'] = true;
                    $questData['country_title'] = $quest->city->country->title ?? 'Другие';
                    $data[] = $questData;
                }
            }
            $this->response->toggleSuccess();
        } else {
            $this->response->setStatus(401);
        }

        $this->response->setData($data);
        return $this->response->responseData();
    }

    public function opened(Request $request)
    {
        $user_id = User::autoriseUserByToken($request);
        $data = [];

        if ($user_id) {
            $games = Game::select('quest_id', 'step', 'mode_id')
                ->where('user_id', $user_id)
                ->where('finished', 0)
                ->get();

            foreach ($games as $game) {
                $quest = Quest::with('city.country')
                    ->select('id', 'title', 'image', 'city_id', 'paid')
                    ->where('id', $game->quest_id)
                    ->first();

                if ($quest) {
                    $questData = $quest->getData($game->step, $game->mode_id);
                    $questData['paid'] = (bool) $quest->paid;
                    $questData['available'] = true;
                    $questData['country_title'] = $quest->city->country->title ?? 'Другие';
                    $data[] = $questData;
                }
            }
            $this->response->toggleSuccess();
        } else {
            $this->response->setStatus(401);
        }

        $this->response->setData($data);
        return $this->response->responseData();
    }

    public function purchased(Request $request)
    {
        $user_id = User::autoriseUserByToken($request);
        $data = [];

        if ($user_id) {
            $user = User::find($user_id);
            if ($user) {
                $quests = $user->purchasedQuests()
                    ->with('city')
                    ->select('quests.id', 'quests.title', 'quests.image', 'quests.city_id')
                    ->get();

                foreach ($quests as $quest) {
                    $data[] = [
                        'id' => $quest->id,
                        'title' => $quest->title,
                        'image' => $quest->getImage(),
                        'city' => $quest->city->title ?? '',
                    ];
                }
            }
            $this->response->toggleSuccess();
        } else {
            $this->response->setStatus(401);
        }

        $this->response->setData($data);
        return $this->response->responseData();
    }

    public function buy(Request $request)
    {
        $user_id = User::autoriseUserByToken($request);
        $quest_id = $request->get('quest_id');
        $mock_token = $request->get('mock_google_token');

        if (!$mock_token) {
            return response()->json(['success' => 0, 'error' => 'Ошибка Google Play']);
        }

        $response = \Http::post('https://test2.gagara-web.ru/api/google-emulator/verify', [
            'token' => $mock_token
        ]);

        if ($response->successful()) {
            $user = User::find($user_id);
            $user->purchasedQuests()->syncWithoutDetaching([$quest_id]);

            return response()->json(['success' => 1]);
        }

        return response()->json(['success' => 0, 'error' => 'Некорректный токен']);
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
