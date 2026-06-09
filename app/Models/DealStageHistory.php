<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;


#[Fillable(['deal_id','from_stage', 'to_stage', 'changed_by'])]
class DealStageHistory extends Model
{
    public function deal()
    {
        return $this->belongsTo(Deals::class);
    }
}
