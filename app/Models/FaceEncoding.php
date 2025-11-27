<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceEncoding extends Model
{
    protected $fillable = ['user_id', 'encoding'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
