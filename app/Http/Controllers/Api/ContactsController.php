<?php

namespace App\Http\Controllers\Api;

use Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class ContactsController extends ApiController
{
    
    
    public function send(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $message = $request->get('message');

        if ($name && $email && $message)
        {
           
            $send_message = 'Имя: ' . $name . '<br>';
            $send_message .= 'Email: ' . $email . '<br>';
            $send_message .= 'Сообщение: ' . $message . '<br>';

            Mail::send([], [], function($message) use ($send_message)
            {
                $message->to(env('MAIL_TO_ADDRESS'));
                $message->subject('Гагара-Квест приложение. Сообщение');
                $message->setBody($send_message, 'text/html');
            });
            
            $this->response->setData(['message' => 'Сообщение отправлено']);
            $this->response->toggleSuccess();
        }
        else 
        {
            $this->response->setError('Не все поля заполнены');
        } 
            
       
        return $this->response->responseData();

        
    }



}