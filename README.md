# Package to connect to nile.dev PG Multi tenant

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jgamboa/nile-laravel-server.svg?style=flat-square)](https://packagist.org/packages/jgamboa/nile-laravel-server)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jgamboa/nile-laravel-server/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jgamboa/nile-laravel-server/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jgamboa/nile-laravel-server/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jgamboa/nile-laravel-server/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jgamboa/nile-laravel-server.svg?style=flat-square)](https://packagist.org/packages/jgamboa/nile-laravel-server)


# Nile Laravel Server

This package provides tools to support multi-tenancy in Laravel applications using PostgreSQL with context-based isolation [thenile.dev].

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/nile-laravel-server.jpg?t=1" width="419px" />]

## Installation

You can install the package via composer:

```bash
composer require jgamboa/nile-laravel-server
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="nile-laravel-server-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="nile-laravel-server-config"
```

This is the contents of the published config file:

```php
return [
];
```

or use the command install from the package: will copy config(beta), copy migrations and ask to run it:
```bash
php artisan nile-server:install
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="nile-laravel-server-views"
```

## Usage

### 1. Tenant Context Middleware: `NileContextMiddleware`

This middleware reads the `x-nile-tenant-id` header and injects it into Laravel's application container using `App::instance('tenant_id', $id)`.

**Usage:**

```php
use JGamboa\NileLaravelServer\Http\Middleware\NileContextMiddleware;

Route::middleware([NileContextMiddleware::class])->group(function () {
    // Routes protected by tenant context
});
```


### 2. Execute Queries in Tenant Context: TenantDB::run()

Use the TenantDB service to run queries that depend on the current tenant ID.

**Usage:**

```php
use JGamboa\NileLaravelServer\Services\TenantDB;

TenantDB::run(function ($db) {
    return $db->table('users')->get();
});
```

This internally sets the tenant ID using: set local nile.tenant_id = ?.


### 3. Multi-tenant Models: BelongsToTenant Trait

Apply the BelongsToTenant trait to models that need to be scoped to a tenant.

**Usage:**

```php
use JGamboa\NileLaravelServer\Traits\BelongsToTenant;

class BranchOffice extends Model
{
    use BelongsToTenant;
}
```

This trait adds a global scope and automatically fills the tenant_id field during creation.

### 4. Tenant Model: Extend NileTenantModel

You must create a model named Tenant that extends NileTenantModel. This provides support for UUIDs and custom timestamps.


**Usage:**

```php
use JGamboa\NileLaravelServer\Models\NileTenantModel;

class Tenant extends NileTenantModel
{
    protected $table = 'tenants';
}
```

The base model includes:

- Automatic UUIDs  
- Soft deletes  
- Custom timestamp fields (`created`, `modified`, `deleted`)  


### 4. User Model: Extend NileUserModel

You must modify laravel base User Model to extends NileUserModel. This provides support for UUIDs and custom timestamps.

**Usage:**
```php
use JGamboa\NileLaravelServer\Models\NileUserModel;

class User extends NileUserModel
{
    // aquí puedes extender comportamiento o relaciones
}
```

The base model includes:

- Automatic UUIDs  
- Soft deletes  
- Custom timestamp fields (`created`, `modified`, `deleted`)  
- `hasVerifiedEmail()` and `markEmailAsVerified()` functions adapted for Nile's `email_verified` column  


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Joaquín Gamboa](https://github.com/JGamboa)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
