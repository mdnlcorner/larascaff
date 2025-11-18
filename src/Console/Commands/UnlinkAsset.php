<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Console\Command;

class UnlinkAsset extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'larascaff:unlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete existing symbolic links configured for the application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->links() as $link => $target) {
            if (!file_exists($link) || !is_link($link)) {
                continue;
            }

            $this->laravel->make('files')->delete($link);

            $this->components->info("The [$link] link has been deleted.");
        }
    }

    /**
     * Get the symbolic links that are configured for the application.
     *
     * @return array
     */
    protected function links()
    {
        return [public_path('larascaff') => base_path('/vendor/mulaidarinull/larascaff/dist')];
    }
}
