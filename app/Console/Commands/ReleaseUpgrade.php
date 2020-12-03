<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReleaseUpgrade extends Command
{
    protected $signature = 'release:upgrade';

    protected $description = 'Upgrade to next release';

    public function handle()
    {
        $this->info("Upgrading to next release");
        $currentVersion = (int) basename(readlink('current'));
        $nextVersion = $currentVersion + 1;

        $this->info('cloning git');
        $cmd = 'git clone git@github.com:pipan/ovaldo-api-php.git releases' . DIRECTORY_SEPARATOR . $nextVersion;
        exec($cmd);

        $this->info('installing dependencies');
        $cmd = 'composer install --no-dev -o -d releases' . DIRECTORY_SEPARATOR . $nextVersion;
        exec($cmd);
        exec('ln -sfn releases' . DIRECTORY_SEPARATOR . $nextVersion . ' current');
        $this->info("Upgrade successful");
    }
}
