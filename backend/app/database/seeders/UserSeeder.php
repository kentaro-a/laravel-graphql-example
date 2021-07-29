<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	{
		User::create([
			'id'     => 1,
			'name'     => "[TEST ACCOUNT] Has Jobs",
			'mail'     => "has_jobs@account.mail.test",
			'password'     => "1121",
			'last_login_at'     => date("Y-m-d H:i:s"),
		]);

		User::create([
			'id'     => 2,
			'name'     => "[TEST ACCOUNT] No Jobs",
			'mail'     => "no_jobs@account.mail.test",
			'password'     => "1121",
			'last_login_at'     => date("Y-m-d H:i:s"),
		]);

    }
}
