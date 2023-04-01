<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;

class PagesController extends Controller
{
    public function index()
    {
        return view('index')
            ->with('posts', Post::orderBy('updated_at', 'DESC')->get());
    }
}
