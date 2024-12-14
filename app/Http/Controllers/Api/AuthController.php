<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UnverifiedEmail;
use Illuminate\Support\Facades\Hash;
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

    private function verifyGoogleIdToken($idToken) {
        $googleOAuthEndpoint = "https://oauth2.googleapis.com/tokeninfo";
        $url = $googleOAuthEndpoint . "?id_token=" . urlencode($idToken);

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Content-Type: application/json\r\n"
            ]
        ]);

       try { 
            $response = file_get_contents($url, false, $context);
    
            if ($response === false) {
                return [
                    'success' => false,
                    'message' => 'Ошибка верификации',
                    'data' => null,
                ];
            }

            $data = json_decode($response, true);

            if (isset($data['email_verified']) && $data['email_verified'] == 'true') {
                return [
                    'success' => true,
                    'message' => 'Верификация прошла успешно',
                    'data' => $data,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Токен не валиден',
                    'data' => null,
                ];
            }
        }
        catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function loginGoogle(Request $request)
    {
        $token = $request->bearerToken();
        $idToken = $request->get('idToken');
        $success = false;

        if($token && $idToken)
        {
            $result = $this->verifyGoogleIdToken($idToken);
            if ($result['success']) {
                $azp = $result['data']['azp'];

                if (env('AZP_KEY') == $azp) {
                    $name = $result['data']['name'];
                    $email = $result['data']['email'];
                    
                    if ($email) {
                        $api_token = hash('sha256', $token);
                        $user = User::select('id', 'name', 'token', 'logged_in')
                            ->where('email', $email)->where('active', 1)->first();
                        
                        if ($user){
                            User::where('token', $api_token)->where('email', null)->delete();
                            $user->logged_in = 1;
                            $user->token = $api_token;

                            if ($name) $user->name = $name;

                            if($user->save())
                            {
                                $success = true;
                                $this->response->toggleSuccess();

                            }
                            else
                            {
                                $this->response->setStatus(500);
                            }
                        }
                        else {
                            $user = User::select('id', 'name', 'token', 'logged_in')
                                ->where('token', $api_token)->where('active', 1)->first();
                            if ($user) {
                                $user->logged_in = 1;
                                $user->email = $email;

                                if ($name) $user->name = $name;
    
                                if($user->save())
                                {
                                    $success = true;
                                    $this->response->toggleSuccess();
    
                                }
                                else
                                {
                                    $this->response->setStatus(500);
                                }
                            }
                            else {
                                $user = new User;

                                $user->password = bcrypt(Str::random(10) . time());
                                $user->active = 1;
                                $user->token = $api_token;
                                $user->email = $email;
                                $user->name = $name;
                                
                                $user->save();
                            }

                        }
                    }
                }

            }
        }

        if (!$success)
        {
            $this->response->setStatus(401);
            $this->response->setError('Ошибка валидации пользователя');
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
            $user = User::select('id')
                    ->where('email', $email)->first();

            if($user) {
                $this->response->setError('Такой пользователь уже существует');
            }
            else {
                $unverifiedEmail = new UnverifiedEmail;
                $unverifiedEmail->email = $email;
                $code = rand(1000, 9999);
                $unverifiedEmail->code = $code;

                if($unverifiedEmail->save())
                {
                    mail($email, 'Код доступа Гагара Квест', 'Код: ' . $code);

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
            $this->response->setError('Не передан емейл');
        } 
            
       
        return $this->response->responseData();

        
    }

    public function sendPasswordReset(Request $request)
    {
        $email = $request->get('email');

        if ($email)
        {
            $user = User::select('id')
                    ->where('email', $email)->first();

            if(!$user) {
                $this->response->setError('Пользователь не найден');
            }
            else {
                $unverifiedEmail = new UnverifiedEmail;
                $unverifiedEmail->email = $email;
                $code = rand(1000, 9999);
                $unverifiedEmail->code = $code;

                if($unverifiedEmail->save())
                {
                    mail($email, 'Код доступа Гагара Квест', 'Код: ' . $code);

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
            $this->response->setError('Не передан емейл');
        } 
            
       
        return $this->response->responseData();

        
    }

    public function checkCode(Request $request)
    {
        $code = $request->get('code');
        $email = $request->get('email');
        $token = $request->bearerToken();
        $api_token = hash('sha256', $token);

        if($code)
        {
            try {
                $unverifiedId = UnverifiedEmail::select('id')
                        ->where('email', $email)->where('code', $code)->first();
                if($unverifiedId)
                {
                    $user = User::select('id', 'password', 'token', 'name', 'email')
                    ->where('token', $api_token)->first();

                    if(!$user) {
                        $user = new User;
                        $user->token = $api_token;
                        $user->active = 1;
                    }

                    $user->email = $email;
                    $user->name = substr($email, 0, strpos($email, '@'));
                    $user->password = bcrypt($user->createToken());

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
                    $this->response->setError('Неправильный код');
                }
            } 
            catch (Exception $e) {
                $this->response->setError('Произшла ошибка');
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
        $code = $request->get('code');
        $email = $request->get('email');
        $password = $request->get('password');
        
        if( $password && $email ) 
        {
            if(strlen($password) >= 8) {
                $unverifiedId = UnverifiedEmail::select('id')->where('email', $email)->where('code', $code)->first();
                $user = User::select('id', 'password', 'active', 'logged_in')->where('email', $email)->first();
                if ($user && $unverifiedId)
                {
                    UnverifiedEmail::where('email', $email)->delete();
                    $user->password = bcrypt($password);
                    $user->active = 1;
                    $user->logged_in = 1;

            
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