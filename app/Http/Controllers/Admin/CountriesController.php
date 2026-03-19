<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        $countries = Country::paginate(20);
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug'  => 'required|string|max:255|unique:countries,slug',
        ]);

        Country::create([
            'title'     => $request->title,
            'slug'      => $request->slug,
            'published' => $request->has('published')
        ]);

        return redirect()->route('admin.countries.index')->with('success', 'Страна добавлена');
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'slug'  => 'required|string|max:255|unique:countries,slug,' . $id,
        ]);

        $country->update([
            'title'     => $request->title,
            'slug'      => $request->slug,
            'published' => $request->has('published')
        ]);

        return redirect()->route('admin.countries.index')->with('success', 'Страна обновлена');
    }
}