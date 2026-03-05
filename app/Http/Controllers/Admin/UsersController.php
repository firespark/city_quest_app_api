<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Quest;
use App\Models\UserQuest;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;



class UsersController extends Controller
{

    public function index()
    {
        $perpage = 30;
        $users = User::with(['purchasedQuests', 'games'])->paginate($perpage);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:0,1',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::add($request->all());
        $user->role = $request->get('role');
        $user->save();

        return redirect()->route('admin.users.edit', $user->id)->with('status', 'Пользователь успешно создан');
    }


    public function edit($id)
    {
        $user = User::find($id);
        $quests = Quest::all();
        $userQuests = UserQuest::where('user_id', $id)->pluck('quest_id')->toArray();

        return view('admin.users.edit', compact('user', 'quests', 'userQuests'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required',
            'role' => 'required|in:0,1',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        $this->validate($request, $rules);

        $user->name = $request->get('name');
        $user->role = $request->get('role');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        $user->save();

        UserQuest::where('user_id', $id)->delete();
        if ($request->has('quests')) {
            foreach ($request->input('quests') as $quest_id) {
                UserQuest::create([
                    'user_id' => $id,
                    'quest_id' => $quest_id
                ]);
            }
        }

        return redirect()->route('admin.users.edit', $id)->with('status', 'Данные обновлены');
    }


    public function destroy($id)
    {
        User::find($id)->remove();


        return redirect()->route('admin.users.index');
    }
}
