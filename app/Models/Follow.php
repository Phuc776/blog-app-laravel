<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'following_id',
        'followed_id',
        'deleted_at',
    ];

    /**
     * Relationship with User model for the follower.
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'following_id');
    }

    /**
     * Relationship with User model for the followed user.
     */
    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
