<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace hollodotme\CrontabValidator\Exceptions;

/**
 * Class InvalidExpressionException
 * @package hollodotme\CrontabValidator\Exceptions
 */
final class InvalidExpressionException extends \InvalidArgumentException
{
	/** @var string */
	private $expression;

	public function getExpression() : string
	{
		return $this->expression;
	}

	public function withExpression( string $expression ) : InvalidExpressionException
	{
		$this->message    = sprintf( 'Invalid crontab expression: "%s"', $expression );
		$this->expression = $expression;

		return $this;
	}
}
