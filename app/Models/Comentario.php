<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table ="comentarios";
     protected $fillable = [
        "message","user","ip","lesson_id"
        ];




}
