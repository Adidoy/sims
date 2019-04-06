<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Correction extends Model
{
    protected $table = 'corrections';
    protected $primaryKey = 'id';

    /**
     * Fillable entries when using eloquent
     * queries
     * 
     * @array
     */
    public $fillable = [
        
    ];
}
