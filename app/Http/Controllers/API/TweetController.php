<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tweet;

class TweetController extends Controller
{
    /**
     * create tweet method
     * 
     * @param Illuminate\Http\Request
     * 
     * @return Illuminate\Http\Response
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
}
