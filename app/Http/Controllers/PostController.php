<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;



class PostController extends Controller
{
    public function getDashboard()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('dashboard', ['posts' => $posts]);
    }

    public function postCreatePost(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|max:1000'
        ]);
        $post = new Post();
        $message = 'There was an error';
        $post->body = $request['body'];

        $arr = $this->csvToArray('C:\xampp\htdocs\social-network\storage\app\public\wulgaryzmy.csv');


        //******************************PIERWSZA WERSJA CENZUROWANIA******************************

        if($this->censored($request, $arr)){
            $message = 'Censored!';
        }elseif($request->user()->posts()->save($post)){
            $message = 'Post Successfully created!';
        }

        //****************************************************************************************


        //******************************DRUGA WERSJA CENZUROWANIA*********************************

        $post->body = $this->censored2($request, $arr, 'C:\xampp\htdocs\social-network\storage\app\public\random.csv');

        if($request->user()->posts()->save($post)){
            $message = 'Post Successfully created!';
        }

        //****************************************************************************************
        return redirect()->route('dashboard')->with(['message' => $message]);
    }

    public function censored(Request $request, Array $arr)
    {
        $condition = 0;
        foreach($arr as $ar){
            if(Str::contains($request['body'], $ar)){
                $condition = 1;
            }
        }
        return $condition;
    }

    public function censored2(Request $request, Array $arr, $path)
    {
        foreach($arr as $ar){
            if(Str::contains($request['body'], $ar)){
                $request['body'] = str_replace($ar, $this->randomProduct($path), $request['body']);
            }
        }
        return $request['body'];
    }

    public function randomProduct($path)
    {
        $random = array();
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        $i = 0;
        $lines = mb_convert_encoding($lines, 'UTF-8');


        foreach($lines as $key => $value){
            $random[$key] = str_getcsv($value)[0];
            $i++;
        }

        return $random[rand(0, $i)];
    }

    public function csvToArray($path)
    {
        $csv = array();
        $lines = file($path, FILE_IGNORE_NEW_LINES);

        $lines = mb_convert_encoding($lines, 'UTF-8');

        foreach($lines as $key => $value){
            $csv[$key] = str_getcsv($value)[0];
        }
        return $csv;
    }

    public function  getDeletePost($post_id)
    {
        $post = Post::where('id', $post_id)->first();
        if(Auth::user() != $post->user){
            return redirect()->back();
        }
        $post->delete();
        return redirect()->route('dashboard')->with(['message' => 'Succesfully deleted!']);
    }

    public function postEditPost(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);
        $arr = $this->csvToArray('C:\xampp\htdocs\social-network\storage\app\public\wulgaryzmy.csv');
        $post = Post::find($request['postId']);
        if(Auth::user() != $post->user){
            return redirect()->back();
        }

        $post->body = $request['body'];

        $post->update();
        return response()->json(['new_body' => $post->body = $this->censored2($request, $arr, 'C:\xampp\htdocs\social-network\storage\app\public\random.csv')], 200);
    }

    public function postLikePost(Request $request)
    {
        $post_id = $request['postId'];
        $is_like = $request['isLike'] === 'true';
        $update = false;
        $post = Post::find($post_id);
        if (!$post) {
            return null;
        }
        $user = Auth::user();
        $like = $user->likes()->where('post_id', $post_id)->first();
        if ($like) {
            $already_like = $like->like;
            $update = true;
            if ($already_like == $is_like) {
                $like->delete();
                return null;
            }
        } else {
            $like = new Like();
        }
        $like->like = $is_like;
        $like->user_id = $user->id;
        $like->post_id = $post->id;
        if ($update) {
            $like->update();
        } else {
            $like->save();
        }
        return null;
    }
}