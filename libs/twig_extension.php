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
 * TwigView_Extension
 * Inherit for Custom Filter Extensions
 *
 * @package app.views.twig
 * @subpackage app.views.twig-filters
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
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