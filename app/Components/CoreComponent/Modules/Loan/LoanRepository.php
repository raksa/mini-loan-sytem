<?php
namespace App\Components\CoreComponent\Modules\Loan;

use App\Components\CoreComponent\Modules\Client\Client;

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
        $result = Loan::orderBy(Loan::ID, 'desc');
        if (isset($data["client"])) {
            $client = $data["client"];
            $result = $result->where(Loan::CLIENT_ID, $client->getId());
        }
        $loans = $result->paginate($data['perPage']);
        return $loans;
    }

    /**
     * Create Loan
     *
     * @param \App\Components\CoreComponent\Modules\Client\Client $client
     * @param array $data
     * @return \App\Components\CoreComponent\Modules\Loan\Loan|null
     */
    public function createLoan(Client $client, $data = [])
    {
        $data[Loan::CLIENT_ID] = $client->getId();
        $loan = new Loan();
        $loan->setProps($data);
        return $loan->save() ? $loan : null;
    }
}
