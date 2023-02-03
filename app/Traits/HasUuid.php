<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUUID(): void
    {
        static::creating(function (Model $model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
