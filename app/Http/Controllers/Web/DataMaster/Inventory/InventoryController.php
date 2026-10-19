<?php

namespace App\Http\Controllers\Web\DataMaster\Inventory;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        $title = 'Inventories';

        return view('data_master.inventories.'.__FUNCTION__, compact('title'));
    }
}
