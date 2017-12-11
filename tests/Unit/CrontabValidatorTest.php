<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace hollodotme\CrontabValidator\Tests\Unit;

use hollodotme\CrontabValidator\CrontabValidator;
use hollodotme\CrontabValidator\Exceptions\InvalidExpressionException;
use PHPUnit\Framework\TestCase;

/**
 * Class CrontabValidatorTest
 * @package hollodotme\CrontabValidator\Tests\Unit
 */
final class CrontabValidatorTest extends TestCase
{
	/**
	 * @param string $expression
	 *
	 * @dataProvider validExpressionsProvider
	 */
	public function testExpressionIsValid( string $expression ) : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isExpressionValid( $expression ) );

		$validator->guardExpressionIsValid( $expression );
	}

	public function validExpressionsProvider() : array
	{
		return require __DIR__ . '/DataProviders/ValidExpressions.php';
	}

	/**
	 * @param string $expression
	 *
	 * @dataProvider invalidExpressionsProvider
	 */
	public function testExpressionIsInvalid( string $expression ) : void
	{
		$validator = new CrontabValidator();

		$this->assertFalse( $validator->isExpressionValid( $expression ) );
	}

	public function invalidExpressionsProvider() : array
	{
		return require __DIR__ . '/DataProviders/InvalidExpressions.php';
	}

	/**
	 * @param string $expression
	 *
	 * @dataProvider invalidExpressionsProvider
	 */
	public function testInvalidExpressionThrowsException( string $expression ) : void
	{
		try
		{
			(new CrontabValidator())->guardExpressionIsValid( $expression );
		}
		catch ( InvalidExpressionException $e )
		{
			$this->assertSame( 'Invalid crontab expression: "' . $expression . '"', $e->getMessage() );
			$this->assertSame( $expression, $e->getExpression() );
		}
	}

	public function testExpressionAllowsTheLastModifierInDayOfMonth() : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isExpressionValid( '* * 5L * *' ) );
	}

	public function testExpressionAllowsTheLastModifierInDayOfWeek() : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isExpressionValid( '* * * * 5L' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * WEDL' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * 3L,5L' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * 2L-5L' ) );
	}

	public function testExpressionAllowsTheHashtagModifierInDayOfWeek() : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isExpressionValid( '* * * * 5#1' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * 4#2' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * 3#3' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * 2#4' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * 1#5' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * MON#5' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * TUE#4' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * WED#3' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * thu#2' ) );
		$this->assertTrue( $validator->isExpressionValid( '* * * * Fri#1' ) );
	}

	public function testInvalidHashtagModifierIsNotAllowed() : void
	{
		$validator = new CrontabValidator();

		$this->assertFalse( $validator->isExpressionValid( '* * * * 1#6' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * * * Wed#0' ) );
	}

	public function testCanUseQuestionmarkInsteadOfAsteriskForDayOfMonth() : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isExpressionValid( '* * ? * *' ) );
	}

	public function testInvalidStepsAreNotAllowedInMinuteSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid minute steps
		$this->assertFalse( $validator->isExpressionValid( '*/-1 * * * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '*/0 * * * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '*/60 * * * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '*/61 * * * *' ) );
	}

	public function testInvalidStepsAreNotAllowedInHourSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid hour steps
		$this->assertFalse( $validator->isExpressionValid( '* */-1 * * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* */0 * * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* */24 * * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* */26 * * *' ) );
	}

	public function testInvalidStepsAreNotAllowedInDayOfMonthSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid day of month steps
		$this->assertFalse( $validator->isExpressionValid( '* * */-1 * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * */0 * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * */32 * *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * */35 * *' ) );
	}

	public function testInvalidStepsAreNotAllowedInMonthSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid month steps
		$this->assertFalse( $validator->isExpressionValid( '* * * */-1 *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * * */0 *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * * */13 *' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * * */15 *' ) );
	}

	public function testInvalidStepsAreNotAllowedInDayOfWeekSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid day of week steps
		$this->assertFalse( $validator->isExpressionValid( '* * * * */-1' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * * * */0' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * * * */8' ) );
		$this->assertFalse( $validator->isExpressionValid( '* * * * */10' ) );
	}
}
