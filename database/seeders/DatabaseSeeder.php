<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function (){
            /*edit ship permission*/
            $editShipPerm = Permission::create([
               'name' => 'edit ship'
            ]);

            # admin section
            $permissionNames = array(
                'delete account',
                'edit account',
                'verificate account',
                'delete ship',
                'verificate ship'
            );
            foreach ($permissionNames as $permissionName) {
                $adminPermissions[] = Permission::create([
                    'name' => $permissionName]
                );
            }
            $admin = Role::create(['name' => 'admin']);
            $adminPermissions[] = $editShipPerm;
            $admin->syncPermissions($adminPermissions);
            $adminAccount = User::create([
               'name' => 'Admin',
               'email' => 'admin@test.com',
               'password' => Hash::make('admintest'),
               'email_verified_at' => Carbon::now(),
               'is_approved' => true
            ]);
            $adminAccount->assignRole($admin);



            # user section
            $userPermissionNames = array(
                'create ship',
                'edit profile'
            );
            $user = Role::create(['name' => 'user']);
            foreach ($userPermissionNames as $permissionName) {
                $userPermissions[] = Permission::create([
                    'name' => $permissionName
                ]);
            }
            $userPermissions[] = $editShipPerm;
            $user->syncPermissions($userPermissions);
        });
    }
}
