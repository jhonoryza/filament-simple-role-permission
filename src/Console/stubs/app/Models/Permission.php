<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public static function getPredefined(): array
    {
        return [
            'users' => [
                'view-any',
                'view',
                'create',
                'update',
                'delete',
                'bulk-delete',
            ],
            'roles' => [
                'view-any',
                'view',
                'create',
                'update',
                'delete',
                'bulk-delete',
            ],
            'permissions' => [
                'view-any',
                'view',
                'create',
                'update',
                'delete',
                'bulk-delete',
            ],
        ];
    }

    public static function match($permission): string
    {
        return match ($permission) {
            'view-any' => 'viewAnyPermission',
            'view' => 'viewPermission',
            'create' => 'createPermission',
            'update' => 'updatePermission',
            'delete' => 'deletePermission',
            'bulk-delete' => 'bulkDeletePermission',
            default => '',
        };
    }

    protected $fillable = ['name'];
}
