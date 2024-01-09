<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Follow extends Model
{
    protected $table = 'followables';

    protected $fillable = ['followable_id', 'followable_type', 'accepted_at', 'created_at'];

    public function followable(): MorphTo
    {
        return $this->morphTo();
    }
}
