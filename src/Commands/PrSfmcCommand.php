<?php

namespace Dmgroup\PrSfmc\Commands;

use Illuminate\Console\Command;

class PrSfmcCommand extends Command
{
    public $signature = 'pr-sfmc';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
