<?php
/**
 * TwigView Filters for CakePHP
 *
 * - TimeHelper -
 * 
 * @version 0.7.rock-lobster
 * @package app.views
 * @subpackage app.views.twig-filters
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @license MIT License
 */
App::import('Helper', 'Time');

/**
 * TimeHelper - Extension for Filterset
 *
 * @package app.views.twig-extensions
 * @author Kjell Bublitz
 */
class Twig_Extension_Time extends Twig_Extension {
	public function getName() {
		return 'TimeHelper';
	}
	public function getFilters() {
		return array(
			'ago' => new Twig_Filter_Function('TwigView_Filter_Time::timeAgoInWords'),
			'nice' => new Twig_Filter_Function('TwigView_Filter_Time::nice'),
			'niceShort' => new Twig_Filter_Function('TwigView_Filter_Time::niceShort'),
		);
	}
}

/**
 * TimeHelper - Filter Set
 *
 * @package app.views.twig-filters
 * @author Kjell Bublitz
 */
class TwigView_Filter_Time extends TwigView_Filter {
	/**
	 * TimeHelper::timeAgoInWords
	 *
	 * @param string $var 
	 * @return void
	 * @author Kjell Bublitz
	 */
	function timeAgoInWords($var) {
		return self::helperObject('TimeHelper')->timeAgoInWords($var);
	}

	/**
	 * TimeHelper::nice
	 *
	 * @param string $var 
	 * @return void
	 * @author Kjell Bublitz
	 */
	function nice($var) {
		return self::helperObject('TimeHelper')->nice($var);
	}

	/**
	 * TimeHelper::niceShort
	 *
	 * @param string $var 
	 * @return void
	 * @author Kjell Bublitz
	 */
	function niceShort($var) {
		return self::helperObject('TimeHelper')->niceShort($var);
	}
}
?>