<?php

namespace Modules\Tramite\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Tramite\Database\Factories\TramiteFactory;

class Tramite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): TramiteFactory
    // {
    //     // return TramiteFactory::new();
    // }
}
