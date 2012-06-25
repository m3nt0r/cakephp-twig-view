# Twig for CakePHP (v0.7)

This plugin for the CakePHP Framework allows you to use the Twig Templating Language.

Apart from enabling you to use most of Twig's features the plugin is tightly integrated with 
the CakePHP view renderer giving you full access to helpers, objects and elements.

## New in this version

- Complete rewrite
- Latest Twig (v1.8.3 atm)
- Easy to upgrade
- Easy to install
- Easy to migrate
- Extendable (see wiki)
- More CakePHP Filters

This version was written on **CakePHP 1.3**. 

Support for 2.x may be limited to non-existant. If you run on 2.x i suggest that you check out the [fork from predominant](https://github.com/predominant/TwigView)

## Installation

#### via Archive

Download the [current version as ZIP](https://github.com/m3nt0r/cakephp-twig-view/zipball/master), unzip it and rename the extracted folder to ```twig```.
Then put the ```twig``` folder inside your ```plugins``` and you are done.

#### via GitHub

Just clone the repository directly into your app.

    $ cd plugins 
    $ git clone git://github.com/m3nt0r/cakephp-twig-view.git twig

## Enabling the View

Inside your ```app_controller.php``` add the following:

    class AppController extends Controller {
        public $view = 'Twig.Twig'; // use twig
    }

Now start creating view files using the ```.tpl``` extension. 

## Documentation

Check the **wiki** for more Informations and Features

[cakephp-twig-view/wiki](https://github.com/m3nt0r/cakephp-twig-view/wiki)

"The readme was just too long(tm)" 


