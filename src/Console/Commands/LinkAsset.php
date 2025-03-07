<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LinkAsset extends Command
{
    protected $signature = 'larascaff:link-asset';

    protected $description = 'Link asset';

    public function handle()
    {
        $link = base_path('/vendor/mulaidarinull/larascaff/dist');
        $target = public_path('larascaff');

        if (File::exists($target) && $this->isRemovableSymlink($target)) {
            return $this->components->error("The [$target] link already exists.");
        }

        File::link($link, $target);
        $this->components->info("The [$link] link has been connected to [$target].");
    }

    protected function isRemovableSymlink(string $link): bool
    {
        return is_link($link);
    }
}
