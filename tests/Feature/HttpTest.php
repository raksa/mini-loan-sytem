<?php

namespace Tests\Feature;

use App\Components\CoreComponent\Modules\Client\Client;
use App\Components\CoreComponent\Modules\Loan\Loan;
use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use App\Components\CoreComponent\Modules\Repayment\RepaymentRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;

/*
 * Author: Raksa Eng
 */
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
    // FIXME: make test in different function
    public function testBasicTest()
    {
        DB::beginTransaction();

        // Test post to get clients in pagination
        $response = $this->post('/api/v1/clients/get');
        $response->assertStatus(200);
        $responseData = $response->baseResponse->getData(true);
        $this->assertTrue(\is_array($responseData));
        $this->assertTrue(isset($responseData['data']) && isset($responseData['links']) && isset($responseData['meta']));

        // Test post to get client by client id
        $response = $this->post('/api/v1/clients/get/' . 9999999);
        $response->assertStatus(404);

        // Test post to get clients
        $response = $this->post('/api/v1/clients/get');
        $response->assertStatus(200);
        $responseData = $response->baseResponse->getData(true);
        $this->assertTrue(\is_array($responseData));
        $this->assertTrue(isset($responseData['data']) && isset($responseData['links']) && isset($responseData['meta']));

        // Test post to get loans frequency repayment type
        $response = $this->post('/api/v1/loans/get_freq_type');
        $response->assertStatus(200);
        $types = RepaymentFrequency::toArrayForApi();
        $response->assertJson([
            "types" => $types,
        ]);

        // Test post to create client
        $response = $this->post('/api/v1/clients/create', [
            'first_name' => 'test_firstname',
            'last_name' => 'test_lastname',
            'phone_number' => '85512345678',
        ]);
        $response->assertStatus(200);

        // Assert client exists
        $responseData = $response->baseResponse->getData(true);
        $clientId = $responseData['client']['id'];
        $client = Client::active()->find($clientId);
        $this->assertNotNull($client);

        // Assert get client by id
        $response = $this->post('/api/v1/clients/get/' . $client->id);
        $response->assertStatus(200);
        $responseData = $response->baseResponse->getData(true);
        $this->assertTrue(\is_array($responseData));
        $this->assertTrue(isset($responseData['data']));

        // Assert fail status of try to duplicate field value
        $response = $this->post('/api/v1/clients/create', $client->toArray());
        $response->assertStatus(400);

        // Test post to get loans of client
        $response = $this->post('/api/v1/loans/get', [
            'clientId' => $client->id,
        ]);
        $response->assertStatus(200);
        $responseData = $response->baseResponse->getData(true);
        $loanCount = \count($responseData['data']);
        $this->assertTrue($loanCount == 0);

        // Test post to create loan of client
        $date = Carbon::now();
        for ($i = 0; $i < 6; $i++) {
            $date->addMonth($i);
            $response = $this->post('/api/v1/loans/create', [
                'clientId' => $client->id,
                Loan::AMOUNT => 1000,
                Loan::DURATION => 12,
                Loan::REPAYMENT_FREQUENCY => RepaymentFrequency::MONTHLY['id'],
                Loan::INTEREST_RATE => 0.1,
                Loan::ARRANGEMENT_FEE => 100,
                Loan::REMARKS => null,
                Loan::DATE_CONTRACT_START => $date . '',
            ]);
            $response->assertStatus(200);
            $responseData = $response->baseResponse->getData(true);
            $loanId = $responseData['loan']['id'];
            $loan = Loan::find($loanId);
            $this->assertNotNull($loan);

            // Test duplicate generated repayment
            $repaymentRepository = new RepaymentRepository();
            $success = $repaymentRepository->generateRepayments($bag, $loan);
            $this->assertFalse($success);

            // Test post to get loans of client
            $perPage = 2;
            $response = $this->post('/api/v1/loans/get', [
                'perPage' => $perPage,
                'clientId' => $client->id,
            ]);
            $response->assertStatus(200);
            $responseData = $response->baseResponse->getData(true);
            $data = $responseData['data'];
            $loanCount = \count($data);
            $this->assertTrue($loanCount <= $perPage);
            $this->assertTrue($responseData['meta']['total'] == $i + 1);

            // Test post to get loan
            $requestId = $data[0]['id'];
            $response = $this->post('/api/v1/loans/get/' . $requestId);
            $response->assertStatus(200);
            $responseData = $response->baseResponse->getData(true);
            $this->assertTrue(\is_array($responseData));
            $this->assertTrue(isset($responseData['data']));
            $responseId = $responseData['data']['id'];
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
        }

        // Assert client activate
        $client->activate(false);
        $this->assertFalse($client->refresh()->active);
        $client->activate(true);
        $this->assertTrue($client->refresh()->active);

        // Asset client trashed
        $client->delete();
        $this->assertTrue($client->refresh()->trashed());
        $client->restore();
        $this->assertFalse($client->refresh()->trashed());

        // Clear current test database records
        DB::rollBack();
    }
}
