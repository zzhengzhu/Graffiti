<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pinpoint;
//get user location
use Grimzy\LaravelMysqlSpatial\Types\Point;
//You may access the authenticated user via the Auth facade
use Illuminate\Support\Facades\Auth;
use App\User;


class PinpointController extends Controller
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
        //
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
            'radius' => 'required',
            'content' => 'required_without:link',
            'link' => 'required_without:content',
        ]);
        $user = User::find(Auth::id());
        if ($user->energy < 50) {
            return redirect(route('pages.index'))->with('error', 'Not Enough Energy');
        } else {
            $user->energy -= 50;
            $user->exp += 50;
            $user->save();

            $point = new Point($request->input('lat'), $request->input('lng'));
            $pinpoint = new Pinpoint;
            $pinpoint->user_id = Auth::id();
            $pinpoint->user_name = Auth::user()['name'];
            $pinpoint->location = $point;
            $pinpoint->content = $request->input('content');
            $pinpoint->link = $request->input('link');
            $pinpoint->radius = $request->input('radius');
            $pinpoint->save();
            return redirect(route('pages.index'))->with('success', 'Pinpoint Submitted');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delpinpoint = Pinpoint::where('id', $id)->first();
        if ($delpinpoint == NULL) {
            return redirect(route('posts.index'))->with('error', 'Access Denied');
        }

        if($delpinpoint->user_id == Auth::id()) {
            $delpinpoint->delete();
            return redirect(route('posts.index'))->with('success', 'Pinpoint Deleted');
        } else {
            return redirect(route('posts.index'))->with('error', 'Access Denied');
        }
    }
}
