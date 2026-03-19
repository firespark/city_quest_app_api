<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
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
        $cities = City::select('id', 'title')->orderBy('title', 'ASC')->get();
        $countries = Country::orderBy('title', 'ASC')->get();
        return view('admin.cities.create', compact('cities', 'countries'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'country_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cities,slug',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'parent_id' => 'nullable|integer',
            'map' => 'nullable|string|max:512',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $slug = $validatedData['slug'];
            $extension = $image->getClientOriginalExtension();
            $imagePath = "/{$slug}/{$slug}.{$extension}";

            $image->storeAs("public/img/{$slug}", "{$slug}.{$extension}", 'public_uploads');
        }

        $city = City::create([
            'country_id' => $request->country_id,
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
            'featured' => $request->has('featured'),
            'published' => $request->has('published'),
            'parent_id' => $validatedData['parent_id'] ?? 0,
            'map' => $validatedData['map'],
        ]);

        return redirect()->route('admin.cities.index')->with('success', 'Город успешно добавлен!');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $cities = City::select('id', 'title')->where('id', '!=', $id)->orderBy('title', 'ASC')->get();
        $countries = Country::orderBy('title', 'ASC')->get();

        return view('admin.cities.edit', compact('city', 'cities', 'countries'));
    }

    public function update(Request $request, $id)
    {

        $city = City::find($id);

        $validatedData = $request->validate([
            'country_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cities,slug,' . $city->id,
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'parent_id' => 'nullable|integer',
            'map' => 'nullable|string|max:512',
        ]);

        $imagePath = $city->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $slug = $validatedData['slug'];
            $extension = $image->getClientOriginalExtension();
            $imagePath = "/{$slug}/{$slug}.{$extension}";

            $image->storeAs("public/img/{$slug}", "{$slug}.{$extension}", 'public_uploads');
        }

        $city->update([
            'country_id' => $request->country_id,
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
            'featured' => $request->has('featured'),
            'published' => $request->has('published'),
            'parent_id' => $validatedData['parent_id'] ?? 0,
            'map' => $validatedData['map'],
        ]);

        return redirect()->route('admin.cities.edit', $id)->with('success', 'Город успешно отредактирован!');
    }

    /*     public function destroy($id)
        {
            City::find($id)->delete();


            return redirect()->route('admin.cities.index');
        } */
}
