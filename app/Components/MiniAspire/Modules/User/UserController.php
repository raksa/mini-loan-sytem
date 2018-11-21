<?php

namespace App\Components\MiniAspire\Modules\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

/*
 * Author: Raksa Eng
 */
class UserController extends Controller
{
    /**
     * Create user via api
     *
     * @param \Illuminate\Http\Request $request
     */
    public function apiCreateUser(Request $request)
    {
        $validator = Validator::make($request->all(), UserRequest::staticRules(),
            UserRequest::staticMessages());
        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => trans("default.validation_error"),
                "errors" => $validator->errors(),
            ], 400);
        }
        $user = new User();
        $user->setProps($request->all());
        if ($user->save()) {
            return response()->json([
                "status" => "success",
                "user" => new UserResource($user->refresh()),
            ], 200);
        }
        return response()->json([
            "status" => "error",
            "message" => trans("default.save_user_fail"),
        ], 500);
    }

    /**
     * Get user list
     * Get user if user"s id is specified
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\MiniAspire\Modules\User\User::ID $id
     */
    public function apiGetUser(Request $request, $id = null)
    {
        $user = User::find($id);
        if ($user) {
            return new UserResource($user);
        }
        return new UserCollection(User::filterUser([
            "perPage" => $request->get("perPage") ?? 20,
        ]));
    }
}
