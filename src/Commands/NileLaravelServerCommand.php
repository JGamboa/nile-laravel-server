<?php

namespace JGamboa\NileLaravelServer\Commands;

use Illuminate\Console\Command;

class NileLaravelServerCommand extends Command
{
    public $signature = 'nile-server:about';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
