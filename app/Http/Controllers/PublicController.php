<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index() {
        return view('layouts.frontend');
    }

    public function articleDetail($slug) {
        return view('layouts.frontend');
    }

    public function articleList() {
        return view('layouts.frontend');
    }
    public function categoryDetailList() {
        return view('layouts.frontend');
    }
    public function categoryList() {
        return view('layouts.frontend');
    }
}
