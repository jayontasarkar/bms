<?php

namespace App\Http\Controllers\Outlet;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Sales;
use Illuminate\Http\Request;

class OpeningBalanceController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function store(Request $request, Outlet $outlet)
    {
    	$request->validate([
            'amount' => 'required|min:1',
    		'vendor_id' => 'required|exists:vendors,id'
    	]);

    	$outlet->transactions()->create($request->all());

    	session()->flash('flash', $msg = 'Opening balance created for outlet');
    	return response()->json(['msg' => $msg]);
    }

    public function update(Request $request, Sales $sales)
    {
    	$sales->update($request->all());

		session()->flash('flash', $msg = 'Opening balance updated for outlet');
    	return response()->json(['msg' => $msg]);    	
    }
}
