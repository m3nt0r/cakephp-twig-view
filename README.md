# Twig for CakePHP (v0.8.0)

This plugin for the CakePHP Framework allows you to use the Twig, the flexible, fast, and secure
template engine for PHP.

Apart from enabling you to use most of Twig's features the plugin is tightly integrated with 
the CakePHP view renderer giving you full access to helpers, objects and elements.

## New in this version

- Using Composer for dependency management

## Installation

#### via Archive

Download the [current version as ZIP](https://github.com/m3nt0r/cakephp-twig-view/zipball/master), unzip it and rename the extracted folder to `twig`. Then put the `twig` folder inside your `plugins` and you are done.

#### via GitHub

Just clone the repository directly into your `plugins` folder as `twig`.

    $ git clone https://github.com/m3nt0r/cakephp-twig-view.git twig

## Twig Version Support

This plugin now uses [Composer](https://getcomposer.org/) to manage dependencies. 
Simply running "composer install" will fetch the required libraries.

## Enabling the View

Inside your ```app_controller.php``` add the following:

```php
class AppController extends Controller {
    public $view = 'Twig.Twig'; // use twig
}
```

Now start by creating twig templates using the `.tpl` extension. 

### Fallback / Upgrading exsiting apps

By default the view will render any existing `.ctp` like always but 
will prefer the `.tpl` version if present. This means that, for example, 
the `index.ctp` is used until you create a `index.tpl` at the same location. 

That way you can upgrade existing views one at a time instead of having 
to rework all files at once.
 
## Documentation

Check the [wiki](https://github.com/m3nt0r/cakephp-twig-view/wiki) for more details on features and usage.

## Version History

This version was written on **CakePHP 1.3**. 

### v0.8

- Replaced "TWIG_DIR" method with composer. 
- Removed included Twig library
- Minor refactoring

### v0.7.1

- Added TWIG_DIR so you can define the full Twig root-directory name
- Deprecated TWIG_VERSION because it lost its purpose due to this change
- Added fabpot/Twig as a submodule of this git repository

### v0.7

- Complete rewrite
- Latest Twig (v1.8.3 atm)
- Easy to upgrade
- Easy to install
- Easy to migrate
- Extendable (see wiki)
- More CakePHP Filters

## CakePHP 2

Support for 2.x may be limited to non-existant. 
If you run on 2.x i suggest that you check out the [fork of predominant](https://github.com/predominant/TwigView).

## License

MIT License  
Copyright (c) 2010-2014, Kjell Bublitz.
