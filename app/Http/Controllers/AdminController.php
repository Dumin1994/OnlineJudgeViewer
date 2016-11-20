<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index($accessKey)
    {
        return redirect()->route('class-index', ['AccessKey' => $accessKey]);
    }
}
