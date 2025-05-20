<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllLikeByPostID($id)
    {
        // Get all likes for a specific post
        $likes = Like::where('post_id', $id)->with('user')->first();
        $post = Post::where('id', $id)->with('user')->first();
        // Get the count of likes for the post
        $likes_count = Like::where('post_id', $id)->count();
        return response()->json(
            [
                'post' => $post,
                'like_count' => $likes_count,
                'like' => $likes,
            ],
            200
        );
    }
    /**
     *  Like or unlike a post
     */
    public function LikednDislikedByPostID(Request $request)

    {
        $user = Auth::user(); // Get the authenticated user
        $data = $request->all(); // Get the request data
        $data['user_id'] = $user->id; // Add the user ID to the data
        $data['post_id'] = $request->id; // Add the post ID to the data
        $post = Post::all()->where('id', $request->id)->load('user')->first(); // Get the post by ID
        $like = Like::where('user_id', $user->id)->where('post_id', $request->id)->first(); // Check if the user has already liked the post
        if ($like) { // If the user has already liked the post
            $like->delete();
            return response()->json([
                'Post' => $post,
                'message' => 'Like removed'
            ]);
        } else { // If the user has not liked the post yet
            // Create a new like
            $like = new Like();
            $like::create($data);
            return response()->json([
                'Post' => $post,
                'message' => 'Post liked'
            ]);
        }
    }
}
