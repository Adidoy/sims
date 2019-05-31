<?php

namespace App\Models;

use DB;
use App;
use Auth;
use Carbon;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    public function scopeFindSectorCode($query, $value)
    {
        $office = App\Office::find($value);
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