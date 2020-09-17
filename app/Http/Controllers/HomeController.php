<?php

namespace App\Http\Controllers;

use App\Category;
use Hash;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::get()->each
            ->append('amount_total')
            ->sortByDesc('amount_total')
            ->flatten();

        return view('dashboard', compact('categories'));
    }

    public function profile()
    {
        return view('users.profile');
    }

    public function updatePassword()
    {
        request()->validate([
            'old_password' => ['required', 'min:6', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail("Old Password didn't match");
                }
            },],
            'password' => ['required', 'min:6', 'confirmed', 'different:old_password'],
            'password_confirmation' => ['required', 'min:6'],
        ]);

        auth()->user()->update(['password' => Hash::make(request('password'))]);

        return back()->with('success', 'Password Updated');
    }
}
