<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class OrganizationPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $perms = [
            ['name' => 'organization_settings_view', 'guard_name' => 'web'],
            ['name' => 'invite_to_organization', 'guard_name' => 'web'],
            ['name' => 'organization_member_list', 'guard_name' => 'web'],
            ['name' => 'member_type_change', 'guard_name' => 'web'],
            ['name' => 'organization_list', 'guard_name' => 'web'],
            ['name' => 'organization_edit', 'guard_name' => 'web'],
            ['name' => 'members_list', 'guard_name' => 'web'],
        ];

        foreach($perms as $key => $per)
        {
            DB::table('permissions')->insert($per);
        }

        $rows = [
          'level_1' => [ //1
            'organization_settings_view', 
            'organization_member_list',
            'organization_list',
            'members_list'
          ],
          'level_2' => [ 
            'organization_settings_view',
            'invite_to_organization',
            'organization_member_list',
            'member_type_change',
            'organization_list',
            'organization_edit',
            'members_list',
          ],
          'level_3' => [            
            'organization_settings_view',
            'invite_to_organization',
            'organization_member_list',
            'member_type_change',
            'organization_list',
            'organization_edit',
            'members_list',            
          ],
          'User' => [            
            'organization_settings_view',
            'invite_to_organization',
            'organization_member_list',
            'member_type_change'
          ],          
        ];

        foreach ($rows as $role_name => $permissions) {
          $role = Role::findByName($role_name);
          foreach ($permissions as $id => $permission) {
            $role->givePermissionTo($permission);
          }
        }        

    }
}
