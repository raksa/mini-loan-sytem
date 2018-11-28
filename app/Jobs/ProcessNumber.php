<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $i;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $i)
    {
        $this->i = $i;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \sleep(\rand(3, 5)); //mock delay time for a such take time execution
        \Log::info('handled at ' . now() . ' for number:' . $this->i);
    }
}
