<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Election extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'start',
        'end'
    ];
    protected $dates = [
        'start',
        'end'
    ];
    protected static function boot()
    {
        parent::boot();

        //static::addGlobalScope(new ReverseScope());

        static::addGlobalScope('reverseScope', function (Builder $builder) {
            $builder->orderBy('id', 'desc');
        });
    }
    public function nominees()
    {
        return $this->hasMany(Nominee::class);
    }
    public function scopeIsOpen($query)
    {
        return $query->whereNotNull('start')->whereNull('end');
    }
    public function scopeIsClose($query)
    {
        return $query->whereNotNull('start')->whereNotNull('end');
    }
    public function total_voters()
    {
        return $this->hasMany(Voter::class);
    }
    public function scopeIsVoted($query , $user_id)
    {
        return $query->leftJoin('voters', function ($join) use($user_id){
            $join->on('elections.id', '=', 'voters.election_id')
                 ->where('voters.user_id', '=', $user_id );
        })->select('elections.*','voters.user_id');
    }
    function voters()
    {
        return $this->hasManyThrough(Voter::class, Nominee::class);
    }

}
