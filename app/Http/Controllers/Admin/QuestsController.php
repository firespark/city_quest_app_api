<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Quest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestsController extends Controller
{
    public function index()
    {
        $perpage = 20;
        $quests = Quest::paginate($perpage);
        return view('admin.quests.index', compact(
            'quests',
        ));
    }

    public function city($city_id)
    {
        $perpage = 20;
        $quests = Quest::where('city_id',$city_id)->paginate($perpage);
        $city = City::select('id','title')->where('id',$city_id)->first();
        return view('admin.quests.index', compact(
            'quests',
            'city',
        ));
    }
    
    public function create()
    {
        $cities = City::pluck('id', 'title')->all();
        return view('admin.quests.create', compact(
            'cities',
        ));
    }

    
    public function store(Request $request)
    {
/*         $this->validate($request, [
            'title' => 'required',
            'image' => 'nullable|image',
        ]);

        $quest = Quest::add($request->all());
        $quest->uploadImage($request->file('image'));
        $quest->setCity($request->get('city_id'));
        $quest->toggleStatus($request->get('status'));

        return redirect()->route('admin.quests.edit', $quest->id); */
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cities,slug',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'city_id' => 'nullable|integer',
            'featured' => 'nullable',
            'finish' => 'nullable|string',
            'start_point' => 'nullable|string|max:255',
            'end_point' => 'nullable|string|max:255',
            'skips_number' => 'nullable|integer',
            'hints_number' => 'nullable|integer',
        ]);
    
        // Определяем путь для загрузки картинки
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $slug = explode('-', $validatedData['slug']);
            $tripNumber = filter_var($slug[1], FILTER_SANITIZE_NUMBER_INT);
            $imagePath = "/{$slug[0]}/{$slug[1]}/quest{$tripNumber}.{$extension}";
    
            // Сохраняем файл в public/img/slug
            $image->storeAs("public/img/{$slug[0]}/{$slug[1]}/", "quest{$tripNumber}.{$extension}", 'public_uploads');
        }
    
        // Сохраняем данные в базу
        $quest = Quest::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
            'city_id' => $validatedData['city_id'] ?? 0,
            'featured' => $request->has('featured'),
            'finish' => $validatedData['finish'],
            'start_point' => $validatedData['start_point'],
            'end_point' => $validatedData['end_point'],
            'skips_number' => $validatedData['skips_number'],
            'hints_number' => $validatedData['hints_number'],
        ]);
    
        // Перенаправляем с успешным сообщением
        return redirect()->route('admin.quests.edit', $quest->id)->with('success', 'Квест успешно отредактирован!');
    }

    
    public function edit($id)
    {
        $quest = Quest::find($id);
        $cities = City::pluck('id', 'title')->all();
        $city = City::select('id','title')->where('id', $quest->city_id)->first();

        return view('admin.quests.edit', compact(
            'quest',
            'cities',
            'city',
        ));
    }

    
    public function update(Request $request, $id)
    {
        
        /* $this->validate($request, [
            'title' => 'required',
            'image' => 'nullable|image',
        ]);

        

        $quest->edit($request->all());
        $quest->uploadImage($request->file('image'));
        $quest->setCity($request->get('city_id'));
        $quest->toggleStatus($request->get('status'));

        return redirect()->route('admin.quests.edit', $id); */
        $quest = Quest::find($id);
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cities,slug',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'city_id' => 'nullable|integer',
            'featured' => 'nullable',
            'finish' => 'nullable|string',
            'start_point' => 'nullable|string|max:255',
            'end_point' => 'nullable|string|max:255',
            'skips_number' => 'nullable|integer',
            'hints_number' => 'nullable|integer',
        ]);
    
        // Определяем путь для загрузки картинки
        $imagePath = $quest->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $slug = explode('-', $validatedData['slug']);
            $tripNumber = filter_var($slug[1], FILTER_SANITIZE_NUMBER_INT);
            $imagePath = "/{$slug[0]}/{$slug[1]}/quest{$tripNumber}.{$extension}";
    
            // Сохраняем файл в public/img/slug
            $image->storeAs("public/img/{$slug[0]}/{$slug[1]}/", "quest{$tripNumber}.{$extension}", 'public_uploads');
        }
    
        // Сохраняем данные в базу
        $quest->update([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
            'city_id' => $validatedData['city_id'] ?? 0,
            'featured' => $request->has('featured'),
            'finish' => $validatedData['finish'],
            'start_point' => $validatedData['start_point'],
            'end_point' => $validatedData['end_point'],
            'skips_number' => $validatedData['skips_number'],
            'hints_number' => $validatedData['hints_number'],
        ]);
    
        // Перенаправляем с успешным сообщением
        return redirect()->route('admin.quests.edit', $id)->with('success', 'Квест успешно отредактирован!');
    }

    
    public function destroy($id)
    {
        Quest::find($id)->remove();


        return redirect()->route('admin.quests.index');
    }
}
