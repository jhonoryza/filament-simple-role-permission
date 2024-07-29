<h1 align="center">Simple Role Permission Filament</h1>
<p align="center">
    <a href="https://packagist.org/packages/jhonoryza/filament-simple-role">
        <img src="https://poser.pugx.org/jhonoryza/filament-simple-role/d/total.svg" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/jhonoryza/filament-simple-role">
        <img src="https://poser.pugx.org/jhonoryza/filament-simple-role/v/stable.svg" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/jhonoryza/filament-simple-role">
        <img src="https://poser.pugx.org/jhonoryza/filament-simple-role/license.svg" alt="License">
    </a>
</p>

This package is a filament scaffolding for simple role and permission, provides filament resources, models, migration, seeder and policy generator

## Screenshot

<p float="left">
    <img src="/public/create-role.png" width="300" />
    <img src="/public/role.png" width="300" />
</p>
<p float="left">
    <img src="/public/create-permission.png" width="300" />
    <img src="/public/permission.png" width="300" />
</p>

## ERD
<p float="left">
    <img src="/public/erd.png" width="300" />
</p>

## Installation

You can install the package via composer

```bash
composer require --dev jhonoryza/filament-simple-role-permission
```

Then you need to run this command to publish the scaffolding

```bash
php artisan filament-simple-role-permission:install
```

Then you can migrate your database

```bash 
php artisan migrate
``` 

Edit `app\Models\User.php` add `HasRole` trait and `predefined roles`

```php
use App\Models\Concern\HasRole;
class User extends Authenticatable
{
    use HasRole;

    const SUPER = 'super-admin';

    const ADMIN = 'admin';

    public static function getPredefined(): array
    {
        return [
            self::SUPER,
            self::ADMIN,
        ];
    }

    protected $fillable = [
        // ... etc

        'role_id',
    ]
}
```

`const SUPER` is used in `UserSeeder` class

Then seed it with some default role and permission
```bash
php artisan db:seed --class=PermissionSeeder && php artisan db:seed --class=RoleSeeder
```

## Configuration

You can configure predefined permissions by adjust `function getPredefined()` and `function match()` in `Permission` model class like this

```php
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
```

## Generating Policy files

This will generate all policy file in `app\Policies` directory base on `predefined` permissions from Permission model

```bash
php artisan policy:generate
```

## Publishing policy stubs

To customize Policy class template:

```bash
php artisan vendor:publish --tag="simple-role-permission-stubs"
```

## Security

If you discover any security related issues, please create an issue.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.