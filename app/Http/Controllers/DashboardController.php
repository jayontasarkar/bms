<?php

namespace App\Http\Controllers;

use App\Models\{Purchase, Sales};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Purchase::duePayments()->take(15);
        $sales     = Sales::duePayments()->take(15);

        return view('dashboard', compact('purchases', 'sales'));
    }
}
