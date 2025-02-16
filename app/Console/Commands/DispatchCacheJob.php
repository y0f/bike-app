<?php

namespace App\Console\Commands;

use App\Jobs\OptimizeAndCacheApp;
use Illuminate\Console\Command;

class DispatchCacheJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize-cache-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job to optimize and cache the application resources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new OptimizeAndCacheApp());
        $this->info('Cache optimization job dispatched successfully!');
    }
}
