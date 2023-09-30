<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class StartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('passport:install');

        DB::table('oauth_clients')->where('id', 2)->update([
            'secret' => '10XwHjzlOKJpDEmodZEjOjAB2MyNvZ7zFxyzz2bY'
        ]);

       $this->call(RolesSeeder::class);
       $this->call(UserSeeder::class);
    }
}