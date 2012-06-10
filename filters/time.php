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
			'relative' => new Twig_Filter_Function('TwigView_Filter_Time::timeAgoInWords'),
			'nice' => new Twig_Filter_Function('TwigView_Filter_Time::nice'),
			'niceShort' => new Twig_Filter_Function('TwigView_Filter_Time::niceShort'),
			'gmt' => new Twig_Filter_Function('TwigView_Filter_Time::gmt'),
			'rssTime' => new Twig_Filter_Function('TwigView_Filter_Time::toRSS'),
			'atomTime' => new Twig_Filter_Function('TwigView_Filter_Time::toAtom'),
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
	 * ### Options:
	 *
	 * - `format` => a fall back format if the relative time is longer than the duration specified by end
	 * - `end` => The end of relative time telling
	 * - `userOffset` => Users offset from GMT (in hours)
	 *
	 * @param string $var 
	 * @return void
	 * @author Kjell Bublitz
	 */
	function timeAgoInWords($var, $options = array()) {
		return self::helperObject('TimeHelper')->timeAgoInWords($var, $options);
	}
		
	/**
	 * TimeHelper::toRSS
	 *
	 * @param string $var UNIX timestamp or a valid strtotime() date string
	 * @return void Date formatted for RSS feeds
	 * @author Kjell Bublitz
	 */
	function toRSS($var, $userOffset = null) {
		return self::helperObject('TimeHelper')->toRSS($var, $userOffset);
	}
	
	/**
	 * TimeHelper::toAtom
	 *
	 * @param string $var UNIX timestamp or a valid strtotime() date string
	 * @return void Date formatted for Atom RSS feeds
	 * @author Kjell Bublitz
	 */
	function toAtom($var, $userOffset = null) {
		return self::helperObject('TimeHelper')->toAtom($var, $userOffset);
	}
	
	/**
	 * TimeHelper::gmt
	 *
	 * @param string $var UNIX timestamp or a valid strtotime() date string
	 * @return void
	 * @author Kjell Bublitz
	 */
	function gmt($var) {
		return self::helperObject('TimeHelper')->gmt($var);
	}

	/**
	 * TimeHelper::nice
	 *
	 * @param string $var 
	 * @return void
	 * @author Kjell Bublitz
	 */
	function nice($var, $userOffset = null) {
		return self::helperObject('TimeHelper')->nice($var, $userOffset);
	}

	/**
	 * TimeHelper::niceShort
	 *
	 * @param string $var 
	 * @return void
	 * @author Kjell Bublitz
	 */
	function niceShort($var, $userOffset = null) {
		return self::helperObject('TimeHelper')->niceShort($var, $userOffset);
	}
}
?>