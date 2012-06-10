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
 * @package app.views.twig
 * @subpackage app.views.twig-filters
 * @author Kjell Bublitz
 */
abstract class TwigView_Extension {
	
	/**
	 * Instance and register any given class
	 *	
	 * @author Kjell Bublitz
	 * @param string $className 
	 * @return object
	 */
	protected static function helperObject($className) {
		$registryKey = 'TwigView_Extension_'.$className;
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
	
	/**
	 * Default Options
	 *
	 * @var array
	 */
	public $twigOptions = array(
		'fileExtension' => '.twig',
		'extensions' => array(
			'i18n',
			'number',
			'text',
			'time'
		)
	);
	
	/**
	 * filename => className
	 * 
	 * @see TwigView::registerExtension()
	 * @var array
	 */
	static private $__extensionExports = array();
	
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
		$this->twigExtensionPath = $this->twigPluginPath . 'extensions';
		
		// import plugin options
		$appOptions = Configure::read('TwigView');
		if (!empty($appOptions) && is_array($appOptions)) {
			$this->twigOptions = array_merge($this->twigOptions, $twigOptions);
		}
		
		// set preferred extension
		$this->ext = $this->twigOptions['fileExtension'];
		
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
		
		// Add custom TwigView Extensions
		$this->twigLoadedExtensions = array();
		foreach ($this->twigOptions['extensions'] as $extensionName) {
			if ($extensionClassName = $this->_loadTwigExtension($extensionName)) {
				$this->Twig->addExtension(new $extensionClassName);
			}
		}
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
		
		if ($___extension == $this->twigOptions['fileExtension']) {
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
	 * Require filter set once, add filter name to self::twigLoadedFilters
	 *
	 * Triggers E_USER_ERROR if file not found.
	 *
	 * @param string $name Filename without extension
	 * @return boolean
	 * @author Kjell Bublitz
	 */
	protected function _loadTwigExtension($extensionName) {
		if (in_array($extensionName, $this->twigLoadedExtensions)) {
			return false; // already loaded
		}
		
		$filename = $extensionName .'.php';
		$filepath = $this->twigExtensionPath . DS . $filename;
		
		if (!is_file($filepath)) {
			trigger_error("TwigExtension file not found: {$extensionName} (looked in: {$this->twigExtensionPath})", E_USER_ERROR);
			return false;
		}
		require_once $filepath;
		
		if (empty(self::$__extensionExports[$filename])) {
			trigger_error("TwigExtension '{$extensionName}' does not export a extension class (did you call registerExtension?).", E_USER_ERROR);
			return false;
		}
		
		$this->twigLoadedExtensions[] = $extensionName;
		return self::$__extensionExports[$filename];
	}
	
	/**
	 * Register Extension Class Name for loading
	 *
	 * Must be called from inside the required extension file
	 * and provide the contained class name
	 *
	 * @param string $file __FILE__
	 * @param string $extensionClassName 'YourTwigExtension'
	 * @return void
	 * @author Kjell Bublitz
	 */
	static public function registerExtension($file, $extensionClassName) {
		self::$__extensionExports[basename($file)] = $extensionClassName;
	}
}