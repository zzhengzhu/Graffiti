<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Station;
//get user location
use Grimzy\LaravelMysqlSpatial\Types\Point;
//You may access the authenticated user via the Auth facade
use Illuminate\Support\Facades\Auth;
use App\User;


class StationController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.station');
    }

    public function load(Request $request) {
        $station = Station::all();
        return $station;
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
            'tag' => 'required',
            'color' => 'required',
            'icon' => 'required',
        ]);
        $point = new Point($request->input('lat'), $request->input('lng'));
        $station = new Station;
        if (Auth::id()) {
            $station->user_id = Auth::id();
            $station->user_name = Auth::user()['name'];
        } else {
            $station->user_id = 0;
            $station->user_name = 'UNKNOWN';    
        }
        $station->location = $point;
        $station->content = $request->input('content');
        $station->link = $request->input('link');
        $station->category = $request->input('tag');
        $station->color = $request->input('color');
        $station->icon = $request->input('icon');
        $station->pointto_id = $request->input('pointto');
        $station->save();
        return redirect(route('stations.index'))->with('success', 'Station posted');
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
        //
    }
}
