<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupTemporaryFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old temporary files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = Storage::files('/temp/reports');
        $now = Carbon::now();
        $count = 0;

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::lastModified($file));

            if ($now->diffInHours($lastModified) >= 24) {
                Storage::delete($file);
                $count++;
            }
        }

        $this->info("Cleaned up {$count} old report files.");
    }
}
