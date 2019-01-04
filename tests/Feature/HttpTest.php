<?php

namespace Tests\Feature;

use App\Components\CoreComponent\Modules\Client\Client;
use App\Components\CoreComponent\Modules\Loan\Loan;
use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;

class HttpTest extends TestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }
    /**
     * Test post api
     */
    public function testBasicTest()
    {
        DB::beginTransaction();

        // Test post to get clients in pagination
        $response = $this->post('/api/v1/clients/get');
        $response->assertStatus(200);

        // Test post to get client by client id
        $client = Client::first();
        if ($client) {
            $response = $this->post('/api/v1/clients/get/' . $client->getId());
            $response->assertStatus(200);
        } else {
            $this->assertNull($client);
        }
        $response = $this->post('/api/v1/clients/get/' . 9999999);
        $response->assertStatus(404);

        // Test post to get clients
        $response = $this->post('/api/v1/clients/get');
        $response->assertStatus(200);
        $this->assertTrue(\is_array($response->baseResponse->getData(true)['data']));

        // Test post to create client
        $response = $this->post('/api/v1/clients/create', [
            Client::FIRST_NAME => 'test_firstname',
            Client::LAST_NAME => 'test_lastname',
            Client::PHONE_NUMBER => '85512345678',
            Client::ADDRESS => '',
        ]);
        $response->assertStatus(200);
        if ($response->baseResponse->getStatusCode() == 200) {

            // Assert client exists
            $clientId = $response->baseResponse->getData(true)['client']['id'];
            $client = Client::find($clientId);
            $this->assertNotNull($client);

            // Assert fail status of try to duplicate field value
            $response = $this->post('/api/v1/clients/create', $client->toArray());
            $response->assertStatus(400);

            // Test post to get loans of client
            $response = $this->post('/api/v1/loans/get', [
                'clientId' => $client->getId(),
            ]);
            $response->assertStatus(200);
            $loanCount = \count($response->baseResponse->getData(true)['data']);
            $this->assertTrue($loanCount == 0);

            // Test post to get loans of client
            $response = $this->post('/api/v1/loans/create', [
                'clientId' => $client->getId(),
                Loan::AMOUNT => 1000,
                Loan::DURATION => 12,
                Loan::REPAYMENT_FREQUENCY => RepaymentFrequency::MONTHLY['id'],
                Loan::INTEREST_RATE => 0.1,
                Loan::ARRANGEMENT_FEE => 100,
                Loan::REMARKS => null,
                Loan::DATE_CONTRACT_START => Carbon::now() . '',
            ]);
            $response->assertStatus(200);
            $loanId = $response->baseResponse->getData(true)['loan']['id'];
            $loan = Loan::find($loanId);
            $this->assertNotNull($loan);

            // Test post to get loans of client
            $response = $this->post('/api/v1/loans/get', [
                'clientId' => $client->getId(),
            ]);
            $response->assertStatus(200);
            $data = $response->baseResponse->getData(true)['data'];
            $loanCount = \count($data);
            $this->assertTrue($loanCount == 1);

            // Test post to get loan
            $requestId = $data[0]['id'];
            $response = $this->post('/api/v1/loans/get/' . $requestId);
            $response->assertStatus(200);
            $responseId = $response->baseResponse->getData(true)['data']['id'];
            $this->assertTrue($requestId == $responseId);

            // Assert repayments
            $repayments = $loan->repayments;
            $this->assertTrue($loan->getMonthsDuration() == $repayments->count());

            // Assert repay
            foreach ($loan->repayments as $repayment) {
                $response = $this->post('/api/v1/repayments/pay/' . $repayment->getId());
                $response->assertStatus(200);

                // Assert not allow repay for paid repayment
                $response = $this->post('/api/v1/repayments/pay/' . $repayment->getId());
                $response->assertStatus(400);
            }

            // Test post to get loans of client
            $response = $this->post('/api/v1/loans/get_freq_type');
            $response->assertStatus(200);
            $types = RepaymentFrequency::toArrayForApi();
            $response->assertJson([
                "types" => $types,
            ]);
        }

        // Clear current test database records
        DB::rollBack();
    }
}
