<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentEmulatorController extends Controller
{
    public function verify(Request $request)
    {
        usleep(500000); 

        return response()->json([
            'status' => 'purchased',
            'purchase_token' => bin2hex(random_bytes(16)),
            'order_id' => 'GPA.' . mt_rand(1000, 9999) . '-' . mt_rand(1000, 9999),
            'success' => true
        ]);
    }
}