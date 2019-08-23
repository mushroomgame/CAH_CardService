<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reader = new Xlsx();
        $sheet = $reader->load("storage/cards/CAH.xlsx")->getSheet(0);
        $max = $sheet->getHighestRow() - 1;
        $result = 0;

        $this->command->getOutput()->progressStart($max);

        for ($i = 2; $i <= $max + 1; $i++)
        {
            $type = $sheet->getCell("A$i") == "黑色卡 - 提问卡" ? 'blackcards' : 'whitecards';
            $text = $sheet->getCell("B$i");
            $tags = $sheet->getCell("C$i");

            $result += DB::table($type)->insert([
                'text' => $text,
                'tags' => $tags,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();

        $this->command->info("Successfully imported $result/$max cards.");
    }
}
