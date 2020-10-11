<?php

namespace App\Http\Controllers;

use App\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function allPosts() {
        $posts = Post::all();

        if($posts) {
            return PostResource::collection($posts);
        }
        return response()->json(['data' => 'Mo Post Found']);
    }

    public function index()
    {
        $userId = $this->_userId();
        $posts = Post::where('user_id', $userId)->get();

        if($posts) {
            return PostResource::collection($posts);
        }
        return response()->json(['data' => 'Mo Post Found']);
    }


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
        $post = new Post();
        $post->user_id = $this->_userId();
        $post->title = $request->title;
        $post->detail = $request->detail;

        if($post->save()) {
            return new PostResource($post);
        }

        return response()->json(['data' => 'Post Not Created']);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if($post) {
            return new PostResource($post);
            
        }

        return response()->json(['data' => 'No Post Found for you']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $post = $this->_post($id);

        if($post) {
            $post->title = $request->title;
            $post->detail = $request->detail;
            $post->save();
            return new PostResource($post);
        }

        return response()->json(['data' => 'Post Not Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->_post($id);

        if($post) {
            $post->delete();
            return new PostResource($post);
        }

        return response()->json(['data' => 'No Post Found']);

    }


    public function _userId() {
        return 10;
    }

    public function _post($id) {
        $userId = $this->_userId();
        $post = Post::where('user_id', $userId)->where('id', $id)->first();
        return $post;
    }
}



// php artisan make:controller PostController --resource --model=Post
