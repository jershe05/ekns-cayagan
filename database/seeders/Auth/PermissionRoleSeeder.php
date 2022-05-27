<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Create Roles
        Role::create([
            'id' => 1,
            'type' => User::TYPE_ADMIN,
            'name' => 'Administrator',
        ]);

        // Non Grouped Permissions
        //

        // Grouped permissions
        // Users category
        $users = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.user',
            'description' => 'All User Permissions',
        ]);

        $users->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.list',
                'description' => 'View Users',
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.deactivate',
                'description' => 'Deactivate Users',
                'sort' => 2,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.reactivate',
                'description' => 'Reactivate Users',
                'sort' => 3,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.clear-session',
                'description' => 'Clear User Sessions',
                'sort' => 4,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.impersonate',
                'description' => 'Impersonate Users',
                'sort' => 5,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.user.change-password',
                'description' => 'Change User Passwords',
                'sort' => 6,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.create.voter',
                'description' => 'Create Voter',
                'sort' => 7,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.update.voter',
                'description' => 'Update Voter',
                'sort' => 8,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.delete.voter',
                'description' => 'Delete Voter',
                'sort' => 9
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.list.voter',
                'description' => 'View Voter List',
                'sort' => 10
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.view.voter',
                'description' => 'View Voter Info',
                'sort' => 11
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.create.leader',
                'description' => 'Create Leader',
                'sort' => 12
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.update.leader',
                'description' => 'Update Leader',
                'sort' => 13
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.delete.leader',
                'description' => 'Delete Leader',
                'sort' => 14
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.list.leader',
                'description' => 'View Leader List',
                'sort' => 15
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.view.leader',
                'description' => 'View Leader Info',
                'sort' => 16
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.create.candidate',
                'description' => 'Create Candidate',
                'sort' => 17
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.update.candidate',
                'description' => 'Update Candidate',
                'sort' => 18
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.delete.candidate',
                'description' => 'Delete Candidate',
                'sort' => 19
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.list.candidate',
                'description' => 'View Candidate List',
                'sort' => 20
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.add.cash.voter',
                'description' => 'Add Cash To Voters',
                'sort' => 21
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.add.cash.leader',
                'description' => 'Add Cash To Leaders',
                'sort' => 22
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.roles',
                'description' => 'View Roles',
                'sort' => 23
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.view.candidate',
                'description' => 'View Candidate Info',
                'sort' => 24
            ]),
        ]);

        // Assign Permissions to other Roles
        //

        $this->enableForeignKeys();
    }
}
