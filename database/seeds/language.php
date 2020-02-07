<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class language extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['shortcut' => 'en'],
            ['shortcut' => 'ar'],
        ];
        DB::table('language')->insert($languages);
    }
}
