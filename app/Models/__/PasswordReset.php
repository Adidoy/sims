<?php

namespace App;

use Auth;
use Carbon;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model {
    protected $table = 'password_reset';
	protected $primaryKey = 'id';
	protected $fillable = [
		'sectorCode',
		'sectorName',
		'sectorHead', 
		'sectorHeadPosition',
		'assistHead',
        'assistHeadPosition',
        'purge',
	];
	public $timestamps = true;

	public function rules(){
		return array(
			'Code' => 'required|min:2|max:10|string|unique:dtmt_sectors,sectorcode',
			'Sector Name' => 'required|max:75',
            'Sector Head' => 'required|max:150',
            'Sector Head Position' => 'required|max:100',
            'Assist Head' => 'required|max:150',
            'Assist Head Position' => 'required|max:100',
		);
	}

	public function updateRules(){
		$code = $this->code;
		return array(
			//'Code' => 'required|max:10|unique:offices,code,'.$code.',code',
			'Sector Name' => 'required|max:75',
			//'Description' => 'max:200'
		);
	}
}