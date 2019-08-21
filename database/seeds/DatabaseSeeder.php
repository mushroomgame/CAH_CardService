<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cards')->insert([
            'type' => 'whitecards',
            'text' => '唐泽贵阳',
            'tags' => '["niconico", "日本", "inm"]'
        ]);

        DB::table('cards')->insert([
            'type' => 'blackcards',
            'text' => '如果有_就好了',
            'tags' => '["动画", "日本"]'
        ]);
    }
}
