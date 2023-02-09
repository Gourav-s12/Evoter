<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    use HasFactory;
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function nominee()
    {
        return $this->belongsTo(Nominee::class);
    }
}
