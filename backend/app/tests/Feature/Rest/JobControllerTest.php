<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use UserSeeder;
use JobSeeder;
use App\Models\User;

class JobControllerTest extends TestCase
{

	use RefreshDatabase;

	public function setUp(): void {
		parent::setUp();
	}

	public function test_list() {
		$this->seed(UserSeeder::class);
		$this->seed(JobSeeder::class);
        $res = $this->getJson('/api/rest/job/list/1');
		$res->assertStatus(200);
		$res->assertJson([
			"results" => [
				"jobs" => [
					0 => ["id"=>3],
					1 => ["id"=>2],
					2 => ["id"=>1],
				]
			],
		]);
    }

	public function test_list_no_jobs() {
		$this->seed(UserSeeder::class);
		$this->seed(JobSeeder::class);
        $res = $this->getJson('/api/rest/job/list/2');
        $res->assertStatus(200);
		$res->assertJsonCount(0, "results.jobs");
	}

	public function test_list_no_users() {
		$this->seed(UserSeeder::class);
		$this->seed(JobSeeder::class);
        $res = $this->getJson('/api/rest/job/list/1000000');
        $res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
	}

    public function test_list_validation_usr_id() {
		$this->seed(UserSeeder::class);
		$this->seed(JobSeeder::class);
        $res = $this->getJson('/api/rest/job/list/a');
        $res->assertStatus(400);
		$res->assertJsonCount(1, "errors");

		$res = $this->getJson('/api/rest/job/list/１');
        $res->assertStatus(400);
		$res->assertJsonCount(1, "errors");
	}

	public function test_add() {
		$this->seed(UserSeeder::class);
		$add_job = ["usr_id"=>1, "name"=>"test job","status"=>0];

		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(200);
		$res->assertExactJson([
			"status" => 200,
			"errors" => [],
			"results" => [],
		]);
		$this->assertDatabaseHas('jobs', $add_job);
	}

	public function test_add_validation_usr_id() {
		$this->seed(UserSeeder::class);

		$add_job = ["usr_id"=>10000, "name"=>"test job","status"=>0];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		// print_r($res->json());
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);
	}

    public function test_add_validation_name() {
		$this->seed(UserSeeder::class);

		$add_job = ["usr_id"=>1, "name"=>"","status"=>0];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);

		$add_job = ["usr_id"=>1, "name"=>1,"status"=>0];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);
	}

	public function test_add_validation_status() {
		$this->seed(UserSeeder::class);

		$add_job = ["usr_id"=>1, "name"=>"aaa"];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);

		$add_job = ["usr_id"=>1, "name"=>"aaa","status"=>""];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);

		$add_job = ["usr_id"=>1, "name"=>"aaa","status"=>"a"];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);

		$add_job = ["usr_id"=>1, "name"=>"aaa","status"=>-1];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);

		$add_job = ["usr_id"=>1, "name"=>"aaa","status"=>10];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);

		$add_job = ["usr_id"=>1, "name"=>"aaa","status"=>0x10];
		$res = $this->postJson('/api/rest/job/add', $add_job);
		$res->assertStatus(400);
		$this->assertDatabaseMissing('jobs', $add_job);
	}

}
