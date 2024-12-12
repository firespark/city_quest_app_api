<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mode()
    {
        return $this->belongsTo(Mode::class);
    }

    public function gameitems()
    {
        return $this->hasMany(Game::class);
    }

    public function getData($quest_id, $user_id, $level = null)
    {
        $game = static::where('user_id', $user_id)->where('quest_id', $quest_id)->first();

        $quest = Quest::select(
            'title', 
            'city_id', 
            'skips_number', 
            'hints_number',
            'finish'
        )->where('id', $quest_id)->first();

        $levels = Sight::where('quest_id', $quest_id)->count();

        $data['quest_title'] = $quest->title;
        $data['quest_city'] = $quest->getCityTitle();
        $data['levels'] = $levels;

            
        if(!empty($game))
        {

            $step = ($level && $level < $game->step) ? $level : $game->step;
            $status = ($level && $level < $game->step) ? 2 : $game->status;

            if($game->finished == 1) {
                $is_finished = true;
            }
            else {
                $is_finished = static::is_finish($quest_id, $step, $status);
            }

            $mode = Mode::select('title')->where('id', $game->mode_id)->first();

            $sight = Sight::where('quest_id', $quest_id)->where('step', $step)->first();
            $max_step = $sight->getMaxStep($quest_id);

            if($is_finished && $step == $max_step) {
                $data['step'] = $step;
                $data['step_total'] = $game->step;
                $data['status'] = $status;
                $data['skips_number'] = $game->skips_number;
                $data['hints_number'] = $game->hints_number;
                $data['mode_id'] = $game->mode_id;
                $data['mode_text'] = $mode->title;

                $data['sight_title'] = null;
                $data['sight_image'] = null;
                $data['sight_content'] = null;
                $data['sight_address'] = null;
                $data['sight_latitude'] = null;
                $data['sight_longitude'] = null;
                $data['answer1'] = null;
                $data['answer2'] = null;

                $data['sight_hint1'] = null;
                $data['sight_hint2'] = null;
                $data['show_hint'] = false;
                $data['show_skip'] = false;

                $data['task1'] = null;
                $data['task2'] = null;

                $data['finish'] = true;
                $data['finish_content'] = static::getPs($quest->finish);

                $data['is_level'] = 0;
            }
            else {

                
                
                $gameItem = Gameitem::where('game_id', $game->id)->where('step', $step)->first();

                $sightData = $sight->makeSightData($status, $game->mode_id);

                $tasksData = Task::getTasks($sight->id, $status);


                $hintsData = Task::getHints($gameItem, $game, $status, $sight->id);


                $data['step'] = $step;
                $data['step_total'] = $game->step;
                $data['status'] = $status;
                $data['skips_number'] = $game->skips_number;
                $data['hints_number'] = $game->hints_number;
                $data['mode_id'] = $game->mode_id;
                $data['mode_text'] = $mode->title;

                $data['sight_title'] = $sightData['title'];
                $data['sight_image'] = $sightData['image'];
                $data['sight_content'] = $sightData['content'];
                $data['sight_address'] = $sightData['address'];
                $data['sight_latitude'] = $sightData['latitude'];
                $data['sight_longitude'] = $sightData['longitude'];
                $data['answer1'] = $sightData['answer1'];
                $data['answer2'] = $sightData['answer2'];
                $data['inputs1'] = $sightData['inputs1'];
                $data['inputs2'] = $sightData['inputs2'];

                       
                $data['sight_hint1'] = $hintsData['sight_hint1'];
                $data['sight_hint2'] = $hintsData['sight_hint2'];
                $data['show_hint'] = $hintsData['show_hint'];
                $data['show_skip'] = ($game->skips_number < 1) ? false : $hintsData['show_skip'];

                $data['task1'] = $tasksData['task1'];
                $data['task2'] = $tasksData['task2'];

                $data['finish'] = false;

                $data['is_level'] = ($level && $level < $game->step) ? 1 : 0;
            }
                
        }
        else {
            $data['step'] = 0;
            $data['step_totlal'] = 0;
            $data['status'] = 0;
            $data['skips_number'] = $quest->skips_number;
            $data['hints_number'] = $quest->hints_number;
            $data['mode_id'] = 1;
            $data['mode_text'] = null;

            $data['sight_title'] = null;
            $data['sight_image'] = null;
            $data['sight_content'] = null;
            $data['sight_address'] = null;
            $data['sight_latitude'] = null;
            $data['sight_longitude'] = null;
            $data['answer1'] = null;
            $data['answer2'] = null;

            $data['sight_hint1'] = null;
            $data['sight_hint2'] = null;
            $data['show_hint'] = false;
            $data['show_skip'] = false;

            $data['task1'] = null;
            $data['task2'] = null;

            $data['finish'] = false;

            $data['is_level'] = 0;
        }
        
        return $data;
    }

    

    public function updateStatus()
    {
        
        switch ($this->status) {
            case 0:
                $this->status = 1;
                $this->save();
                break;

            case 1:
                $this->status = 2;
                $this->save();
                break;

            case 2:
                $this->status = 0;
                $this->step++;
                $this->save();
                break;
               
        }
        
        
    }

    public function is_finish($quest_id, $step, $status)
    {
        $sight = new Sight;
        $max_step = $sight->getMaxStep($quest_id);
        if ($step == $max_step && $status == 2) return true;

        return;
    }


    public function getPs($content)
    {
        $content = str_replace('<p>', '', $content);
        //$content = str_replace('</p>', '', $content);

        //return $content;

        $arr = explode('</p>', $content);
        
        return $arr;
    }

}
