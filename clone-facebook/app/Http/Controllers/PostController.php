<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Get all posts with user likes and comments
    public function index()
    {   //shpw all posts with user like and comment

        $posts = Post::with('user')->latest()->paginate(10)->load('likes', 'comments');
        // Add likes and comments count to each post
        foreach ($posts as $post) {
            $post['likes_count'] = $post->likes()->count();
            $post['comments_count'] = $post->comments()->count();
        }
        // Add the authenticated user's like status for each post
        $userId = Auth::user()->id;
        foreach ($posts as $post) {
            $post['liked'] = $post->likes()->where('user_id', $userId)->exists();
        }
        return response(['Post' => $posts], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storePost(Request $request)
    {
        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = new Post(); // Create a new post instance
        $post->user_id = Auth::id(); // Get the authenticated user ID
        $post->content = $request->input('content'); // Set the post content
        // Check if the request has an image file
        if ($request->hasFile('image')) { // If an image is provided
            $file = $request->file('image'); // Get the uploaded file
            $filename = time() . '.' . $file->getClientOriginalExtension(); // Generate a unique filename
            $file->move(public_path('images'), $filename); // Move the file to the public/images directory
            $post->image = 'images/' . $filename; // Set the post image path
        }

        $post->save(); // Save the post to the database

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    // Get a specific post by ID with user likes and comments
    public function showPostByID($id)
    {
        // Get the post by ID with user likes and comments
        $post = Post::with('user')->find($id)->load('likes', 'comments');
        // Check if the post exists
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        // Add likes and comments count to the post
        $post['likes_count'] = $post->likes()->count();
        $post['comments_count'] = $post->comments()->count();
        // Add the authenticated user's like status for the post
        $userId = Auth::user()->id;
        // Check if the user has liked the post
        $post['liked'] = $post->likes()->where('user_id', $userId)->exists();
        // Add comments to the post
        return response(['post' => $post,], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    // Update a post by its ID
    public function updatePostByID(Request $request, $id)
    {
        // Find the post by ID
        $post = Post::find($id);
        //check if post exists
        if ($post == null) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        //check user is authorized to update the post
        if (Auth::user()->id != $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $post->image = 'images/' . $filename;
            //delete the old image if it exists
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
        }
        //update the post
        $post->save();
        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    // Delete a post by its ID
    public function destroy($id)
    {
        // Find the post by ID
        $post = Post::find($id);
        //check if post exists
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        //check user is authorized to delete the post
        if (Auth::user()->id != $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        //delete the image if it exists
        if ($post->image && file_exists(public_path($post->image))) {
            unlink(public_path($post->image));
        }
        //delete the post
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
