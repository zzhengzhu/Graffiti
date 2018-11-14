<?php

namespace App\Http\Controllers;

use App\Update;
use Illuminate\Http\Request;
//You may access the authenticated user via the Auth facade
use Illuminate\Support\Facades\Auth;

class UpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('root')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $root = false;
        if (in_array($id, [1])) {
            $root = true;
        }
        $logs = Update::orderBy('created_at', 'desc')->get();
        return view('updates.index', ['root' => $root, 'logs' => $logs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('updates.create');
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
            'title' => 'required',
            'content' => 'required',
        ]);
        
        $update = new Update;
        $update->title = $request->input('title');
        $update->content = $request->input('content');
        $update->user_id = Auth::id();
        $update->user_name = Auth::user()['name'];
        $update->save();
        return redirect(route('updates.index'))->with('success', 'Logs Submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function show(Update $update)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function edit(Update $update)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Update $update)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function destroy(Update $update)
    {
        //
    }
}
