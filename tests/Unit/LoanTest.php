<?php

namespace Tests\Unit;

use App\Helpers\LoanCalculator;
use App\Http\Middleware\AuthenticateAPIOnce;
use Illuminate\Foundation\Testing\TestCase;

class LoanTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        return $app;
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // Fail connection to database after HttpTest released
        // $userCode = User::generateUserCode();
        // $this->assertTrue(!!\preg_match('/^[a-z]+[0-9]{6}$/', $userCode));

        // Assert calculation
        $amount = LoanCalculator::calculateMonthlyRepayment(1000, 0.1, 12);
        $this->assertEquals((int) $amount, 83);

        // Assert api authorization
        $saltKey = 'saltkey';
        $timestamp = 1542848522;
        $hash = AuthenticateAPIOnce::dataToTokenHash([
            'key' => 'value',
        ], $saltKey, $timestamp);
        $this->assertEquals($hash, 'MEJRYlhoWVJnQTVrUmlSUVRVbVl5MA==');
    }
}
