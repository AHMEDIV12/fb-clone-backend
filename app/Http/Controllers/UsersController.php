<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function userFollowers($id){
        $followers = [];
        $user = User::findOrFail($id);
        $user_followers_ids = $user->followers;
        foreach($user_followers_ids as $follower){
            $user_follower = User::findOrFail($follower);
            $followers = [...$followers , $user_follower ];
        }

        return response()->json(['followers' => $followers , 'success' => isset($followers)]);
    }

    public function userFollowings($id){
        $followings = [];
        $user = User::findOrFail($id);
        $user_followings_ids = $user->following;
        // dd($user);
        foreach($user_followings_ids as $following){
            $user_following = User::findOrFail($following);
            $followings = [...$followings , $user_following ];
        }

        return response()->json(['followings' => $followings , 'success' => isset($followings)]);
    }

    
    public function user($id)
    {
        $user = User::findOrFail($id);

        return response()->json(['user' => $user, 'success' => isset($user)]);
    }
    
    public function chekuserfollower(Request $request, $id)
    {
        $data = $request->all();
        $user = User::findOrFail($id);
        $in_request_user = User::findOrFail($data['id']);

        if(in_array($data['id'],$user['followers'])){

            return response()->json(['followed' => true]);
        }else{
            return response()->json(['followed' => false]);

        }

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
        //
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
