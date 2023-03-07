<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use App\Post;
use App\User;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json(['posts' => $posts , 'messege' => 'success']);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:200',
            'desc' => 'required|max:2000',
        ]);
        // dd($request->file('img'));
        $data = $request->all();
       
        if ($request->file('img') != null) {
            $request->file('img')->store('public/posts_imgs');
            $data['img']  = 'posts_imgs/' . $request->file('img')->hashName();
        }
        $data['likes'] = [];
        Post::create($data);

        return response()->json(['messege' => 'Post created successfly']);

    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        $user = $post->user;
        // dd($user);

        return response()->json([ 'post' => $post, 'messege' => 'Post created successfly']);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $post = Post::findOrFail($id);

        $post->update($data);
        $post->save();
        
        return response()->json(['post' => $post, 'messege' => 'post updated successfly']);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['messege' => 'post deleted successfly']);
    }

    public function postlike(Request $request, $id){
        
        $data = $request->all();
        $post = Post::findOrFail($id);
        $postlikes = $post['likes'];
        // dd($data);
        $authid = $data['id'];
         if(!in_array($data['id'],$post['likes']))
            {
                $post['likes'] = Arr::prepend($post['likes'], $data['id']);
                $post->save();
                return response()->json(['messege' => 'you liked this post','postLikes' => count($post['likes'])]);
            }else{
                $post['likes'] = array_diff($post['likes'], array($authid));
                $post->save();
                return response()->json(['messege' => 'you unliked this post','postLikes' => count($post['likes'])]);
            }
    }

    public function userposts($id){

        $user = User::findOrFail($id);
        $posts = $user->posts;
        $followersPosts =$user->following;

        if(count($followersPosts) != 0){
        foreach($followersPosts as $follower){
            $userPosts = User::findOrFail($follower);
            $followerPosts = $userPosts->posts ;
            foreach( $followerPosts as $post){
                $posts = [...$posts, $post]  ;
            }
        }}
        return response()->json(['posts' => $posts, 'success' => isset($posts)]);

    }

}
