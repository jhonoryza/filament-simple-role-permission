<?php

namespace App\Models\Concern;

use App\Models\Role;

trait HasRole
{
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermissionTo(string $permission): bool
    {
        return $this->role->permissions()->where('name', $permission)->exists();
    }
}
