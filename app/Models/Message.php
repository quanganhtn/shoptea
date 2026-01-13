<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMessage
 */
class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender',
        'body',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
