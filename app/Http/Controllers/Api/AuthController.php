<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class AuthController extends ApiController
{
    
    public function login(Request $request)
    {

        $token = $request->bearerToken();

        if($token)
        {
            $api_token = hash('sha256', $token);

            $email = $request->get('email');
            $password = $request->get('password');
            
            if( $email && $password ) 
            {
                $user = User::select('id', 'password', 'token', 'logged_in')
                    ->where('email', $email)->where('active', 1)->first();

                if ($user && Hash::check($password, $user->password))
                {
                    
                    $user->logged_in = 1;
                    $user->token = $api_token;

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
                    $this->response->setError('Неправильный Email или Пароль');
                }
                
            }

            else
            {
                $this->response->setError('Не все данные переданы');
            }
        }
        else
        {
            $this->response->setStatus(401);
        }

        return $this->response->responseData();
    } 
    
    

    public function checkEmail(Request $request)
    {
        $email = $request->get('email');

        if($email) {

            $user = User::select('id', 'active')
                    ->where('email', $email)->first();
            if($user)
            {
                if($user->active == 1)
                {
                    $this->response->setData(['email_exists' => 1]); 
                    $this->response->toggleSuccess();
                }
                else
                {
                    $this->response->setError('Пользователь заблокирован');
                }
                
            }
            else
            {
                $this->response->setData(['email_exists' => 0]); 
                $this->response->toggleSuccess();
            }
        }
        else
        {
            $this->response->setError('Не передан емейл');
        }
        return $this->response->responseData();

        
    }

    public function sendCode(Request $request)
    {
        $email = $request->get('email');

        

        if ($email)
        {
           
            $user = User::select('id', 'active', 'code', 'password', 'token', 'name', 'email')
                    ->where('email', $email)->first();

            if(!$user) {
                $user = new User;

                $user->email = $email;
                $user->name = substr($email, 0, strpos($email, '@'));

                $user->password = bcrypt($user->createToken());
                $user->token = hash('sha256', $user->createToken());
                $user->active = 1;
            }

            if($user->active)
            {

            
                $code = rand(1000, 9999);
                $user->code = $code;
                
                if($user->save())
                {
                    mail($email, 'Код доступа Гагара Квест', 'Код: ' . $code);

                    $this->response->toggleSuccess();

                }
                else
                {
                    $this->response->setStatus(500);
                }
            }
            else
            {
                $this->response->setError('Пользователь заблокирован');
            }
        }
        else 
        {
            $this->response->setError('Не передан емейл');
        } 
            
       
        return $this->response->responseData();

        
    }

    public function checkCode(Request $request)
    {
        $code = $request->get('code');

        if($code)
        {
            $user = User::select('id')
                    ->where('email', $request->get('email'))->where('code', $code)->first();
            if($user)
            {
                //$this->response->setData(['email' => true]);
                $this->response->toggleSuccess();
            }
            else
            {
                $this->response->setError('Неправильный код');
            }
        }
        else
        {
            $this->response->setError('Не передан код');
        }
        
        return $this->response->responseData();

        
    }

    public function changePassword(Request $request)
    {


        $email = $request->get('email');
        $password = $request->get('password');
        $code = $request->get('code');
        
        if( $password && $code && $email ) 
        {
            if(strlen($password) >= 8) {

                $user = User::select('id', 'password', 'active', 'logged_in', 'code')
                    ->where('code', $code)->where('email', $email)->first();
                if ($user)
                {

                    $user->password = bcrypt($password);
                    $user->active = 1;
                    $user->logged_in = 1;
                    $user->code = null;

            
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
                    $this->response->setError('Неправильный Код доступа');
                }
            }
            else
            {
                $this->response->setError('Пароль должен состоять минимум из 8 символов');
            }
            
        }

        else
        {
            $this->response->setError('Не все данные переданы');
        }
        

        return $this->response->responseData();
    }




}