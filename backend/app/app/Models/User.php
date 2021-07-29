<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	use HasFactory;
	protected $fillable = ['name','mail','password'];

	public static function validations($keys=[]) {
		$validations = [
			"id" => [
				'required',
				'integer',
			],
			"name" => [
				'required',
				'string',
				'max:20',
			],
			'mail' => [
				'required',
				'string',
				'email',
				'max:255',
				'unique:users',
			],
			'password' => [
				'required',
				'min:6',
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

	public function jobs()
	{
		return $this->hasMany(Job::class, 'usr_id')->orderBy("created_at", "DESC");
	}

}
