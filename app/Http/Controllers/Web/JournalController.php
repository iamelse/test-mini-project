<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class JournalController extends Controller
{
    public function index()
    {
        return view('pages.journals.index');
    }
}