<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{

    public function index()
    {
        // Fetch all posts from the database
        $posts = Post::all();

        // Return a view with the posts data
        return $posts;
    }


    public function show($id)
    {
        // Fetch a single post by ID
        $post = Post::get($id);

        // Return a view with the post data
        return $post;
    }

    public function create()
    {
        // Show a form to create a new post
        POst::create([
            'title' => 'New Post',
            'content' => 'This is the content of the new post.',
        ]);
        return 'Post created successfully!';
    }
}
