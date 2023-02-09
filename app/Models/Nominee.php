<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Nominee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description'
    ];
    protected static function boot()
    {
        parent::boot();

        //static::addGlobalScope(new ReverseScope());

        static::addGlobalScope('forwordScope', function (Builder $builder) {
            $builder->orderBy('id', 'asc');
        });
    }
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
    public function voters()
    {
        return $this->hasMany(Voter::class);
    }
    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }
}
