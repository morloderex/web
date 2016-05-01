<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Category,
    App\Models\Post,
    App\Models\User;

class WelcomeController extends Controller
{
    public function getIndex() {

    	$changelogs = Category::latestChangelog();

    	return view('welcome', compact('changelogs'));
    }
}
