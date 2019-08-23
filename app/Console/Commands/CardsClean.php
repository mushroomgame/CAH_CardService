<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

class CardsClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @translator laravelacademy.org
     */
    protected $signature = 'cards:clean';

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
        foreach (['whitecards', 'blackcards'] as $type)
        {
            //将100投票以上且支持率低于3%的卡自动弃置
            $result = DB::table($type)->where([
                ['plays', '>=', 100],
                [DB::raw('`votes`/`plays`'), '<', '0.03']
            ])->update(
                ['status' => 0],
                ['updated_at' => date('Y-m-d H:i:s')]
            );

            $this->info("Disabled $result $type.");
            
            //自动删除30天以上的禁用卡
            $result = DB::table($type)->where([
                ['status', '=',  0],
                ['updated_at', '<', date('Y-m-d H:i:s', time() - 2592000)]
            ])->delete();

            $this->info("Deleted $result $type.");
        }
    }
}