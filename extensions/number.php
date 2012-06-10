<?php
/**
 * TwigView Filters for CakePHP
 *
 * - NumberHelper -
 * 
 * @version 0.7.rock-lobster
 * @package app.views
 * @subpackage app.views.twig-filters
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @license MIT License
 */
App::import('Helper', 'Number');

/**
 * NumberHelper - Extension for Filterset
 *
 * @package app.views.twig-extensions
 * @author Kjell Bublitz
 */
class Twig_Extension_Number extends Twig_Extension {
	public function getName() {
		return 'NumberHelper';
	}
	public function getFilters() {
		return array(
			'size' => new Twig_Filter_Function('TwigView_Filter_Number::size'),
			'pct' => new Twig_Filter_Function('TwigView_Filter_Number::percentage'),
			'curr' => new Twig_Filter_Function('TwigView_Filter_Number::currency'),
			'p' => new Twig_Filter_Function('TwigView_Filter_Number::precision'),
		);
	}
}
TwigView::registerExtension(__FILE__, 'Twig_Extension_Number');

/**
 * NumberHelper - Filter Set
 *
 * @package app.views.twig-filters
 * @author Kjell Bublitz
 */
class TwigView_Filter_Number extends TwigView_Extension {
	
	/**
	 * Wrapper to Number->toReadableSize()
	 * 
	 * @param integer $length Size in bytes
	 */
	static function size($var) {
		return self::helperObject('NumberHelper')->toReadableSize($var);
	}
	/**
	 * Wrapper to Number->toPercentage()
	 * 
	 * @param float $number A floating point number
	 * @param integer $precision The precision of the returned number
	 */
	static function percentage($var, $p=2) {
		return self::helperObject('NumberHelper')->toPercentage($var, $p);
	}
	/**
	 * Wrapper to Number->currency()
	 * 
	 * @param float $number
	 * @param string $currency Valid values are 'USD', 'EUR', 'GBP'
	 * @param array $options f.e. 'before' and 'after' options.
	 */
	static function currency($var, $curr='USD', $opts=array()) {
		return self::helperObject('NumberHelper')->currency($var, $curr, $opts);
	}
	/**
	 * Wrapper to Number->precision()
	 * 
	 * @param float $number A floating point number
	 * @param integer $precision The precision of the returned number
	 */
	static function precision($var, $p=2) {
		return self::helperObject('NumberHelper')->precision($var, $p);
	}
}