<?php
// app/Models/Pago.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'price',
        'detalle',
        'status',
    ];

    protected $casts = [
        'detalle' => 'array',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function curso()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
