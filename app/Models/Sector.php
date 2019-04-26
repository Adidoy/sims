<?php

namespace App\Models\;

use DB;
use Auth;
use Carbon;
use App\Office;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    public static $appends = ['sector_code', 'sector_name'];

    

}