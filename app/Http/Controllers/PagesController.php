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
        
        $posts = Post::distanceSphere('location', $point, 'radius')->get();

        //$array = json_decode(json_encode($posts));
        return $posts;
    }

    public function loadpinpoints(Request $request) {
        //->with('error', 'Cannot Access Location Service');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $point =new Point($lat, $lng);
        
        //$posts = Pinpoint::distanceSphere('location', $point, 'radius')->get();
        
        $posts = Pinpoint::whereRaw("st_distance_sphere(location, ST_GeomFromText(?)) <= radius", [
            $point->toWkt(),
        ])->get();
        //$posts = DB::select('select * from users where active = ?', [1]);
        
        return $posts;
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
            $post->save();
        }

        $data = array($energy, $exp);
        //$array = json_decode(json_encode($posts));
        return $data;
    }
}
