<?php

namespace App\Http\Controllers\Api;

use App\Models\Gameitem;
use App\Models\User;
use App\Models\Quest;
use App\Models\Sight;
use App\Models\Game;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class GamesController extends ApiController
{
    

    public function get(Request $request, $quest_id)
    {
        
        $user_id = User::autoriseUserByToken($request);


        if($user_id) 
        {
            $data = Game::getData($quest_id, $user_id);

            $this->response->setData($data);
            $this->response->toggleSuccess();
        }

        else
        {
            $this->response->setStatus(401);
        }

        

        return $this->response->responseData();

    }



    public function next(Request $request, $quest_id)
    {
        
        $user_id = User::autoriseUserByToken($request);

        if($user_id) 
        {
            $game = Game::where('user_id', $user_id)
                        ->where('quest_id', $quest_id)
                        ->first();
            if(!empty($game))
            {

                if($game->status == 2) {
                    
                    

                    if($game->is_finish($quest_id, $game->step, $game->status))
                    {
                        $game->finished = 1;
                    }

                    $game->updateStatus();

                    $data = $game->getData($quest_id, $user_id);
                    
                    $this->response->setData($data);  
                    $this->response->toggleSuccess();
                }
                else {
                    $this->response->setStatus(403);
                }
            }
            else
            {
                $this->response->setStatus(404);
            }
        }

        else
        {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();

    }

    public function checkAnswer(Request $request, $quest_id)
    {
        $quest_answer = $request->get('quest_answer');

        if($quest_answer) 
        {
            $user_id = User::autoriseUserByToken($request);

            if($user_id) 
            {

                $game = Game::where('user_id', $user_id)
                        ->where('quest_id', $quest_id)
                        ->first();

                if(!empty($game))
                {


                    $sight = Sight::where('quest_id', $quest_id)->where('step', $game->step)->first();

                    if(!empty($sight))
                    {

                        $answerData = $sight->checkAnswer($quest_answer, $request->get('answer_number'));
                        
                        if(!$answerData['errors']) {
                            $game->updateStatus();
                            $game->hint = 0;
                        }
                                                    
                        $data = $game->getData($quest_id, $user_id);
                        $data['inputResults'] = $answerData['inputResults'];
                        $data['errors'] = $answerData['errors'];
                        
                        $this->response->setData($data);  

                        $this->response->toggleSuccess();


                    }
                    else
                    {
                        $this->response->setStatus(404);
                        $this->response->setError('Достопримечательность не найдена');
                    }
                }
                else
                {
                    $this->response->setStatus(404);
                }
            }
            else
            {
                $this->response->setStatus(401);
            }
        }
        else
        {
            $this->response->setError('Неправильный ответ');
        }

        return $this->response->responseData();

        
    }

    public function getHint(Request $request, $quest_id){
        $user_id = User::autoriseUserByToken($request);

        if($user_id) 
        {

            $game = Game::where('user_id', $user_id)->where('quest_id', $quest_id)->first();

            if(!empty($game))
            {

                $sight = Sight::select('id')->where('quest_id', $quest_id)->where('step', $game->step)->first();

                if(!empty($sight))
                {
                    $gameItem = Gameitem::where('game_id', $game->id)->where('step', $game->step)->first();

                    if (!$gameItem)
                    {
                        $gameItem = new Gameitem;

                        $gameItem->game_id = $game->id;
                        $gameItem->step = $game->step;
                    }

                
                    if ( 
                        ($gameItem->hint1 && ($game->status == 0)) ||
                        ($gameItem->hint2 && ($game->status == 1))
                    )
                    {
                        $this->response->setError('Подсказка уже использована.');
                    }
                    elseif ($game->hints_number < 1) {
                        $this->response->setError('Подсказок больше нет.');
                    }
                    else
                    {
                        switch ($game->status) {
                            case 0:
                                $gameItem->hint1 = 1;
                                break;

                            case 1:
                                $gameItem->hint2 = 1;
                                break;
                        }

                        $game->hints_number--;


                        if($game->save() && $gameItem->save())
                        {
                            $data = $game->getData($quest_id, $user_id);
                    
                            $this->response->setData($data);  
                            $this->response->toggleSuccess();

                        }
                        else
                        {
                            $this->response->setStatus(500);
                        }
                    }
                }
                else
                {
                    $this->response->setStatus(404);
                    $this->response->setError('Достопримечательность не найдена');
                }
            }
            else
            {
                $this->response->setStatus(404);
            }
            
        }

        else
        {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();
    }


    public function getSkip(Request $request, $quest_id){
        $user_id = User::autoriseUserByToken($request);

        if($user_id) 
        {

            $game = Game::where('user_id', $user_id)->where('quest_id', $quest_id)->first();

            if(!empty($game))
            {

                
                    $gameItem = Gameitem::where('game_id', $game->id)->where('step', $game->step)->first();

                    if (!$gameItem)
                    {
                        $gameItem = new Gameitem;

                        $gameItem->game_id = $game->id;
                        $gameItem->step = $game->step;
                    }

                
                    if ( 
                        ($gameItem->skip1 && ($game->status == 0)) ||
                        ($gameItem->skip2 && ($game->status == 1))
                    )
                    {
                        $this->response->setError('Пропуск уже использован.');
                    }
                    elseif ($game->skips_number < 1) {
                        $this->response->setError('Пропусков больше нет.');
                    }
                    else
                    {
                        switch ($game->status) {
                            case 0:
                                $gameItem->skip1 = $request->get('reason_id');
                                $gameItem->skip_comment_1 = $request->get('comment');
                                $game->status = 1;
                                break;

                            case 1:
                                $gameItem->skip2 = $request->get('reason_id');
                                $gameItem->skip_comment_2 = $request->get('comment');
                                $game->status = 2;
                                break;
                        }

                        $game->skips_number--;


                        if($game->save() && $gameItem->save())
                        {
                            $data = $game->getData($quest_id, $user_id);
                    
                            $this->response->setData($data);  
                            $this->response->toggleSuccess();

                        }
                        else
                        {
                            $this->response->setStatus(500);
                        }
                    }
                
            }
            else
            {
                $this->response->setStatus(404);
            }
            
        }

        else
        {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();
    }


    public function setMode(Request $request, $quest_id)
    {

        $user_id = User::autoriseUserByToken($request);

        if($user_id) 
        {

            $quest = Quest::select('skips_number', 'hints_number')->where('id', $quest_id)->first();

            if(!empty($quest))
            {
                $game = Game::select('step', 'user_id')
                    ->where('user_id', $user_id)
                    ->where('quest_id', $quest_id)
                    ->first();

                if (!isset($game->step))
                {
                    
                    $game = new Game;

                    $game->user_id = $user_id;
                    $game->quest_id = $quest_id;
                    $game->step = 1;
                    $game->skips_number = $quest->skips_number;
                    $game->hints_number = $quest->hints_number;

                    
                }

                $game->mode_id = $request->get('mode_id');

                if($game->save())
                {
                    $data = $game->getData($quest_id, $user_id);
                    
                    $this->response->setData($data);  
                    $this->response->toggleSuccess();
                }
                else 
                {
                    $this->response->setStatus(500);
                }
            }
            else
            {
                $this->response->setStatus(404);
            }


        }

        else
        {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();
    }

    public function getLevel(Request $request, $quest_id, $level)
    {
        
        $user_id = User::autoriseUserByToken($request);

        if($user_id) 
        {
            $data = Game::getData($quest_id, $user_id, $level);
            
            $this->response->setData($data);
            $this->response->toggleSuccess();
        }

        else
        {
            $this->response->setStatus(401);
        }


        return $this->response->responseData();

    }

    

    
    
}
