<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function index()
    {
        $perpage = 30;
        $games = Game::paginate($perpage);

        return view('admin.games.index', compact(
            'games',
        ));
    }
    public function user($user_id)
    {
        $perpage = 30;
        $games = Game::where('user_id', $user_id)->paginate($perpage);

        return view('admin.games.index', compact(
            'games',
        ));
    }

    public function destroy($id)
    {
        Game::find($id)->delete();


        return redirect('/admin/games');
    }
}
