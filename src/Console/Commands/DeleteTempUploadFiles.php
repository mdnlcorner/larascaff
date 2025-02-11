<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DeleteTempUploadFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-temp-upload-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary uploaded file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (Storage::disk('local')->files('tmp') as $file) {
            $fileLastModified = Carbon::createFromTimestamp(Storage::lastModified($file));
            if ($fileLastModified->diffInMinutes(now()) > config('larascaff.keep_temp_upload_in')) {
                Storage::delete($file);
            }
        }
    }
}
