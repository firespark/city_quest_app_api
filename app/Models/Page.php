<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Page extends Model
{  
	public function getPs()
    {
        $content = str_replace('<p>', '', $this->content);
        
        return explode('</p>', $content);
    }
}
