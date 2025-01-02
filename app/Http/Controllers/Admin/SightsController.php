<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quest;
use App\Models\Task;
use App\Models\Sight;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SightsController extends Controller
{
    public function index()
    {
        $perpage = 20;
        $sights = Sight::with(['quest:id,title'])->orderBy('quest_id', 'DESC')->orderBy('step', 'ASC')->paginate($perpage);
        
        return view('admin.sights.index', compact(
            'sights',
        ));
    }
    public function quest($quest_id)
    {
        $perpage = 20;
        $sights = Sight::with(['quest:id,title'])->where('quest_id',$quest_id)->orderBy('step', 'ASC')->paginate($perpage);
        $quest = Quest::select('id','title')->where('id',$quest_id)->first();

        return view('admin.sights.index', compact(
            'sights',
            'quest',
        ));
    }
    public function create($quest_id = 0)
    {
        $quests = Quest::pluck('id', 'title')->all();
        $step = Sight::checkStep(1, $quest_id);
        return view('admin.sights.create', compact(
            'quests',
            'quest_id',
            'step',
        ));
    }

    
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required',
            'step' => 'required',
            'quest_id' => 'required',
            'image' => 'nullable|image',
            'description' => 'required',
            'address' =>'required',
            'latitude' =>'required',
            'longitude' => 'required',

            'task1_text' => ['nullable', 'array'],
            'task1_text.*' => ['nullable', 'string'], 
            'task1_image' => ['nullable', 'array'], 
            'task1_image.*' => ['nullable', 'image'], 
            'task1_text' => [
                function ($attribute, $value, $fail) {
                    $texts = request()->input('task1_text', []);
                    $files = request()->file('task1_image', []);
                    $hasText = array_filter($texts, fn($text) => !empty(trim($text)));
                    $hasFile = array_filter($files);
    
                    if (empty($hasText) && empty($hasFile)) {
                        $fail('Вы должны заполнить хотя бы одно текстовое поле или загрузить изображение.');
                    }
                }
            ],

            'answer1' => ['required', 'string', 'max:255'],

            'hint1_text' => ['nullable', 'string'],
            'hint1_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'hint1_text' => [
                function ($attribute, $value, $fail) use ($request) {
                    if (empty($value) && !$request->hasFile('hint1_image')) {
                        $fail('Необходимо указать текст подсказки или загрузить изображение.');
                    }
                }
            ],

            'task2_text' => ['nullable', 'string'],
            'task2_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'task2_text' => [
                function ($attribute, $value, $fail) use ($request) {
                    if (empty($value) && !$request->hasFile('task2_image')) {
                        $fail('Необходимо указать текст вопроса 2 или загрузить изображение.');
                    }
                }
            ],

            'answer2' => ['required', 'string', 'max:255'],

            'hint2_text' => ['nullable', 'string'],
            'hint2_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'hint2_text' => [
                function ($attribute, $value, $fail) use ($request) {
                    if (empty($value) && !$request->hasFile('hint2_image')) {
                        $fail('Необходимо указать текст подсказки или загрузить изображение.');
                    }
                }
            ],
        ]);

        $quest_slug = Quest::select('slug')->where('id', $validatedData['quest_id'])->first()->slug;

        $slug = explode('-', $quest_slug);
        $tripNumber = filter_var($slug[1], FILTER_SANITIZE_NUMBER_INT);
        $imageFolderPath = "/{$slug[0]}/{$slug[1]}/";

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $fileName = "{$validatedData['step']}.{$extension}";
            $imagePath = "{$imageFolderPath}{$fileName}";
    
            $image->storeAs("public/img{$imageFolderPath}", "{$fileName}", 'public_uploads');
        }
        $tasksImagePath = "{$imageFolderPath}tasks/{$validatedData['step']}";

        $sight = Sight::create([
            'title' => $validatedData['title'],
            'quest_id' => $validatedData['quest_id'],
            'answer1' => mb_strtolower(trim($validatedData['answer1'])),
            'answer2' => mb_strtolower(trim($validatedData['answer2'])),
            'step' => Sight::checkStep($validatedData['step'], $validatedData['quest_id']),
            'description' => $validatedData['description'],
            'address' => $validatedData['address'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'image' => $imagePath,
        ]);
        

        for ($i=0; $i <= 2 ; $i++) { 
            if ($validatedData['task1_text'][$i] || isset($validatedData['task1_image'][$i]))
            {
                $type = $i + 1;
                $task1Image = null;
                if (isset($validatedData['task1_image'][$i]))
                {
                    $task1Image = $this->createTaskImage($validatedData['task1_image'][$i], $imageFolderPath, $validatedData['step'], "1-$type");
                }
                
                $task1 = Task::create([
                    'quest_id' => $validatedData['quest_id'],
                    'sight_id' => $sight->id,
                    'text' => $validatedData['task1_text'][$i],
                    'image' => $task1Image,
                    'type' => 1,

                ]);
            }
        }


        $task2ImagePath = $this->createTaskImage($request->file('task2_image'), $imageFolderPath, $validatedData['step'], '2');

        $task2 = Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['task2_text'],
            'image' => $task2ImagePath,
            'type' => 2,

        ]);

        $hint1ImagePath = $this->createTaskImage($request->file('hint1_image'), $imageFolderPath, $validatedData['step'], '3');

        $hint1 = Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['hint1_text'],
            'image' => $hint1ImagePath,
            'type' => 3,

        ]);

        $hint2ImagePath = $this->createTaskImage($request->file('hint2_image'), $imageFolderPath, $validatedData['step'], '4');

        $hint2 = Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['hint2_text'],
            'image' => $hint2ImagePath,
            'type' => 3,

        ]);



        return redirect()->route('admin.sights.edit', $sight->id); 
    }

    
    public function edit($id)
    {
        $sight = Sight::find($id);

        $quests = Quest::pluck('id', 'title')->all();
        $tasks = Task::where('sight_id',$id)->get();
        $groupedTasks = $tasks->groupBy('type');

        $question1 = [];
        $question2 = [];
        $hint1 = [];
        $hint2 = [];

        foreach ($tasks as $key => $task) {
            $arr = ['text'=>$task->text, 'image'=>$task->image];
            switch ($task->type) {
                case 1:
                    $question1[] = $arr;
                    break;
                
                case 2:
                    $question2[] = $arr;
                    break;
                
                case 3:
                    $hint1[] = $arr;
                    break;
                
                case 4:
                    $hint2[] = $arr;
                    break;
                
                default:
                    echo 'Таски без правильного типа присутствуют в коде';
                    break;
            }
        }
        
        $fillValue = ['text'=>null,'image'=>null];

        $question1 = array_pad($question1, 3, $fillValue);


        return view('admin.sights.edit', compact(
            'sight',
            'quests',
            'question1',
            'question2',
            'hint1',
            'hint2',
        ));
    }

    
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'title' => 'required',
            'step' => 'required',
            'quest_id' => 'required',
            'question1' => 'required',
            'answer1' => 'required',
            'question2' => 'required',
            'answer2' => 'required',
            'image' => 'nullable|image',
        ]);

        $sight = Sight::find($id);

        $sight->step = $sight->checkStep($request->get('step'), $request->get('quest_id'));
        
        $coordsArr = $sight->getCoordsArr($request->get('coords'));

        $sight->latitude = $coordsArr['latitude'];
        $sight->longitude = $coordsArr['longitude'];

        $sight->image = $sight->uploadImage($request->file('image'));

        $sight->answer1 = mb_strtolower(trim($request->get('answer1')));
        $sight->answer2 = mb_strtolower(trim($request->get('answer2')));


        $sight->fill($request->all());



        $sight->save();

        return redirect()->route('admin.sights.edit', $id);
    }

    
    public function destroy($id)
    {
        Sight::find($id)->remove();


        return redirect()->route('admin.sights.index');
    }

    protected function createTaskImage($image, $imageFolderPath, $step, $type)
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
}
