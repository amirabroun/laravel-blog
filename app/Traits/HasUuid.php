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

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function uuid($uuid)
    {
        return (new static)->where('uuid', $uuid);
    }
}
