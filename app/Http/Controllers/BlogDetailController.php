<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogDetailController extends Controller
{
    function showblogdetail()
    {
        return view('blog-detail.index');
    }
}
