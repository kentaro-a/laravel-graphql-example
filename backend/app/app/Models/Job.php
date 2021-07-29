<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
	use HasFactory;
	protected $fillable = ['usr_id','name','status'];

	public static function validations($keys=[]) {
		$validations = [
			"id" => [
				'required',
				'integer',
			],
			"usr_id" => [
				'required',
				'integer',
				'exists:users,id',
			],
			"name" => [
				'required',
				'string',
				'max:255',
			],
			"status" => [
				'required',
				'integer',
				'max:9',
				'min:0',
			],
		];

		$ret = [];
		foreach ($keys as $k) {
			if (isset($validations[$k])) {
				$ret[$k] =  $validations[$k];
			}
		}
		return $ret;
	}


	public function user() {
		return $this->belongsTo(User::class, 'usr_id');
	}

}
