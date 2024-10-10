<?php

namespace App\Http\Controllers;

use App\Models\post;
// use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum' , except : ['index', 'show'])
        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        // create post
        $post = $request->user()->posts()->create($fields);
        // return response
        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(post $post)
    {
        return response()->json([
            'message' => 'Post returned successfully',
            'post' => $post
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, post $post)
    {
        // check if the user is authorized to modify the post
        // using policy
        Gate::authorize('modify', $post);
        // validate the request
        $fields = $request->validate([
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
        ]);
        // update the post
        $post->update($fields);
        // return response
        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post $post)
    {
        // check if the user is authorized to modify the post
        // using policy
        Gate::authorize('modify', $post);
        // delete the post
        $post->delete();
        // return response
        return response()->json([
            'message' => 'Post deleted successfully',
        ], 200);
    }
}