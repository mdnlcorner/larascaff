<?php

namespace Mulaidarinull\Larascaff\Console\Commands;

use Mulaidarinull\Larascaff\Seeders\LarascaffSeeder;

class LarascaffSeederCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larascaff:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tables for larascaff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Sedding users, menu & roles');
        $this->components->task(LarascaffSeeder::class, new LarascaffSeeder);
    }
}
