<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 *
 * @OA\Schema(
 * required={"name","email","image","password"},
 * @OA\Xml(name="User"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *  @OA\Property(property="name", type="string", example="Doe"),
 * @OA\Property(property="email", type="string", format="email", description="User unique email address", example="user@gmail.com"),
 * @OA\Property(property="email_verified_at", type="string", readOnly="true", format="date-time", description="Datetime marker of verification status", example="2019-02-25 12:59:20"),
 *  @OA\Property(property="password", type="string", example="P$$wef22"),
 *  @OA\Property(property="image", type="string", example="/image.png"),
 * @OA\Property(property="created_at", type="string", readOnly="true", format="date-time", description="Datetime marker of created at", example="2019-02-25 12:59:20"),
 * @OA\Property(property="updated_at", type="string", readOnly="true", format="date-time", description="Datetime marker of updated_at", example="2019-02-25 12:59:20"),
 * )
 *
 * Class User
 *
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the tweets for the user.
     */
    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    /**
     * Get the followings for the user.
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'follower_user', 'follower_id', 'user_id')->withTimestamps();
    }

    /**
     * Get the followers for the user.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follower_user', 'user_id', 'follower_id')->withTimestamps();
    }
}
