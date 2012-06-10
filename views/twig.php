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
 * Inherit for Filter Extensions
 *
 * @package default
 * @author Kjell Bublitz
 */
abstract class TwigView_Filter {
	protected static function helperObject($className) {
		$registryKey = 'TwigView_Filter'.$className;
		$object = ClassRegistry::getObject($registryKey);	
		if (is_a($object, $className)) return $object;
		$object = new $className();
		ClassRegistry::addObject($registryKey, $object);	
		return $object;
	}
}


/**
 * TwigView Class 
 *
 * @package app.views
 * @subpackage app.views.twig
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 */
class TwigView extends ThemeView {
	
	public $twigOptions = array(
		'filters' => array('i18n','number','text','time'),
		'extension' => '.twig'
	);
	
	/**
	 * Constructor
	 *
	 * @param Controller $controller A controller object to pull View::__passedArgs from.
	 * @param boolean $register Should the View instance be registered in the ClassRegistry
	 * @return View
	 */
	function __construct(&$controller, $register = true) {
		parent::__construct($controller, $register);
		$this->twigPluginPath = dirname(dirname(__FILE__)) . DS;
		$this->twigFilterPath = $this->twigPluginPath . 'filters';
		
		// import plugin options
		$appOptions = Configure::read('TwigView');
		if (!empty($appOptions) && is_array($appOptions)) {
			$this->twigOptions = array_merge($this->twigOptions, $twigOptions);
		}
		
		// set preferred extension
		$this->ext = $this->twigOptions['extension'];
		
		// Setup template paths
		$pluginFolder = Inflector::underscore($this->plugin);
		$paths = $this->_paths($pluginFolder);
		foreach ($paths as $path) {
			// Make "{% include 'test.ctp' %}" a replacement for self::element()
			$paths[] = $path . 'elements' . DS;
		}
		
		// Setup Twig Environment
		$loader = new Twig_Loader_Filesystem($paths);
		$this->Twig = new Twig_Environment($loader, array(
			'cache' => false, // use cakephp cache
			'debug' => (Configure::read() > 0),
		));
		
		// Do not escape return values (from helpers)
		$escaper = new Twig_Extension_Escaper(false);
		$this->Twig->addExtension($escaper);
		
		// Add TwigView CakePHP filters
		$this->_loadCustomFilters();
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
		
		$___filename = basename($___viewFn);
		$___extension = '.' . array_pop(explode('.', $___filename));
				
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
		
		if ($___extension == $this->twigOptions['extension']) {
			ob_start();
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
			extract($___dataForView, EXTR_SKIP);
			ob_start();
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
	
	/**
	 * Expose some selected Helper methods as filters
	 * 
	 * @return void
	 * @author Kjell Bublitz
	 */
	private function _loadCustomFilters() {
		
		$this->twigLoadedFilters = array();
		
		if (in_array('time', $this->twigOptions['filters'])) {
			$this->_loadFilterSet('time');
			$this->Twig->addExtension(new Twig_Extension_Time);
		}
		if (in_array('i18n', $this->twigOptions['filters'])) {	
			$this->_loadFilterSet('i18n');
			$this->Twig->addExtension(new Twig_Extension_I18n);
		}
		if (in_array('number', $this->twigOptions['filters'])) {	
			$this->_loadFilterSet('number');
			$this->Twig->addExtension(new Twig_Extension_Number);
		}
		if (in_array('text', $this->twigOptions['filters'])) {		
			$this->_loadFilterSet('text');
			$this->Twig->addExtension(new Twig_Extension_Text);
		}
	}
	
	/**
	 * Require filter set once, add filter name to self::twigLoadedFilters
	 *
	 * Triggers E_USER_ERROR if file not found.
	 *
	 * @param string $name Filename without extension
	 * @return boolean
	 * @author Kjell Bublitz
	 */
	private function _loadFilterSet($name) {
		$filterFilePath = $this->twigFilterPath . DS . $name .'.php';
		if (!is_file($filterFilePath)) {
			trigger_error("Filter not found: {$name} (looked in: {$this->twigFilterPath})", E_USER_ERROR);
			return false;
		}
		require_once $filterFilePath;
		$this->twigLoadedFilters[] = $name;
		return true;
	}
}