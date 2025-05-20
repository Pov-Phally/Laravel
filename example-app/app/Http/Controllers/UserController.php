<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        // Fetch all users from the database
        $users = DB::select('SELECT * FROM users');

        // Return a view with the users data
        return $users;
    }

    public function show($id)
    {
        // Fetch a single user by ID
        $user = DB::table('users')->where('id', $id)->get();

        // Return a view with the user data
        return $user;
    }
    public function create()
    {
        // Show a form to create a new user
        DB::table('users')->insert(
            [
                'FirstName' => 'John',
                'LastName' => 'Janes',
                'Job' => 'Developer',
                'Phone' => '1234567890',
            ]
        );
        return 'User created successfully!';
    }
    public function update($id)
    {
        // Update a user by ID
        DB::table('users')->where('id', $id)->update(
            [
                'Job' => 'Manager',
                'Phone' => '0987654321',
            ]
        );
        return 'User updated successfully!';
    }
    public function delete($id)
    {
        // Delete a user by ID
        DB::table('users')->where('id', $id)->delete(
            [
                'id' => $id,
            ]
        );
        return 'User deleted successfully!';
    }
}
