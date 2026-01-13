<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperConversation
 */
class Conversation extends Model
{

    protected $fillable = [
        'user_id',
        'admin_id',
        'last_message_at',
        'admin_last_read_at',
        'admin_unread',
        'last_sender',
    ];


    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
