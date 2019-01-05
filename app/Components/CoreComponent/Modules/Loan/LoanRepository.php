<?php
namespace App\Components\CoreComponent\Modules\Loan;

use App\Components\CoreComponent\Modules\Client\Client;
use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use Carbon\Carbon;

/*
 * Author: Raksa Eng
 */
class LoanRepository
{
    /**
     * Filter Loan as pagination
     *
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function filterLoan($data = [])
    {
        $result = Loan::active()->orderDesc();
        if (isset($data["client"])) {
            $client = $data["client"];
            $result = $result->where('client_id', $client->id);
        }
        $loans = $result->paginate($data['perPage']);
        return $loans;
    }

    /**
     * Create Loan
     *
     * @param array $bag
     * @param \App\Components\CoreComponent\Modules\Client\Client $client
     * @param array $data
     * @return \App\Components\CoreComponent\Modules\Loan\Loan|null
     */
    public function createLoan(&$bag, Client $client, $data = [])
    {
        if (!RepaymentFrequency::isValidType($data['repayment_frequency'])) {
            $bag = ['message' => trans('default.repayment_frequency_type_invalid')];
            return null;
        }
        $loan = new Loan();
        $dateContractStart = new Carbon($data['date_contract_start']);
        $dateContractEnd = $dateContractStart->copy()->addMonth($data['duration']);
        $loan->fill([
            'client_id' => $client->id,
            'amount' => $data['amount'],
            'duration' => $data['duration'],
            'repayment_frequency' => $data['repayment_frequency'],
            'interest_rate' => $data['interest_rate'],
            'arrangement_fee' => $data['arrangement_fee'],
            'remarks' => $data['remarks'],
            'date_contract_start' => $dateContractStart . '',
            'date_contract_end' => $dateContractEnd . '',
        ]);
        if (!$loan->save()) {
            $bag = ['message' => trans("default.loan_cannot_save")];
            return null;
        }
        return $loan;
    }
}
