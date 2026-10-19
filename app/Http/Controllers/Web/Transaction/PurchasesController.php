<?php

namespace App\Http\Controllers\Web\Transaction;

use App\Http\Controllers\Controller;

class PurchasesController extends Controller
{
    public function index()
    {
        $title = 'Purchases';

        return view('transactions.purchases.'.__FUNCTION__, compact('title'));
    }
}
