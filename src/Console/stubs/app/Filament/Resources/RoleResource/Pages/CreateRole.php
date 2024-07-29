<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['permissions'] = collect($data['permissions'])->flatten()->toArray();

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        /** @var Role $model */
        $model = parent::handleRecordCreation($data);
        $model->permissions()->sync($data['permissions']);

        return $model;
    }
}
