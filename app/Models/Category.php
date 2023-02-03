<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Category extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['title'];

    protected $with = ['posts'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
