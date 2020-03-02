<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    public function postCreatePost(Request $request)
    {
        // Validation
        $post = new Post();
        $post->body = $request['body'];
        $request->user()->posts()->save($post);
        return redirect()->route('dashboard');
    }
}