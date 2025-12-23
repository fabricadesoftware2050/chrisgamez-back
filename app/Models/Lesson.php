<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{

    protected $fillable = [
        'title', 'duration', 'type', 'isFree', 'url_video', 'content', 'description', 'url_download'
    ];


    public function comments()
    {
        return $this->hasMany(LessonQuestion::class)
            ->orderBy('created_at', 'desc');
    }

    

}
