<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *  required={"text"},
 *  @OA\Xml(name="Tweet"),
 *  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *  @OA\Property(property="text", type="string", example="tweet text"),
 *  @OA\Property(property="created_at", type="string", readOnly="true", format="date-time", description="Datetime marker of created at", example="2019-02-25 12:59:20"),
 *  @OA\Property(property="updated_at", type="string", readOnly="true", format="date-time", description="Datetime marker of updated_at", example="2019-02-25 12:59:20"),
 * )
 *
 * Class User
 *
 */
class Tweet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    /**
     * Get the user that owns the tweet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
