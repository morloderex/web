<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Config::get('faqs');
        return view('faq', compact('faqs'));
    }
}
