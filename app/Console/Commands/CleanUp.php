<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

class CleanUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @translator laravelacademy.org
     */
    protected $signature = 'cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up bad cards';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = DB::table('cards')->where([
            ['votes', '>=', 100],
            [DB::raw('`vote_up`/`votes`'), '<', '0.03']
        ])->update(
            ['enabled' => false]
        );

        $this->info("Cleaned up $result cards.");
    }
}