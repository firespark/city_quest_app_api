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
        $quests = Quest::orderBy('id', 'desc')->paginate($perpage);
        return view('admin.quests.index', compact('quests'));
    }

    public function city($city_id)
    {
        $perpage = 20;
        $city = City::findOrFail($city_id);

        $quests = Quest::where('city_id', $city_id)
            ->orderBy('order_number', 'asc')
            ->paginate($perpage);

        return view('admin.quests.index', compact('quests', 'city'));
    }

    public function create()
    {
        $cities = City::pluck('id', 'title')->all();
        return view('admin.quests.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:quests,slug',
            'order_number' => 'nullable|integer',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'city_id' => 'nullable|integer',
            'finish' => 'nullable|string',
            'start_point' => 'nullable|string|max:255',
            'end_point' => 'nullable|string|max:255',
            'skips_number' => 'nullable|integer',
            'hints_number' => 'nullable|integer',
        ]);

        $quest = Quest::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'order_number' => $validated['order_number'] ?? 0,
            'content' => $validated['content'],
            'image' => $validated['image'],
            'city_id' => $validated['city_id'] ?? 0,
            'finish' => $validated['finish'],
            'start_point' => $validated['start_point'],
            'end_point' => $validated['end_point'],
            'skips_number' => $validated['skips_number'] ?? 3,
            'hints_number' => $validated['hints_number'] ?? 3,
            'featured' => $request->has('featured'),
            'published' => $request->has('published'),
            'paid' => $request->has('paid'),
        ]);

        return redirect()->route('admin.quests.edit', $quest->id)->with('success', 'Квест успешно создан!');
    }

    public function edit($id)
    {
        $quest = Quest::findOrFail($id);
        $cities = City::pluck('id', 'title')->all();
        $city = City::find($quest->city_id);

        return view('admin.quests.edit', compact('quest', 'cities', 'city'));
    }

    public function update(Request $request, $id)
    {
        $quest = Quest::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:quests,slug,' . $id,
            'order_number' => 'nullable|integer',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'city_id' => 'nullable|integer',
            'finish' => 'nullable|string',
            'start_point' => 'nullable|string|max:255',
            'end_point' => 'nullable|string|max:255',
            'skips_number' => 'nullable|integer',
            'hints_number' => 'nullable|integer',
        ]);

        $quest->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'order_number' => $validated['order_number'] ?? 0,
            'content' => $validated['content'],
            'image' => $validated['image'],
            'city_id' => $validated['city_id'] ?? 0,
            'finish' => $validated['finish'],
            'start_point' => $validated['start_point'],
            'end_point' => $validated['end_point'],
            'skips_number' => $validated['skips_number'] ?? 0,
            'hints_number' => $validated['hints_number'] ?? 0,
            'featured' => $request->has('featured'),
            'published' => $request->has('published'),
            'paid' => $request->has('paid'),
        ]);

        return redirect()->route('admin.quests.edit', $id)->with('success', 'Квест успешно обновлен!');
    }

    public function destroy($id)
    {
        $quest = Quest::findOrFail($id);
        $quest->delete();

        return redirect()->route('admin.quests.index');
    }
}