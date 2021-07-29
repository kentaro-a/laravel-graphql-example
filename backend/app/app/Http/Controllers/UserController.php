<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class UserController extends Controller
{
	public function list() {
		$res = [
			"status" => 200,
			"errors" => [],
			"results" => [],
		];
		$users = User::orderBy("id","DESC")->get(["id","name"]);
		$res["results"] = $users;
		return response()->json($res, $res["status"]);
	}

	public function add(Request $request) {
		$res = [
			"status" => 200,
			"errors" => [],
			"results" => [],
		];
		$validations = User::validations(["name","mail","password"]);
		$validator = Validator::make($request->input(), $validations);
		if ($validator->fails()) {
			$res["status"] = 400;
			$res["errors"] = $validator->errors();

		} else {
			User::create($request->input());
		}
		return response()->json($res, $res["status"]);
	}

	public function modify(Request $request) {
		$res = [
			"status" => 200,
			"errors" => [],
			"results" => [],
		];

		$validations = User::validations(["id", "name", "password"]);
		$validator = Validator::make($request->input(), $validations);
		if ($validator->fails()) {
			$res["status"] = 400;
			$res["errors"] = $validator->errors();

		} else {
			$user = User::find($request->input("id"));
			if (empty($user)) {
				$res["status"] = 400;
				$res["errors"] = $validator->errors()->add("id", "No user found.");

			} else {
				$user->name = $request->input("name");
				$user->password = $request->input("password");
				$user->save();
			}
		}
		return response()->json($res, $res["status"]);

	}



}
