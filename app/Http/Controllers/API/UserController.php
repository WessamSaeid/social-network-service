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
     * @OA\Post(
     * path="/api/signup",
     * summary="Sign up",
     * description="Signup by name,email, password, image",
     * operationId="signup",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user info",
     *    @OA\JsonContent(
     *       required={"name","email","password", "image"},
     *       @OA\Property(property="name", type="string", example="user1"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="image", type="image"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="validation error",
     *    @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="The given data was invalid.")
     *       )
     *     ),
     * @OA\Response(
     *    response=201,
     *    description="signed up successfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *       @OA\Property(property="auth_token", type="string", example="7|3oj7ungE9309ZpR8ZknWQzbJgPzmUpmM68RwScN0")
     *        )
     *     )
     * )
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
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="login",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The email must be a valid email address.")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="valid credientials",
     *    @OA\JsonContent(
     *       @OA\Property(property="auth_token", type="string", example="7|3oj7ungE9309ZpR8ZknWQzbJgPzmUpmM68RwScN0")
     *        )
     *     )
     * )
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
                'message' => __('lang.invalid_credientials'),
            ]);
        }

        return response()->json([
            'auth_token' =>  $user->createToken('auth-token')->plainTextToken
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/users/follow",
     * summary="Follow another user",
     * description="Follow another user",
     * operationId="follow",
     * tags={"users"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user id to follow",
     *    @OA\JsonContent(
     *       required={"user_id"},
     *       @OA\Property(property="user_id", type="integer", example=1),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="validation exception",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid.")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="followed successfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="You have followed :name successfully.")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="Already followed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="You have already followed :name.")
     *        )
     *     )
     * )
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
            ],404);
        }

        $followings->attach($followingUser);

        return response()->json([
            'message' =>  __('lang.followed_successfully', ['name' => $followingUser->name])
        ]);
    }
}
