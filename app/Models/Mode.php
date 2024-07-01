<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Mode extends Model
{

    public function games()
    {
        return $this->hasMany(Game::class);
    }

}
