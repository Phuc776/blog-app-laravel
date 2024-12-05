<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role_id', // Role relationship
        'provider_name',
        'provider_id',
        'auth_type', // For distinguishing between registered and OAuth2 users
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getAccessTokenAttribute()
    {
        return $this->createToken('user')->plainTextToken;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship with the Role model.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship with the Post model.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relationship with the Comment model.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship with the Like model.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relationship with the Follow model (followers and following).
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'following_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'followed_id');
    }

    public function canLoginWithPassword()
    {
        return $this->auth_type === 'local' || $this->auth_type === 'mixed';
    }
    
    public function canLoginWithProvider($provider)
    {
        return $this->auth_type === 'oauth' || $this->auth_type === 'mixed';
    }
    

    /**
     * Relationship with the UserOauth model.
     */
    public function oauthAccounts()
    {
        return $this->hasMany(UserOauth::class);
    }
}
