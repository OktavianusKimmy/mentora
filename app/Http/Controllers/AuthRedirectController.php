<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthRedirectController extends Controller
{
    public function handle()
    {
        if (auth()->check()) {
            return redirect('/');
        } else {
            return redirect('/login');
        }
    }
}
