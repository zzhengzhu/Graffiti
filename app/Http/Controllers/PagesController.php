<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//get posts from model
use App\Post;
//get pinpoints from model
use App\Pinpoint;
//get user location
use Grimzy\LaravelMysqlSpatial\Types\Point;
//You may access the authenticated user via the Auth facade
use Illuminate\Support\Facades\Auth;
use App\User;

class PagesController extends Controller
{
    public function index() {
        $user = User::find(Auth::id());
        return view('pages.index', ['energy' => $user->energy, 'exp' => $user->exp]);
    }


    public function info() {
        return view('pages.info');
    }

    public function loadposts(Request $request) {
        //->with('error', 'Cannot Access Location Service');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $point =new Point($lat, $lng);


        function searchpo($post, &$extrasid, &$ppids) {
            if (is_null($post->pointto_id)) {
                $extrasid[] = $post->id;
            } else {
                $pointtoid = $post->pointto_id;
                if (strpos($pointtoid,'pp') !== FALSE) {
                    $ppids[] = str_replace("pp","",$pointtoid);
                } else if (strpos($pointtoid,'po') !== FALSE) {
                    $nextid = str_replace("po","",$pointtoid);
                    //may push the post's id to a list here
                    $nextpost = Post::find($nextid);
                    searchpo($nextpost, $extrasid, $ppids);
                }
            }
        }
        
        $posts = Post::whereRaw("st_distance_sphere(location, ST_GeomFromText(?)) <= radius", [
            $point->toWkt(),
        ])->get();
        $pinpoints = Pinpoint::whereRaw("st_distance_sphere(location, ST_GeomFromText(?)) <= radius", [
            $point->toWkt(),
        ])->get();
        $extrasid = array();
        $ppids = array();
        foreach($pinpoints as $pinpoint) {
            $ppids[] = $pinpoint->id;
        }
        foreach ($posts as $post) {
            searchpo($post, $extrasid, $ppids);
        }
        
        $ppids = array_unique($ppids);
        $allposts = Post::all();
        
        foreach ($ppids as $ppid) {
            $ppid = 'pp' . $ppid;
            foreach ($allposts as $post) {
                if ($ppid == $post->pointto_id) {
                    $extrasid[] = $post->id;
                }
            }
        }
        
        //$extrasid = array_unique($extrasid); //not necessary    

        $queue = new \Ds\Queue($extrasid);
      
        while (!$queue->isEmpty()) {
            $popid = 'po'. $queue->pop();
            foreach ($allposts as $post) {
                if ($popid == $post->pointto_id) {
                    $extrasid[] = $post->id;
                    $queue->push($post->id);
                }
            }
        }
        
        $all = Post::whereIn('id', $extrasid)->get();

        //$array = json_decode(json_encode($posts));
        
        return $all;
    }

    public function loadpinpoints(Request $request) {
        //->with('error', 'Cannot Access Location Service');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $point =new Point($lat, $lng);
        
        //$posts = Pinpoint::distanceSphere('location', $point, 'radius')->get();
        
        $pinpoints = Pinpoint::whereRaw("st_distance_sphere(location, ST_GeomFromText(?)) <= radius", [
            $point->toWkt(),
        ]);
        $posts = Post::whereRaw("st_distance_sphere(location, ST_GeomFromText(?)) <= radius", [
            $point->toWkt(),
        ])->get();

        function searchpp($post, &$extrasid) {
            $pointtoid = $post->pointto_id;
            if (strpos($pointtoid,'pp') !== FALSE) {
                $extrasid[] = str_replace("pp","",$pointtoid);
            } else if (strpos($pointtoid,'po') !== FALSE) {
                $nextid = str_replace("po","",$pointtoid);
                //may push the post's id to a list here
                $nextpost = Post::find($nextid);
                searchpp($nextpost, $extrasid);
            }
        }

        $extrasid = array();
        foreach ($posts as $post) {
            searchpp($post, $extrasid);
        }

        $all = Pinpoint::whereIn('id', $extrasid)
            ->union($pinpoints)
            ->distinct()
            ->get();

        return $all;
    }

    public function upvote(Request $request) {
        //change user, change posts
        //owned post vs. other's post 
        //$id = substr($request->input('id'), 6);
        $id = $request->input('id');
        $post = Post::find($id);
        $user = User::find(Auth::id());
        $energy = 0;
        $exp = 0;
        $radius = 0;
        if(Auth::id() == $post->user_id) {
            if ($user->energy < 10) {
                return ['redirect'=> url('/index')];
            } else {
                $user->energy -= 10;
                $user->exp += 5;
                $energy = $user->energy;
                $exp = $user->exp;
                $user->save();

                $post->radius += 316/pow($post->radius, 0.25);
                $post->radius = intval($post->radius);
                $radius = $post->radius;
                $post->save();
            }
        } else {
            $user->energy += 10;
            $user->exp += 2;
            if($user->energy > 1000) {
                $user->energy = 1000;
            }
            $energy = $user->energy;
            $exp = $user->exp;
            $user->save();

            $post->radius += 316/pow($post->radius, 0.25);
            $post->radius = intval($post->radius);
            $radius = $post->radius;
            $post->save();
        }

        $data = array($energy, $exp, $radius);
        //$array = json_decode(json_encode($posts));
        return $data;
    }
}
