<?php

namespace App\Http\Controllers;

use App\Models\Inventories;
use App\Models\Purchases;
use App\Models\Sales;

class DashboardController extends Controller
{
    public function index()
    {
        $inventories_count = Inventories::whereNull('deleted_at')->count();
        $sales_count       = Sales::whereNull('deleted_at')->count();
        $purchases_count   = Purchases::whereNull('deleted_at')->count();

        return view('dashboard', compact('inventories_count', 'sales_count', 'purchases_count'));
    }
}
