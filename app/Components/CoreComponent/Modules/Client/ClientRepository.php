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
        $result = Client::orderBy(Client::ID, 'desc');
        $clients = $result->paginate($data['perPage']);
        return $clients;
    }

    /**
     * Create Client
     *
     * @param array $data
     * @return \App\Components\CoreComponent\Modules\Client\Client|null
     */
    public function createClient($data = [])
    {
        $data[Client::CLIENT_CODE] = $this->generateClientCode();
        $client = new Client();
        $client->setProps($data);
        return $client->save() ? $client : null;
    }

    /**
     * Generate Client code
     */
    public function generateClientCode()
    {
        $latestClientRecord = Client::orderBy(Client::ID, 'desc')->first();
        if (!$latestClientRecord) {
            return 'a000000';
        }
        $clientCode = $latestClientRecord->getClientCode();
        while (true) {
            if (!Client::where(Client::CLIENT_CODE, ++$clientCode)->exists()) {
                break;
            }
        }
        return $clientCode;
    }
}
