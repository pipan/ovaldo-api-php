<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class ReleaseRollback extends Command
{
    protected $signature = 'release:rollback';

    protected $description = 'Rollback to previous release';

    public function handle()
    {
        if (!file_exists('current')) {
            $this->error('link to current release does not exists');
            return;
        }

        $this->info("Rolling back to previous version");

        $currentVersion = (int) basename(readlink('current'));
        $prevVersion = $currentVersion - 1;
        for ($prevVersion; $prevVersion > 0; $prevVersion--) {
            if (!file_exists('releases' . DIRECTORY_SEPARATOR . $prevVersion)) {
                continue;
            }
            exec('ln -sfn releases' . DIRECTORY_SEPARATOR . $prevVersion . ' current');
            exec('rm -rf releases' . DIRECTORY_SEPARATOR . $currentVersion);
            $this->info('Rollback successful');
            return;
        }
        $this->info('Cannot rollback: no previous version found');
    }
}
