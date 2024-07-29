<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Role & Permission';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $predefinedPermissions = DB::table('permissions')
            ->select([
                'permissions.*',
                DB::raw('SUBSTRING_INDEX(name, ".", 1) as perm_name'),
                DB::raw('SUBSTRING_INDEX(name, ".", -1) as perm_act'),
            ])
            ->get()
            ->groupBy('perm_name');
        $formPermissions = [];
        foreach ($predefinedPermissions as $label => $permissions) {
            $formPermissions[] = Forms\Components\CheckboxList::make('permissions.'.$label)
                ->label(Str::title(Str::singular($label)))
                ->options($permissions->pluck('perm_act', 'id'))
                ->bulkToggleable()
                ->reactive()
                ->afterStateHydrated(function (Get $get, Set $set) use ($predefinedPermissions) {
                    $checkAll = true;
                    foreach ($get('permissions') as $label => $permissions) {
                        if (count($permissions) === 0 || count($permissions) !== count($predefinedPermissions[$label])) {
                            $checkAll = false;
                            break;
                        }
                    }
                    $set('check-all', $checkAll);
                })
                ->afterStateUpdated(function (Get $get, Set $set) use ($predefinedPermissions) {
                    $checkAll = true;
                    foreach ($get('permissions') as $label => $permissions) {
                        if (count($permissions) === 0 || count($permissions) !== count($predefinedPermissions[$label])) {
                            $checkAll = false;
                            break;
                        }
                    }
                    $set('check-all', $checkAll);
                });
        }

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Section::make('Permissions')
                    ->schema([
                        Checkbox::make('check-all')
                            ->label('Check All Permissions')
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state) use ($predefinedPermissions) {
                                if ($state === true) {
                                    foreach ($predefinedPermissions as $label => $permission) {
                                        $set('permissions.'.$label, $permission->pluck('id')->toArray());
                                    }
                                } else {
                                    foreach ($predefinedPermissions as $label => $permission) {
                                        $set('permissions.'.$label, []);
                                    }
                                }
                            })
                            ->default(false),
                        Forms\Components\Section::make()
                            ->columns(5)
                            ->schema($formPermissions),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            /** @var Role $record */
                            foreach ($records as $record) {
                                $record->permissions()->detach();
                                $record->delete();
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
