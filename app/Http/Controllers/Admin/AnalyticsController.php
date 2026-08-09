<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $totalMachines = Machine::count();

        $availableMachines = Machine::where('status', 'idle')
            ->count();

        $busyMachines = Machine::whereIn('status', ['washing', 'rinsing', 'drying'])
            ->count();

        // Order statistics
        $totalLaundry = Order::count();

        $laundryStatus = Order::select(
            'order_status as status',
            DB::raw('count(*) as total')
        )
            ->groupBy('order_status')
            ->get();

        // Daily order count
        $dailyLaundry = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
            ->groupBy('date')
            ->get();

        return view('admin.analytics', compact(
            'totalUsers',
            'totalMachines',
            'availableMachines',
            'busyMachines',
            'totalLaundry',
            'laundryStatus',
            'dailyLaundry'
        ));
    }
}
