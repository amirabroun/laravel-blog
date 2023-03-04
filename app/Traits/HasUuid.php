<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasUuid
{
    public static function bootHasUUID(): void
    {
        static::creating(
            fn (Model $model) => $model->uuid = fake()->uuid()
        );
    }
}
