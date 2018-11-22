<?php

namespace Tests\Feature;

use App\Components\MiniAspire\Modules\Loan\Loan;
use App\Components\MiniAspire\Modules\Repayment\RepaymentFrequency;
use App\Components\MiniAspire\Modules\User\User;
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

        // Test post to get users in pagination
        $response = $this->post('/api/v1/users/get');
        $response->assertStatus(200);

        // Test post to get user by user id
        $user = User::first();
        if ($user) {
            $response = $this->post('/api/v1/users/get/' . $user->getId());
            $response->assertStatus(200);
        } else {
            $this->assertNull($user);
        }

        // Test post to create user
        $response = $this->post('/api/v1/users/create', [
            User::FIRST_NAME => 'test_firstname',
            User::LAST_NAME => 'test_lastname',
            User::PHONE_NUMBER => '85512345678',
            User::ADDRESS => '',
        ]);
        $response->assertStatus(200);
        if ($response->baseResponse->getStatusCode() == 200) {

            // Assert user exists
            $userId = $response->baseResponse->getData(true)['user']['id'];
            $user = User::find($userId);
            $this->assertNotNull($user);

            // Assert fail status of try to duplicate field value
            $response = $this->post('/api/v1/users/create', $user->toArray());
            $response->assertStatus(500);

            // Test post to get loans of user
            $response = $this->post('/api/v1/loans/get/' . $user->getId());
            $response->assertStatus(200);

            // Test post to get loans of user
            $response = $this->post('/api/v1/loans/create/' . $user->getId(), [
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

            // Assert repayments
            $this->assertTrue($loan->getMonthsDuration() == \count($loan->repayments));

            // Assert repay
            foreach ($loan->repayments as $repayment) {
                $response = $this->post('/api/v1/repayments/pay/' . $repayment->getId());
                $response->assertStatus(200);

                // Assert not allow repay for paid repayment
                $response = $this->post('/api/v1/repayments/pay/' . $repayment->getId());
                $response->assertStatus(400);
            }

            // Test post to get loans of user
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
