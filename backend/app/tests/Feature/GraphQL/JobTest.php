<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use UserSeeder;
use JobSeeder;
use App\Models\User;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class JobTest extends TestCase
{

	use MakesGraphQLRequests;
	use RefreshDatabase;

	public function setUp(): void {
		parent::setUp();
	}

	public function test_list() {
		$this->seed(UserSeeder::class);
		$this->seed(JobSeeder::class);

		$query = ["usr_id" => 1, "order" => "DESC"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			query(
				$usr_id: Int!
				$order: SortOrder!
			){
				job_list(usr_id: $usr_id, orderBy: [{column: ID, order: $order}]) {
					id
					usr_id
					name
					status
					created_at
					user {
						name
					}
				}
			}
		',
		$query);
		$res->assertStatus(200);
		$res->assertJson([
			"data" => [
				"job_list" => [
					0 => ["id"=>3],
					1 => ["id"=>2],
					2 => ["id"=>1],
				]
			],
		]);


		$query = ["usr_id" => 1, "order" => "ASC"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			query(
				$usr_id: Int!
				$order: SortOrder!
			){
				job_list(usr_id: $usr_id, orderBy: [{column: ID, order: $order}]) {
					id
					usr_id
					name
					status
					created_at
					user {
						name
					}
				}
			}
		',
		$query);
		$res->assertStatus(200);
		$res->assertJson([
			"data" => [
				"job_list" => [
					0 => ["id"=>1],
					1 => ["id"=>2],
					2 => ["id"=>3],
				]
			],
		]);
    }


	public function test_list_no_jobs() {
		$this->seed(UserSeeder::class);
		$this->seed(JobSeeder::class);

		$query = ["usr_id" => 2];
		$res = $this->graphQL(/** @lang GraphQL */ '
			query(
				$usr_id: Int!
			){
				job_list(usr_id: $usr_id) {
					id
					usr_id
					name
					status
					created_at
					user {
						name
					}
				}
			}
		',
		$query);
		$res->assertStatus(200);
		$res->assertJsonCount(0, "data.job_list");
	}



	public function test_list_no_users() {
		$this->seed(UserSeeder::class);
		$this->seed(JobSeeder::class);
        $query = ["usr_id" => 10000];
		$res = $this->graphQL(/** @lang GraphQL */ '
			query(
				$usr_id: Int!
			){
				job_list(usr_id: $usr_id) {
					id
					usr_id
					name
					status
					created_at
					user {
						name
					}
				}
			}
		',
		$query);
		$res->assertStatus(200);
		$res->assertJsonCount(0, "data.job_list");
	}


	public function test_add() {
		$this->seed(UserSeeder::class);
		$add_job = ["usr_id"=>1, "name"=>"test job","status"=>0];

		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$usr_id: Int!
				$name: String!
				$status: Int!
			){
				job_add(usr_id: $usr_id, name: $name, status: $status)
			}

		',
		$add_job);
		$res->assertStatus(200);
		$res->assertExactJson([
			"data" => [
				"job_add" => true,
			],
		]);
		$this->assertDatabaseHas('jobs', $add_job);
	}


	public function test_add_validation_usr_id() {
		$this->seed(UserSeeder::class);

		$add_job = ["usr_id"=>10000, "name"=>"test job","status"=>0];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$usr_id: Int!
				$name: String!
				$status: Int!
			){
				job_add(usr_id: $usr_id, name: $name, status: $status)
			}

		',
		$add_job);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('jobs', $add_job);
	}



	public function test_add_validation_name() {
		$this->seed(UserSeeder::class);

		$add_job = ["usr_id"=>1, "name"=>"","status"=>0];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$usr_id: Int!
				$name: String!
				$status: Int!
			){
				job_add(usr_id: $usr_id, name: $name, status: $status)
			}

		',
		$add_job);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('jobs', $add_job);

		$add_job = ["usr_id"=>1, "name"=>str_repeat("a",256),"status"=>0];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$usr_id: Int!
				$name: String!
				$status: Int!
			){
				job_add(usr_id: $usr_id, name: $name, status: $status)
			}

		',
		$add_job);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('jobs', $add_job);

	}


	public function test_add_validation_status() {
		$this->seed(UserSeeder::class);

		$add_job = ["usr_id"=>1, "name"=>"aaa","status"=>-1];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$usr_id: Int!
				$name: String!
				$status: Int!
			){
				job_add(usr_id: $usr_id, name: $name, status: $status)
			}

		',
		$add_job);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('jobs', $add_job);


		$add_job = ["usr_id"=>1, "name"=>"aaa","status"=>10];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$usr_id: Int!
				$name: String!
				$status: Int!
			){
				job_add(usr_id: $usr_id, name: $name, status: $status)
			}

		',
		$add_job);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('jobs', $add_job);

	}

}
