<?php
/**
 * TwigView for CakePHP
 * 
 * @version 0.8.0
 * @package app.views
 * @subpackage app.views.twig
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @license MIT License
 *
 * @link http://www.twig-project.org Twig Homepage
 * @link http://github.com/m3nt0r My GitHub
 * @link http://twitter.com/m3nt0r My Twitter
 */

/**
 * Composer
 */
App::import('Vendor', 'Twig.Autoload');

/**
 * Base Classes
 */
App::import('View', 'Theme');
App::import('Lib', 'Twig.TwigExtension');

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
		'fileExtension' => '.tpl',
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
		
		// import page title, if assigned the old way
		if (isset($controller->pageTitle)) {
			$this->pageTitle = $controller->pageTitle;
		}
		
		// import plugin options
		$appOptions = Configure::read('TwigView');
		if (!empty($appOptions) && is_array($appOptions)) {
			$this->twigOptions = array_merge($this->twigOptions, $appOptions);
		}
		
		// set preferred extension
		$this->ext = $this->twigOptions['fileExtension'];
		
		// Setup template paths
		$pluginFolder = Inflector::underscore($this->plugin);
		$paths = $this->_paths($pluginFolder);
		foreach ($paths as $i => $path) {
			// Make "{% include 'test.ctp' %}" a replacement for self::element()
			$paths[] = $path . 'elements' . DS;
		}
		
		// check if all paths really exist. unfortunately Twig_Loader_Filesystem does an is_dir() for each path
		// while CakePHP just assumes you know what you are doing.
		foreach ($paths as $i => $path) {
			if (!is_dir($path)) {
				unset($paths[$i]);
				continue; // skip
			}
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
				// get relative-to-loader path
				$___relativeFn = basename(dirname($___viewFn)). DS . $___filename;
			
				// load helpers
				if ($this->helpers != false && $loadHelpers === true) {
					// Expose helpers the "cakephp 1.2" way: 
					foreach($this->loaded as $name => $helper) {
						$this->Twig->addGlobal($name, $helper);
					}
				}
				
				echo $this->Twig->render($___relativeFn, $___dataForView);
			} 
			catch(Exception $e) {
				echo '<pre><h2>Twig Error</h2>'.htmlentities($e->getMessage()).'</pre>';
			}
		} else {
			if (!isset($___dataForView['cakeDebug'])) {
				$___dataForView['cakeDebug'] = null;
			}
			
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
	 * Renders a piece of PHP with provided parameters and returns HTML, XML, or any other string.
	 *
	 * This realizes the concept of Elements, (or "partial layouts")
	 * and the $params array is used to send data to be used in the
	 * Element.	 Elements can be cached through use of the cache key.
	 *
	 * @param string $name Name of template file in the/app/views/elements/ folder
	 * @param array $params Array of data to be made available to the for rendered
	 *						view (i.e. the Element)
	 *	  Special params:
	 *		cache - enable caching for this element accepts boolean or strtotime compatible string.
	 *		Can also be an array
	 *				if an array,'time' is used to specify duration of cache.  'key' can be used to
	 *				create unique cache files.
	 *
	 * @return string Rendered Element
	 * @access public
	 */
	function element($name, $params = array(), $loadHelpers = false) {
		$file = $plugin = $key = null;

		if (isset($params['plugin'])) {
			$plugin = $params['plugin'];
		}

		if (isset($this->plugin) && !$plugin) {
			$plugin = $this->plugin;
		}

		if (isset($params['cache'])) {
			$expires = '+1 day';

			if (is_array($params['cache'])) {
				$expires = $params['cache']['time'];
				$key = Inflector::slug($params['cache']['key']);
			} elseif ($params['cache'] !== true) {
				$expires = $params['cache'];
				$key = implode('_', array_keys($params));
			}

			if ($expires) {
				$cacheFile = 'element_' . $key . '_' . $plugin . Inflector::slug($name);
				$cache = cache('views' . DS . $cacheFile, null, $expires);

				if (is_string($cache)) {
					return $cache;
				}
			}
		}
		$paths = $this->_paths($plugin);
		$exts = array($this->ext, '.ctp', '.thtml');
		foreach ($exts as $ext) {
			foreach ($paths as $path) {
				if (file_exists($path . 'elements' . DS . $name . $ext)) {
					$file = $path . 'elements' . DS . $name . $ext;
					break 2;
				}
			}
		}

		if (is_file($file)) {
			$params = array_merge_recursive($params, $this->loaded);
			$element = $this->_render($file, array_merge($this->viewVars, $params), $loadHelpers);
			if (isset($params['cache']) && isset($cacheFile) && isset($expires)) {
				cache('views' . DS . $cacheFile, $element, $expires);
			}
			return $element;
		}
		$file = $paths[0] . 'elements' . DS . $name . $this->ext;

		if (Configure::read() > 0) {
			return "Not Found: " . $file;
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