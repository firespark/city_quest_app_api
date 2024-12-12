<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\Task;
use App\Models\Sight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{

    public function index()
    {

        /*$ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://test2.gagara-web.ru/api/games/get/6");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Accept: application/json";
        $headers[] = "Authorization: Bearer Og1iV5bDfeg94vuuH1uquDLZNEBb3JWlomUMJik3QPHfstfeNmV7zmPEvWmgQ9OWNfg2thM5mNdNL1660710600391";
        $headers[] = "Accept-Language: en";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        print_r(json_decode($result));


        /*$tasks = Task::whereNotNull('image')->where('image_width', 0)->get();

        foreach ($tasks as $task) {
            $image = '';
            $imageArr = explode('.', $task->image);
            if(array_key_exists(1, $imageArr) ) {
                /*if($imageArr[1] == 'jpg') {
                    $image = $imageArr[0] . '.png';
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $image;
                }
                if($imageArr[1] == 'png') {
                    $image = $imageArr[0] . '.jpg';
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $image;
                }*/

                /*$path = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $imageArr[0] . '.' . $imageArr[1];
                $image = $imageArr[0] . '-1.' . $imageArr[1];
                echo $path . '<br>';
                if (file_exists($path)) {
                    $size = getimagesize($path);
                    if($size[0] && $size[1]) {

                        /*$task->image = $image;
                        $task->image_width = $size[0];
                        $task->image_height = $size[1];
                        $task->save();*/
                        /*echo 1 . '<br>';
                    }
                }
                else {
                    echo 'no' . '<br>';
                }
            }
            else {
                print_r($imageArr);
            }
            
            
        }*/



        /*$send_message = 'Имя: rrr<br>';
            $send_message .= 'Email: rrr\r\n';
            $send_message .= 'Сообщение: rrr\r\n';

            Mail::send([], [], function($message) use ($send_message)
            {
              
                $message->subject('Гагара-Квест приложение. Сообщение');
                $message->setBody($send_message, 'text/html');
            });



        
        Mail::raw('Текст письма', function($message)
        {
            $message->subject('Welcome to the Tutorials Point');
        });

        $sights = Sight::get();

        foreach ($sights as $sight) {
            $sight->answer1 = mb_strtolower(trim($sight->answer1));
            $sight->answer2 = mb_strtolower(trim($sight->answer2));
            $sight->save();
        }*/
    }

    

    /*public function insertXml()
    {
        $slug = 'spb';
        
        $xml_file = URL::to('/') . '/quest.xml';

        $xmlfile = file_get_contents($xml_file); 
      
        $new = simplexml_load_string($xmlfile); 

        $con = json_encode($new); 
          
        $newArr = json_decode($con, true); 

        $quests = [];

        if(isset($newArr['quest'][0])) {
            $quests = $newArr['quest'];
        }
        else {
            $quests[0] = $newArr['quest'];
        }



        foreach ($quests as $quest) {



            if(isset($quest['id'])) {
                $quest_id = $quest['id'];
                $trip_number = $quest['trip'];

                foreach($quest['sights']['sight'] as $key => $sight) {

                    $pos = strpos($sight['title'], '.');

                    $sight_number = trim(mb_substr($sight['title'], 0, $pos));
                    $title = trim(mb_substr($sight['title'], $pos + 2));

                    $answer1 = mb_strtolower(trim($sight['answer1']));
                    $answer2 = mb_strtolower(trim($sight['answer2']));
                    $address = trim($sight['address']);
                    $step = trim($sight['step']);
                    $image = '/' . $slug . '/trip' . $trip_number . '/' . $sight_number . '.jpg';

                    $description = '';

                    if(is_array($sight['description']['p'])) {
                        foreach($sight['description']['p'] as $p) {
                            $description .= '<p>' . trim($p) . '</p>';
                        }
                    }
                    else {
                        $description = trim($sight['description']['p']);
                    }
                        

                    $description = str_replace('!--br--!', '<br>', $description);

                    $coords_arr = explode(' ', trim($sight['coords']));
                    
                    $latitude = preg_replace("/[^.0-9]/", '', $coords_arr[0]);
                    $longitude = preg_replace("/[^.0-9]/", '', $coords_arr[1]);
                    

                    $newSight = new Sight;

                    $newSight->title = $title;
                    $newSight->quest_id = $quest_id;
                    $newSight->answer1 = $answer1;
                    $newSight->answer2 = $answer2;
                    $newSight->step = $step;
                    $newSight->image = $image;
                    $newSight->description = $description;
                    $newSight->address = $address;
                    $newSight->latitude = $latitude;
                    $newSight->longitude = $longitude;

                    $newSight->save();

                    $tasks_arr = [];

                    if(isset($sight['task1']['task'][0])) {
                        $tasks_arr = $sight['task1']['task'];
                    }
                    else {
                        $tasks_arr[0] = $sight['task1']['task'];
                    }
                    
                    foreach ($tasks_arr as $task) {
                        $task_image = ($task['image']) ? '/' . $slug . '/trip' . $trip_number . '/tasks/' . trim($task['image']) : null;
                        $task_text = ($task['question']) ? str_replace('!--br--!', '<br>', trim($task['question'])) : null;

                        $newTask = new Task;
                        $newTask->quest_id = $quest_id;
                        $newTask->sight_id = $newSight->id;
                        $newTask->text = $task_text;
                        $newTask->image = $task_image;
                        $newTask->type = 1;
                        $newTask->save();
                       
                    }

                    $newTask = new Task;
                    $newTask->quest_id = $quest_id;
                    $newTask->sight_id = $newSight->id;
                    $newTask->text = str_replace('!--br--!', '<br>', trim($sight['task2']));
                    $newTask->image = null;
                    $newTask->type = 2;
                    $newTask->save();

                    
                    $hint1 = trim($sight['hint1']);
                    $newTask = new Task;

                    if(strpos($hint1, '.jpg') || strpos($hint1, '.png')) {

                        $newTask->quest_id = $quest_id;
                        $newTask->sight_id = $newSight->id;
                        $newTask->text = null;
                        $newTask->image = '/' . $slug . '/trip' . $trip_number . '/tasks/' . $hint1;
                        $newTask->type = 3;
                        
                    }
                    else {
                        $newTask->quest_id = $quest_id;
                        $newTask->sight_id = $newSight->id;
                        $newTask->text = str_replace('!--br--!', '<br>', $hint1);
                        $newTask->image = null;
                        $newTask->type = 3;
                        
                    }

                    $newTask->save();

                    

                    $hint2 = trim($sight['hint2']);
                    $newTask = new Task;

                    if(strpos($hint2, '.jpg') || strpos($hint2, '.png')) {
                        $newTask->quest_id = $quest_id;
                        $newTask->sight_id = $newSight->id;
                        $newTask->text = null;
                        $newTask->image = '/' . $slug . '/trip' . $trip_number . '/tasks/' . $hint2;
                        $newTask->type = 4;
                        
                    }
                    else {
                        $newTask->quest_id = $quest_id;
                        $newTask->sight_id = $newSight->id;
                        $newTask->text = str_replace('!--br--!', '<br>', $hint2);
                        $newTask->image = null;
                        $newTask->type = 4;
                        
                    }

                    $newTask->save();
                    
                }
            }


        }

       
        
    }*/
}
