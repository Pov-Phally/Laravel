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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // upoad profile image
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $user->profile = 'images/' . $filename;
        } else {
            $user->profile = 'images/default.png';
        }
        //save user to database
        $user->save();
        return response()->json(['message' => 'User registered successfully'], 201);
    }

    //login user
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        if (Auth::attempt('email', $request->email, 'password', $request->password)) {
            $token = Auth::user()->Auth::createToken('API token')->accessToken;
            //return  detail and token to user
            return response()->json([
                'user' => Auth::user(),
                'token' => $token,
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    //update user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'No user Found'], 404);
        }
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'UnAuthenticated']);
        }
        $user->Auth::update($request->all());

        //upoad profile image
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $user->profile = 'images/' . $filename;
        } else {
            $user->profile = 'images/default.png';
        }
        //delete old profile image
        if ($user->Auth::getOriginal('profile') != 'images/default.png') {
            $oldImage = public_path($user->Auth::getOriginal('profile'));
            if (file_exists($oldImage)) {
                unlink($oldImage);
            }
        }
        //save user to database
        $user->Auth::update();
        return response()->json(['message' => 'User updated successfully'], 200);
    }
    //logout user
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }
}
