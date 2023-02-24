<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Casts\Attribute,
    Factories\HasFactory,
};

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body'];
    protected $with = ['user'];
    protected $appends = ['size'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * 4 ... 12
     *
     * @return Attribute
     */
    protected function size(): Attribute
    {
        $wordCount = count(explode(' ', $this->body));

        $size = match (true) {
            $wordCount < 10 && $wordCount > 0 => 4,
            $wordCount < 15 && $wordCount > 10 => 5,
            $wordCount < 20 && $wordCount > 15 => 6,
            $wordCount < 25 && $wordCount > 20 => 7,
            $wordCount < 30  && $wordCount > 25 => 8,
            $wordCount < 35  && $wordCount > 30 => 9,
            $wordCount < 40  && $wordCount > 35 => 11,
            default => 12
        };

        return Attribute::get(fn () => $size);
    }
}
