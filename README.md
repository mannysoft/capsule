# Laravel 4 Database Package Wrapper

A simple wrapper class for the Laravel Database package.  This is only to be used outside of a Laravel application.

## Usage

### Setup

Before you can make any Db calls or use any Eloquent Models, you must first make a connection using the `Db::makeConnection` method.

    DbWrapper\Db::makeConnection('main', [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => '',
        'username'  => '',
        'password'  => '',
        'collation' => 'utf8_general_ci',
        'prefix'    => '',
    ], true);

The `makeConnection` method has the following prototype:

    public static function makeConnection($name, array $config, $default = false)

### Using the Query Builder

You can use the Query Builder just as you would using the `Db` Facade in Laravel 4:

    DbWrapper\Db::table('foo')->select('*')->get()

**Note: You can `use DbWrapper` in your PHP files so you can simply use `Db` without the namespace.**

### Eloquent Models

You can extend the `Illuminate\Database\Eloquent\Model` class and use the Models as you normally would.  When `makeConnection` is called it also sets up Eloquent for you.
