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

    	// Popular categories
    	$categories = Category::popular();

    	// Popular posts
    	$posts = Post::popular(['id', 'title', 'description']);

    	// number of users
    	$users = User::count();

    	return view('welcome', compact('categories', 'posts', 'users'));
    }
}
