<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use UserSeeder;
use App\Models\User;

class UserControllerTest extends TestCase
{

	use RefreshDatabase;

	public function setUp(): void {
		parent::setUp();
	}

	public function test_list() {
		$this->seed(UserSeeder::class);
        $res = $this->getJson('/api/rest/user/list');
		$res->assertStatus(200);
		$res->assertJsonCount(2, "results");
		$res->assertJson([
			"results" => [
				0 => ["id"=>2],
				1 => ["id"=>1],
			],
		]);
    }

    public function test_list_nodata() {
        $res = $this->getJson('/api/rest/user/list');
        $res->assertStatus(200);
		$res->assertExactJson([
			"status" => 200,
			"errors" => [],
			"results" => [],
		]);
	}

	public function test_add() {
		$this->seed(UserSeeder::class);
		$add_user = ["name"=>"a","mail"=>"b@b.com", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(200);
		$res->assertExactJson([
			"status" => 200,
			"errors" => [],
			"results" => [],
		]);
		$this->assertDatabaseHas('users', $add_user);
	}

	public function test_add_validation_name() {
		$this->seed(UserSeeder::class);

		$add_user = ["mail"=>"b@b.com", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"","mail"=>"b@b.com", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"123456789012345678901","mail"=>"b@b.com", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);
	}

	public function test_add_validation_mail() {
		$this->seed(UserSeeder::class);

		$add_user = ["name"=>"aa", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa","mail"=>"", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa","mail"=>"bsw2e0?", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa","mail"=>str_repeat("a",255) ."@a.com", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);

		// ununique email in users.
		$add_user = ["name"=>"aa","mail"=>"has_jobs@account.mail.test" ."@a.com", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);
	}

	public function test_add_validation_password() {
		$this->seed(UserSeeder::class);

		$add_user = ["name"=>"aa", "mail"=>"b@b.com"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa", "mail"=>"b@b.com", "password"=>""];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa", "mail"=>"b@b.com", "password"=>"12345"];
		$res = $this->postJson('/api/rest/user/add', $add_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $add_user);
	}


	public function test_modify() {
		$this->seed(UserSeeder::class);

		$mod_user = ["id"=>1,"name"=>"modified_name", "password"=>"modified_password"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(200);
		$res->assertExactJson([
			"status" => 200,
			"errors" => [],
			"results" => [],
		]);
		$this->assertDatabaseHas('users', $mod_user);

		$mod_user = ["id"=>1,"name"=>"modified_name", "mail"=>"modified_mail@mail.com", "password"=>"modified_password"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(200);
		$res->assertExactJson([
			"status" => 200,
			"errors" => [],
			"results" => [],
		]);
		$this->assertDatabaseMissing('users', $mod_user);
		$mod_user = ["id"=>1,"name"=>"modified_name", "mail"=>"has_jobs@account.mail.test", "password"=>"modified_password"];
		$this->assertDatabaseHas('users', $mod_user);
	}


	public function test_modify_validation_id() {
		$this->seed(UserSeeder::class);
		$seed_user = User::find(1)->toArray();

		$mod_user = ["name"=>"aaa", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $mod_user);

		$mod_user = ["id"=>"", "name"=>"aaa", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $mod_user);

		$mod_user = ["mail"=>"has_jobs@account.mail.test", "name"=>"aaa", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $mod_user);

		$this->assertDatabaseHas('users', $seed_user);

	}


	public function test_modify_validation_name() {
		$this->seed(UserSeeder::class);
		$seed_user = User::find(1)->toArray();

		$mod_user = ["id"=>1, "name"=>"", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $mod_user);

		$mod_user = ["id"=>1, "name"=>"123456789012345678901", "password"=>"123456"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $mod_user);

		$this->assertDatabaseHas('users', $seed_user);
	}


	public function test_modify_validation_password() {
		$this->seed(UserSeeder::class);
		$seed_user = User::find(1)->toArray();

		$mod_user = ["id"=>1, "name"=>"aa"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $mod_user);

		$mod_user = ["id"=>1, "name"=>"aa", "password"=>""];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $mod_user);

		$mod_user = ["id"=>1, "name"=>"aa", "password"=>"12345"];
		$res = $this->postJson('/api/rest/user/modify', $mod_user);
		$res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
		$this->assertDatabaseMissing('users', $mod_user);

		$this->assertDatabaseHas('users', $seed_user);
	}
}
