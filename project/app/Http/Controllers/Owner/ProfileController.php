<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('owner.profile', [
            'user' => auth()->user(),
        ]);
    }
}
