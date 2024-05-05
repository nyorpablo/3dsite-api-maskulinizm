<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserApiSubscription extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subscription_tier',
    ];

    public function subscription()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user_tier()
    {
        return $this->belongsTo(SubscriptionTier::class, 'subscription_tier');
    }
}
