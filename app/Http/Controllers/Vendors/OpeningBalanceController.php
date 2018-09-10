<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class OpeningBalanceController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function update(Request $request, Purchase $purchase)
    {
    	$purchase->update($request->all());

		session()->flash('flash', $msg = 'Opening balance updated for outlet');
    	return response()->json(['msg' => $msg]);    	
    }
}
