<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use UserSeeder;
use App\Models\User;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class UserTest extends TestCase
{

	use MakesGraphQLRequests;
	use RefreshDatabase;

	public function setUp(): void {
		parent::setUp();
	}

	public function test_list() {
		$this->seed(UserSeeder::class);
		$res = $this->graphQL(/** @lang GraphQL */ '
			query{
			  user_list(orderBy: [{column: ID, order: DESC}]) {
				id
				name
				mail
				password
				last_login_at
				jobs {
				  id
				  name
				}
			  }
			}
		');

		$res->assertStatus(200);
		$res->assertJsonCount(2, "data.user_list");
		$res->assertJson([
			"data" => [
				"user_list" => [
					0 => ["id"=>2],
					1 => ["id"=>1],
				],
			],
		]);
    }

	public function test_list_nodata() {
		$res = $this->graphQL(/** @lang GraphQL */ '
			query{
			  user_list(orderBy: [{column: ID, order: DESC}]) {
				id
				name
				mail
				password
				last_login_at
				jobs {
				  id
				  name
				}
			  }
			}
		');

		$res->assertStatus(200);
		$res->assertJsonCount(0, "data.user_list");
		$res->assertJson([
			"data" => [
				"user_list" => [
				],
			],
		]);
	}

	public function test_add() {
		$this->seed(UserSeeder::class);
		$add_user = ["name"=>"a","mail"=>"b@b.com", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertExactJson([
			"data" => [
				"user_add" => true,
			],
		]);
		$this->assertDatabaseHas('users', $add_user);
	}


	public function test_add_validation_name() {
		$this->seed(UserSeeder::class);

		$add_user = ["mail"=>"b@b.com", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$mail: String!
				$password: String!
			){
				user_add(mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"","mail"=>"b@b.com", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"123456789012345678901","mail"=>"b@b.com", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);
	}


	public function test_add_validation_mail() {
		$this->seed(UserSeeder::class);

		$add_user = ["name"=>"aa", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$password: String!
			){
				user_add(name: $name, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa","mail"=>"", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa","mail"=>"bsw2e0?", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa","mail"=>str_repeat("a",255) ."@a.com", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);

		/* // ununique email in users. */
		$add_user = ["name"=>"aa","mail"=>"has_jobs@account.mail.test" ."@a.com", "password"=>"123456"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);
	}


	public function test_add_validation_password() {
		$this->seed(UserSeeder::class);

		$add_user = ["name"=>"aa", "mail"=>"b@b.com"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
			){
				user_add(name: $name, mail: $mail)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa", "mail"=>"b@b.com", "password"=>""];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);

		$add_user = ["name"=>"aa", "mail"=>"b@b.com", "password"=>"12345"];
		$res = $this->graphQL(/** @lang GraphQL */ '
			mutation(
				$name: String!
				$mail: String!
				$password: String!
			){
				user_add(name: $name, mail: $mail, password: $password)
			}
		',
		$add_user);
		$res->assertStatus(200);
		$res->assertJsonCount(1, "errors.0.extensions.validation");
		$this->assertDatabaseMissing('users', $add_user);
	}


}
