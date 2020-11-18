<?php

namespace App\Http\Controllers;

use App\Models\Benutzer;
use App\Models\Gift;
use App\Models\Relationships;
use App\Models\Wish;
use Illuminate\Http\Request;

class WeihnachtsAPIController extends Controller
{
    public function index() {
        return response()->json("You should not be here.", 400);
    }

    public function targets(Request $request) {
        if(!isset($request->token)) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $user = Benutzer::where("token", $request->token)->first();

        if($user === null) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        return response()->json($user->targets, 200);
    }

    public function user(Request $request) {
        if(!isset($request->token)) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $user = Benutzer::where("token", $request->token)->first();

        if($user === null || $user->count() === 0) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        return response()->json($user, 200);
    }

    public function wishes($userid) {
        return response()->json(Wish::where("from", $userid)->get());
    }

    public function addWish(Request $request) {
        if($request->input("token") === null) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $user = Benutzer::where("token", $request->input("token"))->first();

        if($user === null || $user->count() === 0) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $wish = Wish::create([
            "from" => $user->id,
            "wish" => $request->input("wish")
        ]);

        return response()->json($wish, 200);
    }

    public function deleteWish(Request $request) {
        if($request->input("token") === null) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $user = Benutzer::where("token", $request->input("token"))->first();

        if($user === null || $user->count() === 0) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $wish = Wish::find($request->input("id"));

        if($wish->from !== $user->id) {
            return response()->json(["err" => "Not your wish."], 403);
        }

        if($wish->claimed_by !== null) {
            return response()->json(["err" => "Already claimed."], 403);
        }

        $wish->delete();

        return response()->json(null, 204);
    }

    public function gifters($userid, Request $request) {
        if(!isset($request->token)) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $user = Benutzer::where("token", $request->token)->first();

        $relationshipCheck = Relationships::where("from", $user->id)->where("to", $userid)->get();

        if($user === null || $user->count() === 0 || $relationshipCheck->count() === 0) {
            return response()->json(["err" => "Invalid token / Invalid relationship"], 403);
        }

        $relationship = Relationships::where("to", $userid)->get();

        return response()->json($relationship, 200);
    }

    public function claimWish(Request $request) {
        if($request->input("token") === null) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $user = Benutzer::where("token", $request->input("token"))->first();

        if($user === null || $user->count() === 0) {
            return response()->json(["err" => "Invalid token"], 403);
        }

        $wish = Wish::find($request->input("id"));
        if($wish->count() === 0) {
            return response()->json(["err" => "Invalid wish"], 400);
        }

        $relationship = Relationships::where("from", $user->id)->where("to", $wish->from)->first();
        if($relationship->count() === 0) {
            return response()->json(["err" => "You do not have access to that wish."], 403);
        }

        if($wish->claimed_by !== null) {
            return response()->json(["err" => "Already claimed."], 403);
        }

        $wish->claimed_by = $user->id;
        $wish->save();

        return response()->json($wish, 200);
    }

    public function userinfo($id, Request $request) {
        $user = Benutzer::where("id", $id)->first();

        if($user === null || $user->count() === 0) {
            return response()->json(["err" => "That user does not exist"], 400);
        }

        return response()->json(array(
            "id" => $user->id,
            "name" => $user->name
        ), 200);
    }
}
