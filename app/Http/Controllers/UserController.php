<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function query($id)
    {
        return view('user.query', ['id' => $id]);
    }

    public function compare($id1, $id2)
    {
        return view('user.compare', ['id1' => $id1, 'id2' => $id2]);
    }
}
