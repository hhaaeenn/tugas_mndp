<?php

namespace App\Http\Controllers\Web\Transaction;

use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    public function index()
    {
        $title = 'Sales';

        return view('transactions.sales.'.__FUNCTION__, compact('title'));
    }
}
