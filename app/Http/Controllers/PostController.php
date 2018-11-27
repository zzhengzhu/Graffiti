<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
//get user location
use Grimzy\LaravelMysqlSpatial\Types\Point;
//You may access the authenticated user via the Auth facade
use Illuminate\Support\Facades\Auth;
use App\User;
//only use in index
use App\Pinpoint;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('verified');
    }

    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->get();
        $pinpoints = Pinpoint::where('user_id', Auth::id())->get();
        return view('posts.index', ['posts' => $posts, 'pinpoints' => $pinpoints]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'lat' => 'required',
            'lng' => 'required',
            'content' => 'required_without:link',
            'link' => 'nullable|active_url',
        ]);
        $user = User::find(Auth::id());
        if ($user->energy < 100) {
            return redirect(route('pages.index'))->with('error', 'Not Enough Energy');
        } else {
            $user->energy -= 100;
            $user->exp += 50;
            $user->save();

            $point = new Point($request->input('lat'), $request->input('lng'));
            $post = new Post;
            $post->user_id = Auth::id();
            $post->user_name = Auth::user()['name'];
            $post->location = $point;
            if (in_array(Auth::id(), [1])) {
                $post->type = 'admin';
            }
            $post->pointto_id = $request->input('pointto');
            $post->content = $request->input('content');
            $post->link = $request->input('link');
            $post->save();
            return redirect(route('pages.index'))->with('success', 'Post Submitted');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //->get() does not work, without first does not work
        $delpost = Post::where('id', $id)->first();
        if ($delpost == NULL) {
            return redirect(route('posts.index'))->with('error', 'Access Denied');
        }

        if($delpost->user_id == Auth::id()) {
            $delpost->delete();
            return redirect(route('posts.index'))->with('success', 'Post Deleted');
        } else {
            return redirect(route('posts.index'))->with('error', 'Access Denied');
        }
    }
}
