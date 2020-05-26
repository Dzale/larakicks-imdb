<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            [
                "name" => "admin",
                "permissions" => [
                    ''
                ],
            ],

            [
                "name" => "user",
                "permissions" => [
                    ''
                ],
            ],

        ];
        $permissions = [
            'movie-view-all',
            'movie-view',
            'movie-store',
            'movie-update',
            'movie-destroy',
            'movie-bulk-store',
            'movie-bulk-destroy',
            'actor-view-all',
            'actor-view',
            'actor-store',
            'actor-update',
            'actor-destroy',
            'actor-bulk-store',
            'actor-bulk-destroy',
            'comment-view-all',
            'comment-view',
            'comment-store',
            'comment-update',
            'comment-destroy',
            'comment-bulk-store',
            'comment-bulk-destroy',
            'director-view-all',
            'director-view',
            'director-store',
            'director-update',
            'director-destroy',
            'director-bulk-store',
            'director-bulk-destroy',
            'movie-search-favorited-users',
            'user-search-favorite-movies',
            'user-attach-favorite-movie',
            'user-detach-favorite-movie',
            'movie-search-wishlisted-users',
            'user-search-wishlist-movies',
            'user-attach-wishlist-movie',
            'user-detach-wishlist-movie',
            'actor-search-movies',
            'movie-search-actors',
            'movie-attach-actor',
            'movie-detach-actor',
            'actor-attach-movie',
            'actor-detach-movie',
            'director-search-movies',
            'movie-search-directors',
            'movie-attach-director',
            'movie-detach-director',
            'director-attach-movie',
            'director-detach-movie',
            'user-search-comments'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name'], 'guard_name' => 'web'])
                ->givePermissionTo($role['permissions']);
        }
    }
}
