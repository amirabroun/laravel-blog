<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowableCollection extends JsonResource
{
        /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = FollowableResource::class;
}
