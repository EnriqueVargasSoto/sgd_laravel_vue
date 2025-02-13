<?php

namespace Modules\Maestros\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Maestros\Database\Factories\AreaFactory;

class Area extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): AreaFactory
    // {
    //     // return AreaFactory::new();
    // }
}
