<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    public function index()
    {
        $subscribers = Subscription::all();


        return view('admin.subscribers.index', compact(
            'subscribers',
        ));
    }

    public function destroy($id)
    {
        Subscription::find($id)->delete();


        return redirect('/admin/subscribers');
    }
}
