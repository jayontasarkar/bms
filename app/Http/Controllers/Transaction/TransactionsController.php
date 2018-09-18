<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function destroy(Transaction $transaction)
    {
    	$transaction->forceDelete();

    	session()->flash('flash', $msg = 'Opening balance removed');

    	return response()->json(['msg' => $msg]);
    }
}
