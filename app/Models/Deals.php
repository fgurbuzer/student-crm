<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'owner_id', 'thread_id', 'title', 'stage', 'value_amount', 'value_currency', 'value_currency', 'lost_reason'])]
class Deals extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }

    public function notes()
    {
        return $this->belongsTo(DealNotes::class, 'deal_id');
    }

    public function stageHistory()
    {
        return $this->belongsTo(DealStageHistory::class, 'deal_id');
    }



}
