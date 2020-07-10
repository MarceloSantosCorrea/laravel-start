<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('passport:install', [
            '--uuids' => false,
            '--force' => true,
        ]);
        factory(\App\Models\User::class, 1)->create([
            'name'     => 'Marcelo Corrêa',
            'email'    => 'marcelocorrea229@gmail.com',
            'password' => Hash::make('Marsc2014'),
//            'is_admin' => true,
        ]);
    }
}
