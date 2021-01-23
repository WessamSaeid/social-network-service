<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tweet;

class TweetController extends Controller
{
   /**
     * @OA\Post(
     * path="/api/tweets",
     * summary="Create tweet",
     * description="Create new tweet",
     * operationId="store",
     * tags={"tweets"},
     * @OA\RequestBody(
     *    required=true,
     *    description="text of the tweet",
     *    @OA\JsonContent(
     *       required={"text"},
     *       @OA\Property(property="text", type="string", example="New tweet"),
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
     *    response=201,
     *    description="created successfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="tweet", type="object", ref="#/components/schemas/Tweet"),
     *        )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:140',
        ]);

        $user = $request->user();
        $tweet = $user->tweets()->create([
            'text' => $request->text
        ]);
        
        return response()->json([
            'tweet' => $tweet
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/timeline",
     * summary="Show timeline twets",
     * description="List all the tweets of the followed users paginated",
     * operationId="timeline",
     * tags={"Timeline"},
     * @OA\Response(
     *    response=200,
     *    description="success",
     *    @OA\JsonContent(
     *           @OA\Property(property="tweets", type="object")
     *      )
     *     )
     * )
     */
    public function timeline(Request $request)
    {
        $followingsIds = $request->user()->followings()->pluck('user_id');
        $tweets = Tweet::whereIn('user_id', $followingsIds)->with('user')->paginate();

        return response()->json([
            'tweets' => $tweets
        ]);
    }
}
