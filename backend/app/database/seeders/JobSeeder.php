<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	{
		Job::create([
			'id'     => 1,
			'usr_id'     => 1,
			'name'     => "job1",
			'status'     => 0,
			'created_at'     => "2020-01-01 00:00:00",
		]);
		Job::create([
			'id'     => 2,
			'usr_id'     => 1,
			'name'     => "job2",
			'status'     => 1,
			'created_at'     => "2020-01-02 02:00:00",
		]);
		Job::create([
			'id'     => 3,
			'usr_id'     => 1,
			'name'     => "job3",
			'status'     => 2,
			'created_at'     => "2020-01-02 03:00:00",
		]);
    }
}
