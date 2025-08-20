<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Review;

class ReviewLiked implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $reviewId;
    public $liker_id;
    public $owner_user_id;

    public function __construct(Review $review, $likerId)
    {
        $this->reviewId = $review->id;
        $this->liker_id = $likerId;
        $this->owner_user_id = $review->user_id;
    }

    public function broadcastOn()
    {
        return new Channel('user.'.$this->owner_user_id);
    }
}
