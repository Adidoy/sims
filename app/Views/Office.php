<?php
namespace App\Views;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{

	protected $table = 'offices_v';
    
    /**
     * Filters the record by the sector of the office
     *
     * @param Builder $query
     * @param string $office
     * @return object
     */
	public function scopeSector($query, $office)
    {
        return $query->where('date_received');
    }
    
}
