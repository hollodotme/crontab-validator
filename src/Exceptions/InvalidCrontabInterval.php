<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\CrontabValidator\Exceptions;

/**
 * Class InvalidCrontabInterval
 * @package hollodotme\CrontabValidator\Exceptions
 */
final class InvalidCrontabInterval extends CrontabValidatorException
{
	/** @var string */
	private $crontabInterval;

	public function getCrontabInterval(): string
	{
		return $this->crontabInterval;
	}

	public function withCrontabInterval( string $crontabInterval ): InvalidCrontabInterval
	{
		$this->crontabInterval = $crontabInterval;

		return $this;
	}
}
