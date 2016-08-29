<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\CrontabIntervalValidator;

use hollodotme\CrontabIntervalValidator\Exceptions\CrontabIntervalValidatorException;

/**
 * Class CrontabIntervalValidator
 * @package hollodotme\CrontabIntervalValidator
 */
class CrontabIntervalValidator
{
	/** @var string */
	private $regexp;

	public function __construct()
	{
		$this->regexp = $this->buildCrontabRegexp();
	}

	private function buildCrontabRegexp() : string
	{
		$numbers = [
			'min'     => '[0-5]?\d',
			'hour'    => '[01]?\d|2[0-3]',
			'date'    => '0?[1-9]|[12]\d|3[01]',
			'month'   => '[1-9]|1[012]',
			'weekday' => '[0-7]',
		];

		$sections = [];
		foreach ( $numbers as $section => $number )
		{
			$range                = "({$number})(-({$number})(\/\d+)?)?";
			$sections[ $section ] = "\*(\/\d+)?|{$number}(\/\d+)?|{$range}(,{$range})*";
		}

		$sections['month'] .= '|jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec';
		$sections['weekday'] .= '|mon|tue|wed|thu|fri|sat|sun';

		$joinedSections = '(' . join( ')\s+(', $sections ) . ')';
		$replacements   = '@reboot|@yearly|@annually|@monthly|@weekly|@daily|@midnight|@hourly';

		return "^({$joinedSections}|({$replacements}))$";
	}

	public function isValid( string $crontabInterval ) : bool
	{
		return (bool)preg_match( "/$this->regexp/umsi", $crontabInterval );
	}

	public function guardIsValid( string $crontabInterval )
	{
		if ( !$this->isValid( $crontabInterval ) )
		{
			throw new CrontabIntervalValidatorException( 'Invalid crontab interval' );
		}
	}
}
