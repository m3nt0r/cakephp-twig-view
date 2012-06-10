<?php
define('TWIG_VERSION', '1.8.2');
/**
 * TwigView for CakePHP
 * 
 * @version 0.7.rock-lobster
 * @package app.views
 * @subpackage app.views.twig
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @license MIT License
 *
 * Rewriting is fun. Simplicity is bliss.
 *
 * @link http:://www.twig-project.org Twig Homepage
 * @link http://github.com/m3nt0r My GitHub
 * @link http://twitter.com/m3nt0r My Twitter
 */
App::import('Core', 'Theme');
App::import('Vendors', 'Twig.Twig_Environment', array(
	'file' => 'twig-'.TWIG_VERSION.DS.'lib'.DS.'Twig'.DS.'Autoloader.php'
));

Twig_Autoloader::register();

/**
 * CakePHP i18n Support
 *
 * @param string $text 
 * @param string $param1 (plural, domain or empty(default))
 * @param mixed $param2
 * @param mixed $param3 
 * @return string
 * @author Kjell Bublitz
 */
function transFilter($text, $param1=null, $param2=null, $param3=null) {
	
	// 'Word'|trans('Words', 'users', 5)
	if (is_numeric($param3)) {
		return __dn($domain=$param2, $singular=$text, $plural=$param1, $count=$param3, true);
	}
	
	// 'Word'|trans('Words', 5)
	if (is_numeric($param2)) {
		return __n($singular=$text, $plural=$param1, $count=$param2, true);
	}
	
	// 'Word'|trans('users')
	if (!empty($param1)) {
		return __d($domain=$param1, $text, true);
	}
	
	return __($text, true);
}

/**
 * TwigView Class 
 *
 * @package app.views
 * @subpackage app.views.twig
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 */
class TwigView extends ThemeView {
	
	/**
	 * Constructor
	 *
	 * @param Controller $controller A controller object to pull View::__passedArgs from.
	 * @param boolean $register Should the View instance be registered in the ClassRegistry
	 * @return View
	 */
	function __construct(&$controller, $register = true) {
		parent::__construct($controller, $register);
		
		// Setup template paths
		// Make "{% include 'test.ctp' %}" a replacement for self::element()
		$paths = $this->_paths(Inflector::underscore($this->plugin));
		foreach ($paths as $path) {
			$paths[] = $path . 'elements' . DS;
		}
			
		// Setup Twig Environment
		$loader = new Twig_Loader_Filesystem($paths);
		$this->Twig = new Twig_Environment($loader, array(
			'cache' => false, // use cakephp cache
			'debug' => (Configure::read() > 0),
		));
		
		// i18n
		$this->Twig->addFilter('trans', new Twig_Filter_Function('transFilter'));
		
		// Do not escape return values (from helpers)
		$escaper = new Twig_Extension_Escaper(false);
		$this->Twig->addExtension($escaper);
		
		// preferred extension
		$this->ext = '.twig';
	}
	
	/**
	 * Renders and returns output for given view filename with its
	 * array of data.
	 *
	 * @param string $___viewFn Filename of the view
	 * @param array $___dataForView Data to include in rendered view
	 * @param boolean $loadHelpers Boolean to indicate that helpers should be loaded.
	 * @param boolean $cached Whether or not to trigger the creation of a cache file.
	 * @return string Rendered output
	 * @access protected
	 */
	function _render($___viewFn, $___dataForView, $loadHelpers = true, $cached = false) {
		$loadedHelpers = array();
		
		if ($this->helpers != false && $loadHelpers === true) {
			$loadedHelpers = $this->_loadHelpers($loadedHelpers, $this->helpers);
			$helpers = array_keys($loadedHelpers);
			$helperNames = array_map(array('Inflector', 'variable'), $helpers);

			for ($i = count($helpers) - 1; $i >= 0; $i--) {
				$name = $helperNames[$i];
				$helper =& $loadedHelpers[$helpers[$i]];

				if (!isset($___dataForView[$name])) {
					${$name} =& $helper;
				}
				$this->loaded[$helperNames[$i]] =& $helper;
				$this->{$helpers[$i]} =& $helper;
			}
			$this->_triggerHelpers('beforeRender');
			unset($name, $loadedHelpers, $helpers, $i, $helperNames, $helper);
		}
		
		extract($___dataForView, EXTR_SKIP);
		ob_start();
		
		$___viewFolder = dirname($___viewFn);
		$___filename = basename($___viewFn);
		$___extension = array_pop(explode('.', $___filename));
		
		if ($___extension == 'twig') {
			try {
				// load helpers
				if ($this->helpers != false && $loadHelpers === true) {
					// Expose helpers the "cakephp 1.2" way: 
					foreach($this->loaded as $name => $helper) {
						$this->Twig->addGlobal($name, $helper);
					}
				}
				
				
				
				
				// render
				$templateName = $this->viewPath . DS . $___filename;
				echo $this->Twig->render($templateName, $___dataForView);
			} 
			catch(Exception $e) {
				echo '<pre><h2>Twig Error</h2>'.htmlentities($e->getMessage()).'</pre>';
			}
			
		} else {
			if ((Configure::read() > 0)) {
				include ($___viewFn);
			} else {
				@include ($___viewFn);
			}
		}
		
		if ($loadHelpers === true) {
			$this->_triggerHelpers('afterRender');
		}

		$out = ob_get_clean();
		$caching = (
			isset($this->loaded['cache']) &&
			(($this->cacheAction != false)) && (Configure::read('Cache.check') === true)
		);

		if ($caching) {
			if (is_a($this->loaded['cache'], 'CacheHelper')) {
				$cache =& $this->loaded['cache'];
				$cache->base = $this->base;
				$cache->here = $this->here;
				$cache->helpers = $this->helpers;
				$cache->action = $this->action;
				$cache->controllerName = $this->name;
				$cache->layout = $this->layout;
				$cache->cacheAction = $this->cacheAction;
				$cache->viewVars = $this->viewVars;
				$out = $cache->cache($___viewFn, $out, $cached);
			}
		}
		return $out;
	}
}