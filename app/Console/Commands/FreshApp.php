<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FreshApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart the app';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->production() && !$this->continue()) {
            return;
        }

        $this->freshDatabase();

        $this->seedDatabase();

        $this->deleteMedia();

        $this->callSilent('optimize:clear');
    }

    private function production()
    {
        return env('APP_ENV', 'local') == 'production';
    }

    private function continue()
    {
        return $this->confirm('Do you want to restart app`?', false);
    }

    private function freshDatabase()
    {
        $this->comment('fresh database: ');
        $this->call('migrate:fresh');
    }

    private function seedDatabase()
    {
        $this->comment('seed database: ');
        $this->call('db:seed');
    }

    private function deleteMedia()
    {
        $this->comment('delete media...');

        File::deleteDirectory(config('filesystems.disks.media.root'));

        $this->comment('Done!');
    }
}
