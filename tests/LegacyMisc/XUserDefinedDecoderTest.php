<?php

namespace Neoncitylights\Encoding\LegacyMisc\Tests;

use Neoncitylights\Encoding\HandleState;
use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\LegacyMisc\XUserDefinedDecoder;
use Neoncitylights\Encoding\Queue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( XUserDefinedDecoder::class )]
#[UsesClass( HandleState::class )]
#[UsesClass( HandleStateResult::class )]
#[UsesClass( Queue::class )]
class XUserDefinedDecoderTest extends TestCase {
	public function testEndOfQueueFinished(): void {
		$decoder = new XUserDefinedDecoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 2 );
		$this->assertTrue( $result->state->isFinished() );
	}

	public function testAsciiOneOrMore(): void {
		$decoder = new XUserDefinedDecoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 4 );
		$this->assertTrue( $result->state->isOneOrMore() );
		$this->assertSame( [ 4 ], $result->value );
	}

	#[DataProvider( 'provideNotAsciiOneOrMore' )]
	public function testNotAsciiOneOrMore( int $originalByte, int $expectedByte ): void {
		$decoder = new XUserDefinedDecoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, $originalByte );
		$this->assertTrue( $result->state->isOneOrMore() );
		$this->assertSame( [ $expectedByte ], $result->value );
	}

	public static function provideNotAsciiOneOrMore(): array {
		return [
			[ 0x80, 0xF780 ],
			[ 0xFF, 0xF7FF ],
		];
	}
}
