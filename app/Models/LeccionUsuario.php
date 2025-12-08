<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeccionUsuario extends Model
{
    protected $table = 'lessons_users';

    protected $fillable = [
        'user_id',
        'leccion_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesion()
    {
        return $this->belongsTo(Lesson::class);
    }
}
