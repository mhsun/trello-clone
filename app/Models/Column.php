<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Column extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->order = $model->id;
            $model->save();
        });

        static::addGlobalScope('sorted', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }

    public function cards()
    {
        return $this->hasMany(Card::class)->orderBy('order');
    }
}
