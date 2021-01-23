<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * sign up method 
     * 
     * @param Illuminate\Http\Request
     * 
     * @return Illuminate\Http\Response
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'image' => 'required|image'
        ]);

        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' =>  $request->file('image')->store('avatars')
        ]);

        $token = $user->createToken('auth-token');

        return response()->json([
            'user' => $user,
            'auth_token' => $token->plainTextToken
        ]);
    }

    /**
     * login user
     * 
     * @param Illuminate\Http\Request
     * 
     * @return Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('validation.email'),
            ]);
        }

        return response()->json([
            'auth_token' =>  $user->createToken('auth-token')->plainTextToken
        ]);
    }

    /**
     * follow user
     * 
     * @param Illuminate\Http\Request
     * 
     * @return Illuminate\Http\Response
     */
    public function follow(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                Rule::in(User::pluck('id'))
            ]
        ]);

        $currentUser = $request->user();
        $followingUser = User::find($request->user_id);

        $followings = $currentUser->followings();
        if ($followings->where('follower_id', $currentUser->id)->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'message' => __('lang.followed_already', ['name' => $followingUser->name])
            ]);
        }

        $followings->attach($followingUser);

        return response()->json([
            'message' =>  __('lang.followed_successfully', ['name' => $followingUser->name])
        ]);
    }
}
