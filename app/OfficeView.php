<?php
namespace App;

use Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
class OfficeView extends Model{

	protected $table = 'offices_v';
	
	public function scopeFilterBySector($query, $office)
    {
        return $query->where('date_received');
    }
    
}
