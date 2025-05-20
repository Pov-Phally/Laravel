<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //register user
    public function register(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['profile_picture'] = 'images/' . $filename;
        } else {
            $data['profile_picture'] = 'images/default_profile_picture.png';
        }
        // validate the profile picture if it exists
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        //email verification
        // Create a new user instance
        $user = User::create($data);
        // Hash the password
        $user->password = bcrypt($data['password']);
        // Return a response with the user data
        return response()->json([
            'user' => $user,
            'message' => 'User registered successfully'
        ], 201);
    }

    //login user
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Generate a token for the user
            $token = Auth::user()->createToken('Personal Access Token')->accessToken;
            // Return the user data along with the token
            $user = Auth::user();
            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        }
        // Return an error response if authentication fails
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    //update user details
    public function updateUserdata(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        // Validate the request data
        $data = $request->only([
            'name',
            'password',
            'profile_picture',
        ]);
        // Validate the profile picture if it exists
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['profile_picture'] = 'images/' . $filename;
            //delete the old profile picture
            if ($user->profile_picture != 'images/default_profile_picture.png') {
                $oldProfilePicture = public_path($user->profile_picture);
                if (file_exists($oldProfilePicture)) {
                    unlink($oldProfilePicture);
                }
            }
        }
        // Update the user details
        $user->update($data);

        // Return a response
        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully'
        ], 200);
    }
    //logout user
    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->token()->revoke();

        // Return a response
        return response()->json(['message' => 'User logged out successfully'], 200);
    }
    //delete user
    public function deleteUser(Request $request,)
    {
        // Get the authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 404);
        }
        //delete the user
        $user->delete();
        //delete the profile picture
        if ($user->profile_picture != 'images/default_profile_picture.png') {
            $oldProfilePicture = public_path($user->profile_picture);
            if (file_exists($oldProfilePicture)) {
                unlink($oldProfilePicture);
            }
        }
        // Return a response
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
