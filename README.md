# Hybrid\\Theme

Hybrid Theme is an add-on package for the [Hybrid Core](https://github.com/justintadlock/hybrid-core) WordPress theme framework that creates extra controls and settings that theme authors can use to build their themes.

## Requirements

* WordPress 4.9+.
* PHP 5.6+ (preferably 7+).
* [Composer](https://getcomposer.org/) for managing PHP dependencies.

## Documentation

This project is only meant to work in conjunction with the Hybrid Core framework.  If you're not already working with and building a theme using it, the following will be useless.

### Installation

First, you'll need to open your command line tool and change directories to your theme folder.

```bash
cd path/to/wp-content/themes/<your-theme-name>
```

Then, use Composer to install the package.

```bash
composer require justintadlock/hybrid-theme
```

### Register the service provider

You need to register the service provider during your bootstrapping process.  In your bootstrapping code, you should have something like the following:

```php
$app = new \Hybrid\Core\Application();
```

After that point, you can register the service provider:

```php
$app->provider( \Hybrid\Theme\Provider::class );
```

This is basically going to set up and ready any JS-based customizer controls.

## Copyright and License

This project is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

2021 &copy; [Justin Tadlock](http://justintadlock.com).
