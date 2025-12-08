<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'titulo', 'imagen', 'descripcion', 'nivel', 'categoria', 'vistas', 'precio_actual', 'precio_anterior',
        'autor', 'contenido', 'duracion', 'orden','url_video_intro'
    ];

    protected $casts = [
        'contenido' => 'array'
    ];

    public function users()
{
    return $this->belongsToMany(User::class, 'user_courses');
}



}

