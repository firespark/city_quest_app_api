<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Sight extends Model
{

    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    
    public function getQuestTitle()
    {
        if ($this->quest != null)
        {
            return $this->quest->title;
        }

        return '';
    }

    
    public function getImage()
    {
        if($this->image == null) 
        {
            return URL::to('/') . '/img/the-blurred.jpg';
        }
        return URL::to('/') . '/img/' . $this->image;
    }

    

    public function checkStep($step, $quest_id)
    {
        if ($this->step == $step) return $step;

        $is_step = static::select('id')->where('quest_id', '=', $quest_id)->where('step', '=', $step)->first();


        if ($is_step) {
            $max_step = getMaxStep($quest_id);
            return ++$max_step;
        }

        return $step;
        
    }

    
    public function checkAnswer($answer, $answerNumber)
    {
        
        $data['inputResults'] = [];
        $errors = 0;

        switch ($answerNumber) {
            case 1:
                $answerStr = $this->answer1;
                break;

            case 2:
                $answerStr = $this->answer2;
                break;
            
        }

        $answerArr = explode('|', $answerStr);

        if (!empty($answerArr))
        {
            foreach($answerArr as $key => $arr)
            {
                $strArr = explode(',', str_replace('-', '', $arr));
                $correct = 0;
                if (in_array(mb_strtolower(trim(str_replace('-', '', $answer[$key]))), $strArr))
                    {
                        $correct = 1;
                        unset($answerArr[$key]);
                    }
                /* 
                foreach($answer as $answer_key => $answer_value) {
                    if (in_array(mb_strtolower(trim($answer_value)), $strArr))
                    {
                        $correct = 1;
                        unset($answerArr[$key]);
                        break;
                    }
                    
                } */

                if(!$correct)
                {
                    $errors++;
                }

                $data['inputResults'][$key] = $correct;

            }

        }

        $data['errors'] = $errors;

        return $data;
        
    }

    public function getAnswerStr($answer)
    {
        $str = '';
        $answerArr = explode('|', $answer);

        if (!empty($answerArr))
        {
            foreach($answerArr as $arr)
            {
                $strArr = explode(',', $arr);

                $str .= $strArr[0] . ' ';

            }

            
        }
        return trim($str);
    }

    
    public function getMaxStep($quest_id)
    {
        return $this->select('step')->where('quest_id', '=', $quest_id)->max('step');
    }

    public function getInputsNumber($taskNumber)
    {

        switch ($taskNumber) {
            case 1:
                return count(explode('|', $this->answer1));
                break;

            case 2:
                return count(explode('|', $this->answer2));
                break;

            
            default:
                return 0;
                break;
        }


    }

    public function getUserFriendlyAnswer($answerNumber)
    {

        switch ($answerNumber) {
            case 1:
                $arrs = explode('|', $this->answer1);
                break;

            case 2:
                $arrs = explode('|', $this->answer2);
                break;

            
            default:
                return null;
                break;
        }

        if(empty($arrs)) return null;

        $answer = '';

        foreach ($arrs as $arr) {
            $arr_item = explode(',', $arr);
            $answer .= ' ' . $arr_item[0];
        }

        return(trim($answer));

    }

    public function makeSightData($status, $mode)
    {

        $data = [];
        $data['title'] = ( ( ($status == 1 || $status == 2) && ($mode == 1 || $mode == 2) ) || ( ($status == 2) && ($mode == 3 || $mode == 4) ) ) ? $this->title : null;

        $data['image'] = ( ( ($status == 1 || $status == 2) && ($mode == 1 || $mode == 2 || $mode == 3) ) || ( $status == 2 && $mode == 4 ) ) ? $this->getImage() : null;

        $data['content'] = ( ( ($status == 1 || $status == 2) && ($mode == 1 || $mode == 2) ) || ( ($status == 2) && ($mode == 3 || $mode == 4) ) ) ? $this->getPs() : null;

        $data['address'] = ( ( ($status == 1 || $status == 2) && $mode == 1 ) || ( ($status == 2) && ($mode == 2 ||$mode == 3 || $mode == 4) ) ) ? $this->address : null;

        $data['latitude'] = ( ( ($status == 1 || $status == 2) && $mode == 1 ) || ( ($status == 2) && ($mode == 2 ||$mode == 3 || $mode == 4) ) ) ? $this->latitude : null;

        $data['longitude'] = ( ( ($status == 1 || $status == 2) && $mode == 1 ) || ( ($status == 2) && ($mode == 2 ||$mode == 3 || $mode == 4) ) ) ? $this->longitude : null;


        $data['answer1'] = ($status == 1 || $status == 2) ? $this->getUserFriendlyAnswer(1) : null;
        $data['answer2'] = ($status == 2) ? $this->getUserFriendlyAnswer(2) : null;

        $data['inputs1'] = $this->getInputsNumber(1);
        $data['inputs2'] = ($status == 1 || $status == 2) ? $this->getInputsNumber(2) : null;

        return $data;

    }

    public function getPs()
    {
        $content = str_replace('<p>', '', $this->description);
        //$content = str_replace('</p>', '', $content);

        //return $content;

        $arr = explode('</p>', $content);
        
        return $arr;
    }


   
}
