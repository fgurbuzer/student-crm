<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;


#[Fillable(['thread_id', 'sender_id', 'sender_type', 'body'])]
class Messages extends Model
{
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function thread()
    {
        return $this->belongsTo(Threads::class);
    }
}
