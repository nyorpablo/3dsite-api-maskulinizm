<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetUsageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-usage-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command resets the usage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
