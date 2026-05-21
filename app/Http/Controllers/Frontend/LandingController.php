<?php
// app/Http/Controllers/Frontend/LandingController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function index()
    {
        return view('public.home');
    }
}