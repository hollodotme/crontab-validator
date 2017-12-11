<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace hollodotme\CrontabValidator\Tests\Unit;

use hollodotme\CrontabValidator\CrontabValidator;
use hollodotme\CrontabValidator\Exceptions\InvalidCrontabInterval;
use PHPUnit\Framework\TestCase;

/**
 * Class CrontabValidatorTest
 * @package hollodotme\CrontabValidator\Tests\Unit
 */
final class CrontabValidatorTest extends TestCase
{
	/**
	 * @param string $crontabInterval
	 *
	 * @dataProvider validIntervalProvider
	 * @throws InvalidCrontabInterval
	 */
	public function testIntervalIsValid( string $crontabInterval ) : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isIntervalValid( $crontabInterval ) );

		$validator->guardIntervalIsValid( $crontabInterval );
	}

	public function validIntervalProvider() : array
	{
		return require __DIR__ . '/DataProviders/ValidCrontabIntervals.php';
	}

	/**
	 * @param string $crontabInterval
	 *
	 * @dataProvider invalidIntervalProvider
	 */
	public function testIntervalIsInValid( string $crontabInterval ) : void
	{
		$validator = new CrontabValidator();

		$this->assertFalse( $validator->isIntervalValid( $crontabInterval ) );
	}

	public function invalidIntervalProvider() : array
	{
		return require __DIR__ . '/DataProviders/InvalidCrontabIntervals.php';
	}

	public function testInvalidIntervalThrowsException() : void
	{
		try
		{
			(new CrontabValidator())->guardIntervalIsValid( ' abc def hij klm nop ' );
		}
		catch ( InvalidCrontabInterval $e )
		{
			$this->assertSame( ' abc def hij klm nop ', $e->getCrontabInterval() );
		}
	}

	public function testExpressionAllowsTheLastModifierInDayOfMonth() : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isIntervalValid( '* * 5L * *' ) );
	}

	public function testExpressionAllowsTheLastModifierInDayOfWeek() : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isIntervalValid( '* * * * 5L' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * WEDL' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * 3L,5L' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * 2L-5L' ) );
	}

	public function testExpressionAllowsTheHashtagModifierInDayOfWeek() : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isIntervalValid( '* * * * 5#1' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * 4#2' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * 3#3' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * 2#4' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * 1#5' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * MON#5' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * TUE#4' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * WED#3' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * thu#2' ) );
		$this->assertTrue( $validator->isIntervalValid( '* * * * Fri#1' ) );
	}

	public function testInvalidHashtagModifierIsNotAllowed() : void
	{
		$validator = new CrontabValidator();

		$this->assertFalse( $validator->isIntervalValid( '* * * * 1#6' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * * * Wed#0' ) );
	}

	public function testCanUseQuestionmarkInsteadOfAsteriskForDayOfMonth() : void
	{
		$validator = new CrontabValidator();

		$this->assertTrue( $validator->isIntervalValid( '* * ? * *' ) );
	}

	public function testInvalidStepsAreNotAllowedInMinuteSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid minutes step
		$this->assertFalse( $validator->isIntervalValid( '*/-1 * * * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '*/0 * * * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '*/60 * * * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '*/61 * * * *' ) );
	}

	public function testInvalidStepsAreNotAllowedInHourSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid minutes step
		$this->assertFalse( $validator->isIntervalValid( '* */-1 * * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* */0 * * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* */24 * * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* */26 * * *' ) );
	}

	public function testInvalidStepsAreNotAllowedInDayOfMonthSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid minutes step
		$this->assertFalse( $validator->isIntervalValid( '* * */-1 * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * */0 * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * */32 * *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * */35 * *' ) );
	}

	public function testInvalidStepsAreNotAllowedInMonthSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid minutes step
		$this->assertFalse( $validator->isIntervalValid( '* * * */-1 *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * * */0 *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * * */13 *' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * * */15 *' ) );
	}

	public function testInvalidStepsAreNotAllowedInDayOfWeekSection() : void
	{
		$validator = new CrontabValidator();

		# Invalid minutes step
		$this->assertFalse( $validator->isIntervalValid( '* * * * */-1' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * * * */0' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * * * */8' ) );
		$this->assertFalse( $validator->isIntervalValid( '* * * * */10' ) );
	}
}
