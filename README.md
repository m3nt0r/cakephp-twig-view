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

The previous version 0.6 can be found [here](https://github.com/m3nt0r/cakephp-twig-view/tree/twig.six "twig.six branch")

This version was written on CakePHP 1.3. Support for 2.x may be limited to non-existant.
If you run on 2.x i suggest that you check out the [fork from predominant](https://github.com/predominant/TwigView)

## Installation

#### via Archive

Download the repository (zip button), unzip it and rename the extracted folder to ```twig```.
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

Any ```.ctp``` template will remain working just as before until you add a ```.tpl``` file with the same name. The TwigView simply **prefers** files with .tpl extension and does nothing if there is none, but a suitable ```.ctp``` is present. 

This makes migrating very easy.

This is probably how it looks right now.

    views/users/
      login.ctp

If you want to rewrite the view using Twig simply create a ```.tpl```.

    views/users/
      login.ctp
      login.tpl

They can live next to each other. No problem. Twig will load the .tpl as long as the view is set.


## CakePHP specific examples

This plugin comes with all default layouts converted to Twig. There's also a plain index view 
for a users table with common php mixed in. It's really not much

Examples can be found in:

     ./examples


## CakePHP Elements

We use the `include` tag to import elements. This requires that elements are twig templates. Using ```.ctp``` would work but any PHP inside will not be executed. 

In exchange for this limitation you can import elements as easy as:

    {% include 'test.ctp' %} 

## CakePHP Themes

The plugin has support for themes and works just like the "Theme" view. Simply add the ```$theme```
property to your controller and you're set.

    class AppController extends Controller {
        public $view = 'Twig.Twig';
        public $theme = 'lobster';
    }

This will cause the view to also look in the "themed" folder for templates. In the above example
templates in the following directory are favored over their non-themed version.

    app/views/themed/lobster/

If you, for example, want to overwrite the ```layouts/default.tpl``` file in the ```lobster``` theme, 
then create this file:

    app/views/themed/lobster/layouts/default.tpl

## CakePHP Helpers

All helper objects are available inside a view and can be used like any other variable inside Twig.

    {{ time.nice(user.created) }}

... where ...

    {{ time.nice(user.created) }}
        ^    ^    ^    ^____key
        |    |    |____array (from $this->set() or loop)
        |    |_____ method
        |______ helper

Which is the equivalent of writing:

    <?php echo $this->Time->nice($user['created']); ?>

A more complex example, FormHelper inputs:

    {{
     form.input('message', {
       'label': 'Your message',
       'error': {
         'notempty': 'Please enter a message'
        }
      })
    }}

## CakePHP-powered Filters

The View class adds some sugar to templates by exposing some Helper methods through filters. It  features the most common and useful ones. If you think i've left out an important method, geel free to extend the filter (see ```./extensions``` folder) and send me a pull request. 

By default all filter sets (helpers) are enabled. Currently available are:

- time
- number
- text
- i18n

You can disable any of them by setting ```Configure::write('TwigView.extensions')``` with an list of extensions  
you want (lowercase, like above). If you want to disabled them all just set an empty array or change each 
name to something like 'time_disabled' as the load condition is simply using in_array.

### Examples

You can find usage examples for all custom filters inside the ```examples``` directory. 

- translation.tpl
- filters.tpl

## Caching

The interal Twig caching engine has been disabled in favor of CakePHPs own view caching mechanism.

## Contributing

I've added some utitlity methods so i can bring in any Helper and associated Extensions/Filters as quickly as possible. 

If you want to contribute some filters i suggest to look inside the ```extensions``` directory and copy one of the 
existing files and simply rename methods and class names to your needs.

You are not limited to filters, by the way. Read the Twig Documentation [about extending](http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension). The way i extend can also
be used to add TokenParsers, Functions, Operators, Globals and more.

### Basic Workflow

Extend ```TwigView_Extension``` for your method collection. There is not much code in it, but we are at an early
stage and inheriting is always a good idea when extending a package.

Helpers can used like so:

    self::helperObject('YourHelper')->something()

The first time you do this the ```YourHelper``` object is stored in the [ClassRegistry](http://api.cakephp.org/class/class-registry "CakePHP API") 
so we don't spam-create a new Helper instance each and every filter call (inside a foreach table loop, for example).

Once you are done coding you need to add a call to ```TwigView::registerExtension()``` to your extension file:

    TwigView::registerExtension(__FILE__, 'Your_Helper_Twig_Extension'); // extending Twig_Extension

Replace ```Your_Helper_Twig_Extension``` with your extension class name. This will simply tell ```TwigView``` what class 
to use when calling ```TwigEnvironment::addExtension()``` inside the construct

That's all! And please use tabs, not spaces :)




