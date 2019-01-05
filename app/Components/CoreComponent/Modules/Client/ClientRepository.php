<?php
namespace App\Components\CoreComponent\Modules\Client;

/*
 * Author: Raksa Eng
 */
class ClientRepository
{
    /**
     * Filter Client as pagination
     *
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function filterClient($data = [])
    {
        $result = Client::active()->orderDesc();
        $clients = $result->paginate($data['perPage']);
        return $clients;
    }

    /**
     * Create Client
     *
     * @param array $bag
     * @param array $data
     * @return \App\Components\CoreComponent\Modules\Client\Client|null
     */
    public function createClient(&$bag, $data = [])
    {
        $client = new Client();
        $client->fill([
            'client_code' => $this->generateClientCode(),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone_number' => $data['phone_number'],
            'address' => isset($data['address']) ? $data['address'] : null,
        ]);
        if (!$client->save()) {
            $bag = ['message' => trans("default.client_cannot_save")];
            return null;
        }
        return $client;
    }

    /**
     * Generate Client code
     */
    public function generateClientCode()
    {
        $latestClientRecord = Client::withTrashed()->orderDesc()->first();
        if (!$latestClientRecord) {
            return 'a000000';
        }
        $clientCode = $latestClientRecord->client_code;
        while (true) {
            if (!Client::where('client_code', ++$clientCode)->exists()) {
                break;
            }
        }
        return $clientCode;
    }
}
