<?php

namespace App\Models;

use DB;
use App;
use Auth;
use Carbon;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    //public static $appends = ['sector_code', 'sector_name'];

    public function scopeFindSectorCode($query, $office)
    {
        $office = App\Office::find($office);
        if(isset($office->head_office))
        {
            while(isset($office->head_office)) {
                $headOffice = App\Office::find($office->head_office);
                $office = $headOffice;
            }
        }
        return $office->code;
    }
}