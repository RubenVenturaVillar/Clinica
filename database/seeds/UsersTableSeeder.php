<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	User::create([
        'name' => 'Rubén Ventura',
        'email' => 'waser_ruben@hotmail.com',
        'password' => bcrypt('123456'), // secret
        'dni'=> ' ',
        'address' => ' ',
        'phone' => ' ',
        'rol' => 'admin'
        ]);
        factory(User::class, 50)->create();
    	
    }
}
