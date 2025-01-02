<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function index()
    {
        $users = User::all();


        return view('admin.users.index', , compact(
            'users',
        ));
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
            'password' => 'required',
        ]);
        $user = User::add($request->all());
        return redirect()->route('admin.users.edit', $user->id);
    }

    
    public function edit($id)
    {
        $user = User::find($id);

        return view('admin.users.edit', compact(
            'user',
        ));
    }

    
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id)
            ],
        ]);

        $user = User::find($id);
        
        $user->edit($request->all());
        return redirect()->route('admin.users.edit', $id);
    }

    
    public function destroy($id)
    {
        User::find($id)->remove();


        return redirect()->route('admin.users.index');
    }
}
