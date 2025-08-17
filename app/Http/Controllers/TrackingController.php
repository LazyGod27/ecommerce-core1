<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with(['tracking'])
            ->where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('tracking', compact('orders'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);

        $tracking = Tracking::where('tracking_number', $request->tracking_number)->first();
        
        if (!$tracking) {
            return back()->with('error', 'Tracking number not found');
        }

        return view('tracking', compact('tracking'));
    }
}
