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
        $sights = Sight::with(['quest:id,title'])->where('quest_id', $quest_id)->orderBy('step', 'ASC')->paginate($perpage);
        $quest = Quest::select('id', 'title')->where('id', $quest_id)->first();

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
            'quest_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $quest = Quest::find($value);
                    if (!$quest) {
                        $fail('Выбранный квест не существует.');
                    }
                },
            ],
            'image' => 'nullable|string',
            'description' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',

            'task1_text' => ['nullable', 'array'],
            'task1_text.*' => ['nullable', 'string'],
            'task1_image' => ['nullable', 'array'],
            'task1_image.*' => ['nullable', 'string'],
            'task1_text' => [
                function ($attribute, $value, $fail) {
                    $texts = request()->input('task1_text', []);
                    $images = request()->input('task1_image', []);
                    $hasText = array_filter($texts, fn($text) => !empty(trim($text)));
                    $hasImg = array_filter($images, fn($img) => !empty(trim($img)));

                    if (empty($hasText) && empty($hasImg)) {
                        $fail('Вы должны заполнить хотя бы одно текстовое поле или указать ссылку на изображение в Вопросе 1.');
                    }
                }
            ],

            'answer1' => ['required', 'string', 'max:255'],

            'hint1_text' => ['nullable', 'string'],
            'hint1_image' => ['nullable', 'string'],
            'hint1_text' => $this->validateTextOrImage('hint1', $request),

            'task2_text' => ['nullable', 'string'],
            'task2_image' => ['nullable', 'string'],
            'task2_text' => $this->validateTextOrImage('task2', $request),

            'answer2' => ['required', 'string', 'max:255'],

            'hint2_text' => ['nullable', 'string'],
            'hint2_image' => ['nullable', 'string'],
            'hint2_text' => $this->validateTextOrImage('hint2', $request),
        ]);

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
            'image' => $validatedData['image'] ?? null,
        ]);

        // Task 1

        for ($i = 0; $i <= 2; $i++) {
            $taskText = $validatedData['task1_text'][$i] ?? null;
            $taskImage = $validatedData['task1_image'][$i] ?? null;

            if (!empty($taskText) || !empty($taskImage)) {
                Task::create([
                    'quest_id' => $validatedData['quest_id'],
                    'sight_id' => $sight->id,
                    'text' => $taskText,
                    'image' => $taskImage,
                    'type' => 1,
                ]);
            }
        }

        // Task 2
        Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['task2_text'],
            'image' => $validatedData['task2_image'] ?? null,
            'type' => 2,
        ]);

        // Hint 1
        Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['hint1_text'],
            'image' => $validatedData['hint1_image'] ?? null,
            'type' => 3,
        ]);

        // Hint 2
        Task::create([
            'quest_id' => $validatedData['quest_id'],
            'sight_id' => $sight->id,
            'text' => $validatedData['hint2_text'],
            'image' => $validatedData['hint2_image'] ?? null,
            'type' => 4,
        ]);

        return redirect()->route('admin.sights.edit', $sight->id)->with('success', 'Достопримечательность успешно добавлена!');
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

        $fillValue = ['id' => null, 'text' => null, 'image' => null];

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

        $sight->update([
            'title' => $validatedData['title'],
            'quest_id' => $validatedData['quest_id'],
            'answer1' => mb_strtolower(trim($validatedData['answer1'])),
            'answer2' => mb_strtolower(trim($validatedData['answer2'])),
            'step' => $validatedData['step'],
            'description' => $validatedData['description'],
            'address' => $validatedData['address'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'image' => $validatedData['image'],
        ]);

        $this->updateTask1($validatedData, $sight);
        $this->updateTask2($validatedData);
        $this->updateHint($validatedData, 'hint1');
        $this->updateHint($validatedData, 'hint2');

        return redirect()->route('admin.sights.edit', $sight->id)->with('success', 'Достопримечательность успешно отредактирована!');
    }

    private function validateUpdateRequest(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'step' => 'required',
            'quest_id' => 'required',
            'image' => 'nullable|string',
            'description' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',

            'task1_id' => ['nullable', 'array'],
            'task1_text' => ['nullable', 'array'],
            'task1_text.*' => ['nullable', 'string'],
            'task1_image' => ['nullable', 'array'],
            'task1_image.*' => ['nullable', 'string'],
            'task1_text' => [
                function ($attribute, $value, $fail) {
                    $texts = request()->input('task1_text', []);
                    $images = request()->input('task1_image', []);
                    $hasText = array_filter($texts, fn($text) => !empty(trim($text)));
                    $hasImg = array_filter($images, fn($img) => !empty(trim($img)));

                    if (empty($hasText) && empty($hasImg)) {
                        $fail('Вы должны заполнить хотя бы одно текстовое поле или указать ссылку на изображение.');
                    }
                }
            ],

            'answer1' => ['required', 'string', 'max:255'],

            'hint1_id' => 'required',
            'hint1_text' => ['nullable', 'string'],
            'hint1_image' => ['nullable', 'string'],
            'hint1_text' => $this->validateTextOrImage('hint1', $request),

            'task2_id' => 'required',
            'task2_text' => ['nullable', 'string'],
            'task2_image' => ['nullable', 'string'],
            'task2_text' => $this->validateTextOrImage('task2', $request),

            'answer2' => ['required', 'string', 'max:255'],

            'hint2_id' => 'required',
            'hint2_text' => ['nullable', 'string'],
            'hint2_image' => ['nullable', 'string'],
            'hint2_text' => $this->validateTextOrImage('hint2', $request),
        ]);
    }

    public function validateTextOrImage($fieldPrefix, $request)
    {
        return [
            function ($attribute, $value, $fail) use ($fieldPrefix, $request) {
                $type = strpos($fieldPrefix, 'task') !== false ? 'вопроса' : 'подсказки';
                $number = substr($fieldPrefix, -1);

                $message = "Необходимо указать текст {$type} {$number} или указать ссылку на изображение.";

                if (empty($value) && empty($request->input("{$fieldPrefix}_image"))) {
                    $fail($message);
                }
            }
        ];
    }

    private function updateTask1($validatedData, $sight)
    {
        for ($i = 0; $i <= 2; $i++) {
            $taskImage = $validatedData['task1_image'][$i] ?? null;

            if ($validatedData['task1_id'][$i]) {
                $task1 = Task::find($validatedData['task1_id'][$i]);
                $task1->update([
                    'text' => $validatedData['task1_text'][$i],
                    'image' => $taskImage,
                ]);
            } elseif (!empty($validatedData['task1_text'][$i]) || !empty($taskImage)) {
                Task::create([
                    'quest_id' => $validatedData['quest_id'],
                    'sight_id' => $sight->id,
                    'text' => $validatedData['task1_text'][$i],
                    'image' => $taskImage,
                    'type' => 1,
                ]);
            }
        }
    }

    private function updateTask2($validatedData)
    {
        $task2 = Task::find($validatedData['task2_id']);
        $task2->update([
            'text' => $validatedData['task2_text'],
            'image' => $validatedData['task2_image'] ?? null,
        ]);
    }

    private function updateHint($validatedData, $hintKey)
    {
        $hint = Task::find($validatedData["{$hintKey}_id"]);
        $hint->update([
            'text' => $validatedData["{$hintKey}_text"],
            'image' => $validatedData["{$hintKey}_image"] ?? null,
        ]);
    }


    public function destroy($id)
    {
        $sight = Sight::find($id);
        $tasks = $sight->tasks;
        foreach ($tasks as $task) {
            $task->delete();
        }
        $sight->delete();

        return redirect()->route('admin.sights.index');
    }

    // For now i remove these methods - not comfortable to upload image, better to just set the image path
    /*
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
    */
}
