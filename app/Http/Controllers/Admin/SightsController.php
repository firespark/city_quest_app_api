<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quest;
use App\Models\Task;
use App\Models\Sight;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                    $task1Image = Task::createTaskImage($validatedData['task1_image'][$i], $imageFolderPath, $validatedData['step'], "1-$type");
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


        $task2ImagePath = Task::createTaskImage($request->file('task2_image'), $imageFolderPath, $validatedData['step'], '2');

        $task2 = Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['task2_text'],
            'image' => $task2ImagePath,
            'type' => 2,

        ]);

        $hint1ImagePath = Task::createTaskImage($request->file('hint1_image'), $imageFolderPath, $validatedData['step'], '3');

        $hint1 = Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['hint1_text'],
            'image' => $hint1ImagePath,
            'type' => 3,

        ]);

        $hint2ImagePath = Task::createTaskImage($request->file('hint2_image'), $imageFolderPath, $validatedData['step'], '4');

        $hint2 = Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['hint2_text'],
            'image' => $hint2ImagePath,
            'type' => 4,

        ]);



        return redirect()->route('admin.sights.edit', $sight->id); 
    }

    
    public function edit($id)
    {
        $sight = Sight::find($id);

        $quests = Quest::pluck('id', 'title')->all();
        $tasks = Task::where('sight_id', $id)->get();
        $groupedTasks = $tasks->groupBy('type');

        $question1 = [];
        $question2 = [];
        $hint1 = [];
        $hint2 = [];

        foreach ($tasks as $key => $task) {
            $arr = [
                'id' => $task->id, 
                'text' => $task->text, 
                'image' => $task->image
            ];
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

        $fillValue = ['id'=> null, 'text'=> null,'image'=> null];

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
        $sight = Sight::find($id);

        $validatedData = $this->validateUpdateRequest($request);

        $questSlug = Quest::select('slug')->where('id', $validatedData['quest_id'])->first()->slug;
        [$imageFolderPath, $tripNumber] = $this->getImageFolderPath($questSlug);

        $imagePath = $this->updateSightImage($request, $sight, $validatedData, $imageFolderPath);

        $sight->update($this->getSightUpdateData($validatedData, $imagePath));

        $this->updateTask1($validatedData, $sight, $imageFolderPath);
        $this->updateTask2($request, $validatedData, $imageFolderPath);
        $this->updateHint($request, $validatedData, $imageFolderPath, 'hint1');
        $this->updateHint($request, $validatedData, $imageFolderPath, 'hint2');

        return redirect()->route('admin.sights.edit', $sight->id)->with('success', 'Достопримечательность успешно отредактирована!');
    }

    private function validateUpdateRequest(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'step' => 'required',
            'quest_id' => 'required',
            'image' => 'nullable|image',
            'description' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',

            'task1_id' => ['nullable', 'array'],
            'task1_text' => ['nullable', 'array'],
            'task1_text.*' => ['nullable', 'string'],
            'task1_image' => ['nullable', 'array'],
            'task1_image.*' => ['nullable', 'image'],
            'task1_imgsrc' => ['nullable', 'array'],
            'task1_text' => [
                function ($attribute, $value, $fail) {
                    $texts = request()->input('task1_text', []);
                    $imgsrcs = request()->input('task1_imgsrc', []);
                    $files = request()->file('task1_image', []);
                    $hasText = array_filter($texts, fn($text) => !empty(trim($text)));
                    $hasImg = array_filter($imgsrcs, fn($img) => !empty(trim($img)));
                    $hasFile = array_filter($files);
    
                    if (empty($hasText) && empty($hasFile) && empty($hasImg)) {
                        $fail('Вы должны заполнить хотя бы одно текстовое поле или загрузить изображение.');
                    }
                }
            ],

            'answer1' => ['required', 'string', 'max:255'],

            'hint1_id' => 'required',
            'hint1_text' => ['nullable', 'string'],
            'hint1_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'hint1_imgsrc' => ['nullable', 'string'],
            'hint1_text' => $this->validateTextOrImage('hint1', $request),

            'task2_id' => 'required',
            'task2_text' => ['nullable', 'string'],
            'task2_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'task2_imgsrc' => ['nullable', 'string'],
            'task2_text' => $this->validateTextOrImage('task2', $request),

            'answer2' => ['required', 'string', 'max:255'],

            'hint2_id' => 'required',
            'hint2_text' => ['nullable', 'string'],
            'hint2_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'hint2_imgsrc' => ['nullable', 'string'],
            'hint2_text' => $this->validateTextOrImage('hint2', $request),
        ]);
    }

    public function validateTextOrImage($fieldPrefix, $request)
    {
        return [
            function ($attribute, $value, $fail) use ($fieldPrefix, $request) {
                $type = strpos($fieldPrefix, 'task') !== false ? 'вопроса' : 'подсказки';
                $number = substr($fieldPrefix, -1);  
    
                $message = "Необходимо указать текст {$type} {$number} или загрузить изображение.";
    
                if (empty($value) && !$request->hasFile("{$fieldPrefix}_image") && empty($request->input("{$fieldPrefix}_imgsrc"))) {
                    $fail($message);
                }
            }
        ];
    }

    private function getImageFolderPath($questSlug)
    {
        $slug = explode('-', $questSlug);
        $tripNumber = filter_var($slug[1], FILTER_SANITIZE_NUMBER_INT);
        $imageFolderPath = "/{$slug[0]}/{$slug[1]}/";

        return [$imageFolderPath, $tripNumber];
    }

    private function updateSightImage(Request $request, $sight, $validatedData, $imageFolderPath)
    {
        $imagePath = $sight->image;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $fileName = "{$validatedData['step']}.{$extension}";
            $imagePath = "{$imageFolderPath}{$fileName}";

            $image->storeAs("public/img{$imageFolderPath}", $fileName, 'public_uploads');
        }

        return $imagePath;
    }

    private function getSightUpdateData($validatedData, $imagePath)
    {
        return [
            'title' => $validatedData['title'],
            'quest_id' => $validatedData['quest_id'],
            'answer1' => mb_strtolower(trim($validatedData['answer1'])),
            'answer2' => mb_strtolower(trim($validatedData['answer2'])),
            'step' => $validatedData['step'],
            'description' => $validatedData['description'],
            'address' => $validatedData['address'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'image' => $imagePath,
        ];
    }

    private function updateTask1($validatedData, $sight, $imageFolderPath)
    {
        for ($i = 0; $i <= 2; $i++) {
            $type = $i + 1;
            $task1Image = $validatedData['task1_imgsrc'][$i] ?? null;

            if ($task1Image === '') {
                $task1Image = null;
            }

            if ($validatedData['task1_id'][$i]) {
                $task1 = Task::find($validatedData['task1_id'][$i]);
                $this->handleTaskImage($task1, $task1Image, $validatedData['task1_image'][$i] ?? null, $imageFolderPath, $validatedData['step'], "1-$type");

                $task1->update([
                    'text' => $validatedData['task1_text'][$i],
                    'image' => $task1Image,
                ]);
            } elseif ($validatedData['task1_text'][$i] || isset($validatedData['task1_image'][$i])) {
                $task1Image = Task::createTaskImage($validatedData['task1_image'][$i] ?? null, $imageFolderPath, $validatedData['step'], "1-$type");

                Task::create([
                    'quest_id' => $validatedData['quest_id'],
                    'sight_id' => $sight->id,
                    'text' => $validatedData['task1_text'][$i],
                    'image' => $task1Image,
                    'type' => 1,
                ]);
            }
        }
    }

    private function updateTask2(Request $request, $validatedData, $imageFolderPath)
    {
        $task2 = Task::find($validatedData['task2_id']);
        $task2Image = $validatedData['task2_imgsrc'] ?? null;

        if ($task2Image === '') {
            $task2Image = null;
        }

        $this->handleTaskImage($task2, $task2Image, $request->file('task2_image'), $imageFolderPath, $validatedData['step'], '2');

        $task2->update([
            'text' => $validatedData['task2_text'],
            'image' => $task2Image,
        ]);
    }

    private function updateHint(Request $request, $validatedData, $imageFolderPath, $hintKey)
    {
        $hint = Task::find($validatedData["{$hintKey}_id"]);
        $hintImage = $validatedData["{$hintKey}_imgsrc"] ?? null;

        if ($hintImage === '') {
            $hintImage = null;
        }

        $this->handleTaskImage($hint, $hintImage, $request->file("{$hintKey}_image"), $imageFolderPath, $validatedData['step'], $hintKey === 'hint1' ? '3' : '4');

        $hint->update([
            'text' => $validatedData["{$hintKey}_text"],
            'image' => $hintImage,
        ]);
    }

    private function handleTaskImage($task, &$taskImage, $uploadedImage, $imageFolderPath, $step, $suffix)
    {
        if ($task->image && !$taskImage) {
            $imageService = new ImageService();
            $imageService->deleteImage($task->image);
        }

        if ($uploadedImage) {
            $taskImage = Task::createTaskImage($uploadedImage, $imageFolderPath, $step, $suffix);
        }
    }


    
    public function destroy($id)
    {
        $sight = Sight::find($id);
        $tasks = $sight->tasks;
        $imageService = new ImageService();
        foreach ($tasks as $key => $task) {
            $imageService->deleteImage($task->image);
            $task->delete();
        }
        $sight->delete();
        $imageService->deleteImage($sight->image);

        return redirect()->route('admin.sights.index');
    }
}
