<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Quest extends Model
{


    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function sights()
    {
        return $this->hasMany(Sight::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

   
    public function getImage()
    {
        if($this->image == null) 
        {
            return URL::to('/') . '/img/the-blurred.jpg';
        }
        return URL::to('/') . '/img/' . $this->image;
    }

    
    public function getCityTitle()
    {
        if ($this->city != null)
        {
            return $this->city->title;
        }

        return '';
    }

    public function getPs()
    {
        $content = str_replace('<p>', '', $this->content);
        $content = str_replace('</p>', '', $content);

        return $content;

        //return explode('</p>', $content);
    }

    
    public function related()
    {
        return Self::where('city_id', $this->city_id)->whereNotIn('id', [$this->id])->get();
    }

    public function getData($step, $mode_id)
    {
        $data = [];

        $levels = Sight::where('quest_id', $this->id)->count();
        $mode = Mode::select('title')->where('id', $mode_id)->first();

        $data['id'] = $this->id;
        $data['quest_title'] = $this->title;
        $data['quest_image'] = $this->getImage();
        $data['city'] = $this->getCityTitle();
        $data['levels'] = $levels;
        $data['step'] = $step;
        $data['mode_id'] = $mode_id;
        $data['mode_text'] = $mode->title;

        return $data;
    }

    

}
