<?php

namespace App\Components\CoreComponent\Modules\Client;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

/*
 * Author: Raksa Eng
 */
class ClientController extends Controller
{
    private $repository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->repository = $clientRepository;
    }
    /**
     * Create client via api
     *
     * @param \Illuminate\Http\Request $request
     */
    public function apiCreateClient(Request $request)
    {
        $validator = Validator::make($request->all(), ClientRequest::staticRules(),
            ClientRequest::staticMessages());
        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => trans("default.validation_error"),
                "errors" => $validator->errors(),
            ], 400);
        }
        $client = $this->repository->createClient($request->all());
        if ($client) {
            return response()->json([
                "status" => "success",
                "client" => new ClientResource($client->refresh()),
            ], 200);
        }
        return response()->json([
            "status" => "error",
            "message" => trans("default.save_client_fail"),
        ], 500);
    }

    /**
     * Get client list
     * Get client if client"s id is specified
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\CoreComponent\Modules\Client\Client::ID $id
     */
    public function apiGetClient(Request $request, $id = null)
    {
        $client = Client::find($id);
        if ($client) {
            return new ClientResource($client);
        }
        return new ClientCollection($this->repository->filterClient([
            "perPage" => $request->get("perPage") ?? 20,
        ]));
    }
}
