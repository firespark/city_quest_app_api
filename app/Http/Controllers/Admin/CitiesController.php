<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index()
    {
        $perpage = 20;
        $cities = City::paginate($perpage);


        return view('admin.cities.index', compact(
            'cities',
        ));
    }

    public function create()
    {
        $cities = City::select('id','title')->orderBy('title', 'ASC')->get();
        return view('admin.cities.create', compact(
            'cities',
        ));
    }
/* 
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);
        City::create($request->all());
        return redirect()->route('admin.cities.index');
    } */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cities,slug',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'featured' => 'nullable',
            'parent_id' => 'nullable|integer',
        ]);
    
        // Определяем путь для загрузки картинки
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $slug = $validatedData['slug'];
            $extension = $image->getClientOriginalExtension();
            $imagePath = "/{$slug}/{$slug}.{$extension}";
    
            // Сохраняем файл в public/img/slug
            $image->storeAs("public/img/{$slug}", "{$slug}.{$extension}", 'public_uploads');
        }
    
        // Сохраняем данные в базу
        $city = City::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
            'featured' => $request->has('featured'),
            'parent_id' => $validatedData['parent_id'] ?? 0,
        ]);
    
        // Перенаправляем с успешным сообщением
        return redirect()->route('admin.cities.index')->with('success', 'Город успешно добавлен!');
    }

    public function edit($id)
    {
        $city = City::find($id);
        $cities = City::select('id','title')->orderBy('title', 'ASC')->get();

        return view('admin.cities.edit', compact(
            'city',
            'cities',
        ));
    }

    public function update(Request $request, $id)
    {

        $city = City::find($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cities,slug,' . $city->id,
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'featured' => 'nullable',
            'parent_id' => 'nullable|integer',
        ]);
    
        // Определяем путь для загрузки картинки
        $imagePath = $city->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $slug = $validatedData['slug'];
            $extension = $image->getClientOriginalExtension();
            $imagePath = "/{$slug}/{$slug}.{$extension}";
    
            // Сохраняем файл в public/img/slug
            $image->storeAs("public/img/{$slug}", "{$slug}.{$extension}", 'public_uploads');
        }
        // Сохраняем данные в базу
        $city->update([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
            'featured' => $request->has('featured'),
            'parent_id' => $validatedData['parent_id'] ?? 0,
        ]);

        return redirect()->route('admin.cities.edit', $id)->with('success', 'Город успешно отредактирован!');
    }

/*     public function destroy($id)
    {
        City::find($id)->delete();


        return redirect()->route('admin.cities.index');
    } */
}
