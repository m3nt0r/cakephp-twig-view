<?php
/**
 * TwigView Filters for CakePHP
 *
 * - I18n Filter -
 * 
 * @version 0.7.rock-lobster
 * @package app.views
 * @subpackage app.views.twig-filters
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @license MIT License
 */

/**
 * I18n - Extension for Filterset
 *
 * @package app.views.twig-extensions
 * @author Kjell Bublitz
 */
class Twig_Extension_I18n extends Twig_Extension {
	public function getName() {
		return 'Locales';
	}
	public function getFilters() {
		return array(
			'trans' => new Twig_Filter_Function('TwigView_Filter_I18n::trans'),
		);
	}
}

/**
 * NumberHelper - Filter Set
 *
 * @package app.views.twig-filters
 * @author Kjell Bublitz
 */
class TwigView_Filter_I18n extends TwigView_Filter {
	
	/**
	 * Combines __, __n, __d, __dn
	 * 
	 * Function is selected by the number of arguments given.
	 *
	 * - {{ 'Word'|trans }}
	 * - {{ 'Word'|trans('users') }}
	 * - {{ 'Word'|trans('Words', 5) }}
	 * - {{ 'Word'|trans('Words', 'users', 5) }}
	 *
	 * @param string $text 
	 * @param string $param1 (plural, domain or empty(default))
	 * @param mixed $param2
	 * @param mixed $param3 
	 * @return string
	 * @author Kjell Bublitz
	 */
	static function trans($text, $param1=null, $param2=null, $param3=null) {

		// 'Word'|trans('Words', 'users', 5)
		if (is_numeric($param3)) {
			return __dn($domain=$param2, $singular=$text, $plural=$param1, $count=$param3, true);
		}

		// 'Word'|trans('Words', 5)
		if (is_numeric($param2)) {
			return __n($singular=$text, $plural=$param1, $count=$param2, true);
		}

		// 'Word'|trans('users')
		if (!empty($param1) && !is_numeric($param1)) {
			return __d($domain=$param1, $text, true);
		}

		return __($text, true);
	}

}