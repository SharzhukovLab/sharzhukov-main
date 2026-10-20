<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([\App\Observers\FollowObserver::class])]
class Follow extends Model
{
    protected $fillable = ['follower_id', 'followee_id'];

    public function follower(): BelongsTo { return $this->belongsTo(User::class, 'follower_id'); }
    public function followee(): BelongsTo { return $this->belongsTo(User::class, 'followee_id'); }
}
