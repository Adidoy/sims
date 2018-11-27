<?php

namespace App\Models\Requests\Expiration;

use DB;
use Carbon;
use Illuminate\Database\Eloquent\Model;

class RequestExpiration extends Model {
    protected $table = 'request_expiration';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [ 
        'request_id' , 
        'date_requested' , 
        'expiration_date'
      ];
}