<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message'
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo('users', 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo('users', 'receiver_id');
    }
}
