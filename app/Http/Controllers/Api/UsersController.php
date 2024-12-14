<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Models\Mode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UsersController extends ApiController
{
    public function get(Request $request)
    {
        $token = $request->bearerToken();

        if($token)
        {
            $api_token = hash('sha256', $token);
            
            $user = User::select('id', 'name', 'email', 'notes')->where('token', $api_token)->where('active', 1)->first();

            if(!$user) {
                $user = User::add($api_token);

            }


            if($user)
            {
                $data['name'] = $user->name;
                $data['email'] = $user->email;
                $data['notes'] = $user->notes;

                $this->response->setData($data);

                $this->response->toggleSuccess();

            }
            else
            {
                $this->response->setStatus(401);
            }
        }

        else
        {
            $this->response->setStatus(401);
        }

        

        return $this->response->responseData();
    }

    public function saveName(Request $request)
    {
        $token = $request->bearerToken();

        if($token)
        {
            $api_token = hash('sha256', $token);
            
            $user = User::select('id', 'name')->where('token', $api_token)->where('active', 1)->first();


            if($user)
            {
                $user->name = $request->get('name');

                if($user->save())
                {
                    $this->response->toggleSuccess();

                }
                else
                {
                    $this->response->setStatus(500);
                }

            }
            else
            {
                $this->response->setStatus(401);
            }
        }

        else
        {
            $this->response->setStatus(401);
        }

        

        return $this->response->responseData();
    }

    public function saveNotes(Request $request)
    {
        $token = $request->bearerToken();

        if($token)
        {
            $api_token = hash('sha256', $token);
            
            $user = User::select('id', 'notes')->where('token', $api_token)->where('active', 1)->first();


            if($user)
            {
                $user->notes = $request->get('notes');

                if($user->save())
                {
                    $this->response->toggleSuccess();

                }
                else
                {
                    $this->response->setStatus(500);
                }

            }
            else
            {
                $this->response->setStatus(401);
            }
        }

        else
        {
            $this->response->setStatus(401);
        }


        return $this->response->responseData();
    }

    public function savePassword(Request $request)
    {
        $token = $request->bearerToken();

        if($token)
        {
            $api_token = hash('sha256', $token);
            
            $user = User::select('id', 'password')->where('token', $api_token)->where('active', 1)->first();


            if($user)
            {
                $password = $request->get('password');

                $user->password = bcrypt($password);

                if($user->save())
                {
                    $this->response->toggleSuccess();

                }
                else
                {
                    $this->response->setStatus(500);
                }

            }
            else
            {
                $this->response->setStatus(401);
            }
        }

        else
        {
            $this->response->setStatus(401);
        }

        

        return $this->response->responseData();
    }

    public function testDelete()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->delete(); 
        }
    }

    public function getStatus(Request $request)
    {
        $token = $request->bearerToken();
        $status = 1;

        if($token)
        {
            $api_token = hash('sha256', $token);
            
            $user = User::select('id')->where('token', $api_token)->where('active', 1)->first();

            if($user)
            {
                $games = $user->games()->where('finished',1)->get();
               
                if (!empty($games)){
                    $modes_arr = [];
                    foreach ($games as $game) {
                        if (isset($modes_arr[$game->mode_id])){
                            $modes_arr[$game->mode_id] = $modes_arr[$game->mode_id] + 1;
                        }
                        else {
                            $modes_arr[$game->mode_id] = 1;
                        }
                    }
                    if (!empty($modes_arr)){
                        $max_value = max($modes_arr);
                        $keys_with_max_value = array_keys($modes_arr, $max_value);
                        $max_key = max($keys_with_max_value);
                        $status = $max_key;
                    }
                }

                $mode = Mode::select('title')->where('id', $status)->first();
                if ($mode) 
                {
                    $this->response->setData($mode->title);

                    $this->response->toggleSuccess();
                }
                else
                {
                    $this->response->setStatus(401);
                }
            }
            else
            {
                $this->response->setStatus(401);
            }
        }

        else
        {
            $this->response->setStatus(401);
        }

        

        return $this->response->responseData();
    }
    
}
