<?php

namespace App\Http\Controllers;

use App\Models\Forum\Thread;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Forum\Category,
    App\Models\Forum\Post,
    App\Models\User;

class WelcomeController extends Controller
{
    public function getIndex() {

    	$changelogs = Thread::latestChangelog();

    	return view('welcome', compact('changelogs'));
    }
}
