# Twig for CakePHP (v0.7.1)

This plugin for the CakePHP Framework allows you to use the Twig, the flexible, fast, and secure
template engine for PHP.

Apart from enabling you to use most of Twig's features the plugin is tightly integrated with 
the CakePHP view renderer giving you full access to helpers, objects and elements.

## New in this version
- Added TWIG_DIR so you can define the full Twig root-directory name
- Deprecated TWIG_VERSION because it lost its purpose due to this change
- Added fabpot/Twig as a submodule of this git repository

## Installation

#### via Archive

Download the [current version as ZIP](https://github.com/m3nt0r/cakephp-twig-view/zipball/master), unzip it and rename the extracted folder to `twig`. Then put the `twig` folder inside your `plugins` and you are done.

#### via GitHub

Just clone the repository directly into your `plugins` folder.

    $ git clone git://github.com/m3nt0r/cakephp-twig-view.git twig

If you want the latest twig version too, clone it recursive.

    $ git clone git://github.com/m3nt0r/cakephp-twig-view.git twig --recursive

## Twig Version Support

The view class should support virtually all current and future Twig versions. At the time of writing TwigView v0.7 the latest Twig version used to be 1.8.3 and is still part of the download package. If you want to use your own version you need to place the extracted Twig directory into `./vendors/` and define a `TWIG_DIR` constant in your bootstrap with whatever the directory-name is. 

For example: 

    // bootstrap.php
    define('TWIG_DIR', 'Twig-1.12.3');
    
If you've used the git submodule and pulled the latest version, chances are that your Twig directory is called `twig-latest`. The define should then read:

    // bootstrap.php
    define('TWIG_DIR', 'twig-latest');

## Enabling the View

Inside your ```app_controller.php``` add the following:

```php
class AppController extends Controller {
    public $view = 'Twig.Twig'; // use twig
}
```

Now start by creating twig view-files using the `.tpl` extension. 

### Fallback / Upgrading exsiting apps
By default the view will render any existing `.ctp` like always but will prefer the `.tpl` version if present. This means that, for example, the `index.ctp` is used until you create a `index.tpl` at the same location. That way you can upgrade existing views one at a time instead of having to rework all files at once.
 
## Documentation

Check the **wiki** for more details on features and usage @   [cakephp-twig-view/wiki](https://github.com/m3nt0r/cakephp-twig-view/wiki)

## Version History

This version was written on **CakePHP 1.3**. 

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

Support for 2.x may be limited to non-existant. If you run on 2.x i suggest that you check out the [fork of predominant](https://github.com/predominant/TwigView).

## License
MIT License  
Copyright (c) 2010-2013, Kjell Bublitz.
