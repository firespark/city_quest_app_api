<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    public function quests()
    {
        return $this->hasMany(Quest::class);
    }


    public function getImage()
    {
        if($this->image == null) 
        {
            return '/img/the-blurred.jpg';
        }
        return '/img/' . $this->image;
    }
}
