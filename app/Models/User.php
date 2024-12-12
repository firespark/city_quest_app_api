<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    protected $fillable = [
        'name',
        'email',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];*/

    public function quests()
    {
        return $this->hasMany(Quest::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    
    public function createToken()
    {

        return Str::random(77) . time();

    }

    
    public static function add($api_token)
    {

        $user = new static;

        $user->password = bcrypt(Str::random(10) . time());
        $user->active = 1;
        $user->token = $api_token;
        
        $user->save();

        return $user;

    }



    public static function autoriseUserByToken($request)
    {

        $token = $request->bearerToken();

        if($token)
        {
            
            $api_token = hash('sha256', $token);

            $user = static::select('id')
                    ->where('token', $api_token)->where('active', 1)->first();

            if($user)
            {
                return $user->id;
            }
            else {

                $user = static::add($api_token);

                return $user->id;
            }
        }

        return false;
    }

}
