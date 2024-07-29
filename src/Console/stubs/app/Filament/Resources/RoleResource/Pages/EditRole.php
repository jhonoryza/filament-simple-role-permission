<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['permissions'] = collect($data['permissions'])->flatten()->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);
        $record->permissions()->sync($data['permissions']);

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['permissions'] = $this->record
            ->permissions()
            ->select([
                'permissions.*',
                DB::raw('SUBSTRING_INDEX(name, ".", 1) as perm_name'),
            ])
            ->get()
            ->groupBy('perm_name')
            ->map(function ($permissions) {
                return $permissions->pluck('id');
            })
            ->toArray();

        return $data;
    }
}
