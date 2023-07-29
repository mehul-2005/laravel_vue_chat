<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user_id = \App\Models\User::factory(2)->create();
        // \App\Models\Role::factory(2)->create();
        // \App\Models\Permission::factory(3)->create();

        for ($i=1; $i <=2 ; $i++) { 
            \App\Models\Role_user::create([
                'role_id' =>$i,
                'user_id'=>$i
            ]);
        }
        for ($i=1; $i <=2 ; $i++) { 
            \App\Models\Permission_user::create([
                'permission_id' =>$i,
                'user_id'=>1
            ]);
        }
        
    }
}
