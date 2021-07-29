<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Job;
use App\Models\User;


class JobController extends Controller
{
	public function list(Request $request, $usr_id) {
		$res = [
			"status" => 200,
			"errors" => [],
			"results" => [],
		];

		$validations = User::validations(["id"]);
		$validator = Validator::make(["id" => $usr_id], $validations);
		if ($validator->fails()) {
			$res["status"] = 400;
			$res["errors"] = $validator->errors();

		} else {
			$data = User::with("jobs")->find($usr_id);
			if (empty($data)) {
				$res["status"] = 400;
				$res["errors"] = $validator->errors()->add("id", "No user found.");

			} else {
				$res["results"] = $data;
			}
		}
		return response()->json($res, $res["status"]);
	}

	public function add(Request $request) {
		$res = [
			"status" => 200,
			"errors" => [],
			"results" => [],
		];
		$validations = Job::validations(["usr_id","name","status"]);
		$validator = Validator::make($request->input(), $validations);
		if ($validator->fails()) {
			$res["status"] = 400;
			$res["errors"] = $validator->errors();

		} else {
			Job::create($request->input());
		}
		return response()->json($res, $res["status"]);
	}

}
