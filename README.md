# Twig for CakePHP (v0.7 rock-lobster)

This plugin for the CakePHP Framework allows you to use the Twig Templating Language.

Apart from enabling you to use most of Twig's features the plugin is tightly integrated with 
the CakePHP view renderer giving you full access to helpers, objects and elements.

## New in this branch

- Complete rewrite
- Latest Twig (v1.8.2 atm)
- Easy to upgrade
- Easy to install
- Easy to migrate

This is going to replace the v0.6 branch and the last version was 0.6.1. 
Once this branch is complete, v0.7 will go into master.

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

## Caching

The interal Twig caching engine has been disabled in favor of CakePHPs own view caching mechanism.
