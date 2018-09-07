<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileFormRequest;
use App\Http\Requests\ChangePasswordFormRequest;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index(Request $request)
    {
    	$profile = $request->user();

    	return view('profile.index', compact('profile'));
    }

    public function update(ProfileFormRequest $request)
    {
    	$request->user()->update($request->except('company'));

    	return back()->withFlash('Profile info. updated');
    }

    public function getPasswordForm(Request $request)
    {
    	return view('profile.password.index', [
    		'profile' => $request->user()
    	]);
    }

    public function postPasswordForm(ChangePasswordFormRequest $request)
    {
    	$request->user()->update($request->only('password'));

    	return back()->withFlash('Your login password changed.');
    }
}
