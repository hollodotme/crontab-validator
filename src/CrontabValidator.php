<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\CrontabValidator;

use hollodotme\CrontabValidator\Exceptions\InvalidCrontabInterval;

/**
 * Class CrontabValidator
 * @package hollodotme\CrontabValidator
 */
class CrontabValidator
{
	/** @var string */
	private $intervalRegexp;

	public function __construct()
	{
		$this->intervalRegexp = $this->buildCrontabIntervalRegexp();
	}

	/**
	 * @return string
	 */
	private function buildCrontabIntervalRegexp() : string
	{
		$numbers = [
			'min'     => '[0-5]?\d',
			'hour'    => '[01]?\d|2[0-3]',
			'date'    => '0?[1-9]|[12]\d|3[01]',
			'month'   => '[1-9]|1[012]|jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec',
			'weekday' => '[0-7]|mon|tue|wed|thu|fri|sat|sun',
		];

		$sections = [];
		foreach ( $numbers as $section => $number )
		{
			$range                = "({$number})(-({$number})(/\d+)?)?";
			$sections[ $section ] = "\*(/\d+)?|{$number}(/\d+)?|{$range}(,{$range})*";
		}

		$joinedSections = '(' . join( ')\s+(', $sections ) . ')';
		$replacements   = '@reboot|@yearly|@annually|@monthly|@weekly|@daily|@midnight|@hourly';

		return "^({$joinedSections}|({$replacements}))$";
	}

	/**
	 * @param string $crontabInterval
	 *
	 * @return bool
	 */
	public function isIntervalValid( string $crontabInterval ) : bool
	{
		return (bool)preg_match( "#{$this->intervalRegexp}#i", $crontabInterval );
	}

	/**
	 * @param string $crontabInterval
	 *
	 * @throws InvalidCrontabInterval
	 */
	public function guardIntervalIsValid( string $crontabInterval )
	{
		if ( !$this->isIntervalValid( $crontabInterval ) )
		{
			throw (new InvalidCrontabInterval( 'Invalid crontab interval' ))->withCrontabInterval( $crontabInterval );
		}
	}
}
