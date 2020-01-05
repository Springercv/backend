<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PreferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('preferences')->truncate();
        $arrData = [
            ['name' => 'Mountain'],
            ['name' => 'Beach'],
            ['name' => 'River'],
            ['name' => 'Valley'],
            ['name' => 'Dessert'],
            ['name' => 'Ocean'],
            ['name' => 'Sunset'],
            ['name' => 'Snow'],
            ['name' => 'Volcano'],
        ];

        \DB::table('preferences')->insert($arrData);
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
