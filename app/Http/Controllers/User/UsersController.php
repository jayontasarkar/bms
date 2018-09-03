<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
    	$users = User::orderBy('name')->get();
    	return view('users.index', compact('users'));
    }

    public function store(UsersFormRequest $request)
    {
    	$user = User::create($request->all());

    	session()->flash('flash', $msg = 'User created successfully');
    	return response()->json(['msg' => $msg]);
    }

    public function toggleStatus(User $user)
    {
    	$user->update([
    		'status' => $user->status ? false : true
    	]);

    	return back()
    		->withFlash('User status updated successfully');
    }
}
