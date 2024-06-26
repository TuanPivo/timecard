<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = "attendances";
    protected $fillable = [
        'user_id',
        'status',
        'type',
        'note',
        'date',
    ];
  
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
