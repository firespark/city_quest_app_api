<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Task extends Model
{
    protected $guarded = [];
    public function sight()
    {
        return $this->belongsTo(Sight::class);
    }

    public function getImage()
    {
        if($this->image != null) 
        {
            return URL::to('/') . '/img/' . $this->image;
        }
        return null;
    }


    public function getTasks($sight_id, $status) {

        $data['task1'] = static::getTask($sight_id, 1);
        $data['task2'] = ($status == 1 || $status == 2) ? static::getTask($sight_id, 2) : null;

        return $data;
    }

    public function getHints($gameItem, $game, $status, $sight_id) {

        $data['show_hint'] = true;
        $data['show_skip'] = true;
        $data['sight_hint1'] = null;
        $data['sight_hint2'] = null;

        if($status == 2) {
            $data['show_hint'] = false;
            $data['show_skip'] = false;
                
        }

        if($gameItem) {

            
            if( ($gameItem->hint1 && ($status == 0)) || ($game->hints_number < 1)) {
                $data['show_hint'] = false;
                $hint1 = static::getTask($sight_id, 3);
                if(isset($hint1['tasks'][0])) {
                    $data['sight_hint1'] = $hint1['tasks'][0];
                }
                

            }
            if( ($gameItem->hint2 && ($status == 1)) || ($game->hints_number < 1)) {
                $data['show_hint'] = false;
                $hint2 = static::getTask($sight_id, 4);
                if(isset($hint2['tasks'][0])) {
                    $data['sight_hint2'] = $hint2['tasks'][0];
                }
            }
            if(($gameItem->skip1 && ($status == 0)) || ($game->skips_number < 1)) {
                $data['show_skip'] = false;
                
            }
            if(($gameItem->skip2 && ($status == 1)) || ($game->skips_number < 1)) {
                $data['show_skip'] = false;
                
            }
        }



        return $data;
    }

    public function getTask($sight_id, $type) {
        $data['template'] = null;
        $data['tasks'] = null;

        $tasks = static::where('sight_id', $sight_id)->where('type', $type)->get();


        if(!empty($tasks)) {
            $data['template'] = (count($tasks) > 1) ? 2 : 1;
            $data['tasks'] = [];

            foreach ($tasks as $task) {
                $data['tasks'][] = [
                    'image' => ($task->image) ? URL::to('/') . '/img/' . $task->image : null,
                    'image_width' => $task->image_width,
                    'image_height' => $task->image_height,
                    'text' => explode('<br>', $task->text),
                ];
            }
        }

        return $data;   

    }

    public function createTaskImage($image, $imageFolderPath, $step, $type)
    {
        $imagePath = null;
        if ($image) {
            $extension = $image->getClientOriginalExtension();
            $fileName = "{$step}-{$type}.{$extension}";
            $imagePath = "{$imageFolderPath}tasks/{$fileName}";
    
            $image->storeAs("public/img{$imageFolderPath}tasks/", "{$fileName}", 'public_uploads');
        }
        return $imagePath;
    }

    public function deleteTaskImage($image) {
        $imagePath = "public/img" . $image;
        print_r($imagePath);
        if (Storage::disk('public_uploads')->exists($imagePath)) {
            Storage::disk('public_uploads')->delete($imagePath);
        }
    }

}
