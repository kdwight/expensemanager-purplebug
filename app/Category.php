<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::slug($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucwords($value);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getAmountTotalAttribute()
    {
        return $this->expenses()->sum('amount');
    }
}
