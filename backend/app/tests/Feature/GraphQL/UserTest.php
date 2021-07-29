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


}
