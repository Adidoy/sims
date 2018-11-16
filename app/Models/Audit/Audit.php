<?php

namespace App\Models\Audit;

use Carbon\Carbon;
use App\Models\User\User
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audits';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    /**
     * Additional fields
     *
     * @var array
     */
    protected $appends = [
        'full_name', 'parsed_date'
    ];

    /**
     * Returns the full name attribute of the user
     *
     * @return void
     */
    public function getFullNameAttribute()
    {
        return $this->lastname . ", " . $this->firstname . " " . $this->middlename;
    }

    /**
     * Returns the parsed date for the audit
     *
     * @return void
     */
    public function getParsedDateAttribute()
    {
        return Carbon::parse($this->created_at)->toDayDateTimeString();
    }

    /**
     * Link to the users model
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
   
}
