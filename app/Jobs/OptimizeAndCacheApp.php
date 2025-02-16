<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class OptimizeAndCacheApp implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $commands = [
            'filament:optimize' => 'Optimize Filament resources',
            'icons:cache' => 'Cache icons for Filament',
            'filament:cache-components' => 'Cache Filament components',
            'cache:clear' => 'Clear application cache',
            'config:clear' => 'Clear configuration cache',
            'route:clear' => 'Clear route cache',
            'view:clear' => 'Clear compiled views',
            'optimize' => 'Optimize the application',
        ];

        foreach ($commands as $command => $description) {
            try {
                Log::info("Running command: $description...");
                Artisan::call($command);
                Log::info("Command $command executed successfully.");
            } catch (\Exception $e) {
                Log::error("Failed to execute $command: {$e->getMessage()}");
            }
        }
    }
}
