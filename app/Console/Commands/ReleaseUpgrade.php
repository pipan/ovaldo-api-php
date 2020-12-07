<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReleaseUpgrade extends Command
{
    protected $signature = 'release:upgrade';

    protected $description = 'Upgrade to next release';

    public function handle()
    {
        if (!file_exists('current')) {
            $this->error('link to current release does not exists');
            return;
        }
        $this->info("Upgrading to next release");
        $currentVersion = (int) basename(readlink('current'));
        $nextVersion = $currentVersion + 1;

        $this->info('cloning git');
        $cmd = 'git clone git@github.com:pipan/ovaldo-api-php.git releases' . DIRECTORY_SEPARATOR . $nextVersion;
        exec($cmd);

        $this->info('installing dependencies');
        $cmd = 'composer install --no-dev -o -d releases' . DIRECTORY_SEPARATOR . $nextVersion;
        exec($cmd);

        $this->info('setup storage permissions');
        $cmd = 'chmod -R 777 releases' . DIRECTORY_SEPARATOR . $nextVersion . DIRECTORY_SEPARATOR . 'storage';
        exec($cmd);

        $this->info('coping enviroment config');
        $this->cp(['releases', $currentVersion, '.env'], ['releases', $nextVersion, '.env']);

        exec('ln -sfn releases' . DIRECTORY_SEPARATOR . $nextVersion . DIRECTORY_SEPARATOR . 'public' . ' public/current');
        $this->info("Upgrade successful");
    }

    private function cp($source, $destination)
    {
        $sourcePath = implode(DIRECTORY_SEPARATOR, $source);
        $destinationPath = implode(DIRECTORY_SEPARATOR, $destination);
        exec('cp ' . $sourcePath . ' ' . $destinationPath);
    }
}
