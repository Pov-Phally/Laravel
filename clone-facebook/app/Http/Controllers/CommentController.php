<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Get all comments for a specific post
    public function getAllCommentByPostID($id)
    {
        // Get all comments for a specific post
        $comments = Comment::where('post_id', $id)->with('user')->get();
        //number of comments
        $comments_count = Comment::where('post_id', $id)->count();
        // Get the post by ID
        $post = Post::where('id', $id)->with('user')->first();
        return response()->json(
            [
                'post' => $post,
                'comments_count' => $comments_count,
                'comments' => $comments,
            ],
            200
        );
    }
    //
    /**
     * Store a newly created resource in storage.
     */
    // Create a new comment for a specific post
    public function storeCommentByPostID(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = new Comment(); // Create a new comment instance
        $comment->user_id = Auth::id(); // Get the authenticated user ID
        $comment->post_id = $id; // Set the post ID
        $comment->content = $request->input('content'); // Set the comment content
        $comment->save(); // Save the comment to the database

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment,
        ], 201);
    }



    /**
     * Update the specified resource in storage.
     */
    // Update a comment by its ID
    public function updateCommentByID(Request $request, $id)
    {
        $comment = Comment::find($id); // Find the comment by ID
        // Check if the comment exists
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:255',
        ]);
        $comment->content = $request->input('content'); // Update the content
        $comment->save(); // Save the changes to the database
        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id); // Find the comment by ID

        // Check if the authenticated user is the owner of the comment
        if ($comment->user_id !== auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete(); // Delete the comment
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
