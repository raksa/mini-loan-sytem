<?php

namespace App\Console\Commands;

use App\Jobs\ProcessNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TestQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:testqueue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test working of queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $queueNumber = 'number';
        // mock adding queue work
        \Log::info('Dispatched at ' . now());
        for ($i = 0; $i < 10; $i++) {
            ProcessNumber::dispatch($i)->onQueue($queueNumber);
        }
        $this->info('Jobs have been dispatch');
        $this->info('Jobs will be proceed in next 2 seconds');

        sleep(2); //delay to wait until database storing done
        $this->info('Jobs are processing...');
        Artisan::call('queue:work',
            [
                'database',
                '--queue' => $queueNumber,
                '--stop-when-empty' => true,
            ]);
    }
}
