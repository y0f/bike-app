<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RunStaticAnalysis extends Command
{
    protected $signature = 'analyze:static';
    protected $description = 'Run PHPStan and Pint in one command';

    public function handle()
    {
        $isWindows = PHP_OS_FAMILY === 'Windows';

        $commands = $isWindows
            ? [
                'php vendor/phpstan/phpstan/phpstan analyse',
                'php vendor/bin/pint',
            ]
            : [
                './vendor/bin/phpstan analyse',
                './vendor/bin/pint',
            ];

        foreach ($commands as $command) {
            $this->info('Running: ' . $command);

            $process = Process::fromShellCommandline($command);
            $process->setTimeout(null);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->error($process->getErrorOutput());
                return Command::FAILURE;
            }

            $this->info($process->getOutput());
        }

        $this->info('PHPStan and Pint completed successfully!');
        return Command::SUCCESS;
    }
}
