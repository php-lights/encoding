<?php

namespace Neoncitylights\Encoding\LegacyMisc\Tests;

use Neoncitylights\Encoding\HandleState;
use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\LegacyMisc\XUserDefinedEncoder;
use Neoncitylights\Encoding\Queue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( XUserDefinedEncoder::class )]
#[UsesClass( HandleState::class )]
#[UsesClass( HandleStateResult::class )]
#[UsesClass( Queue::class )]
class XUserDefinedEncoderTest extends TestCase {
	public function testEndOfQueueFinished(): void {
		$decoder = new XUserDefinedEncoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 2 );
		$this->assertTrue( $result->state->isFinished() );
	}

	public function testAsciiOneOrMore(): void {
		$decoder = new XUserDefinedEncoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 4 );
		$this->assertTrue( $result->state->isOneOrMore() );
		$this->assertSame( [ 4 ], $result->value );
	}

	public function testOtherOneOrMore(): void {
		$decoder = new XUserDefinedEncoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 0xF78F );
		$this->assertTrue( $result->state->isOneOrMore() );
		$this->assertSame( [ 0x8F ], $result->value );
	}

	public function testError(): void {
		$decoder = new XUserDefinedEncoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 0xF800 );
		$this->assertTrue( $result->state->isError() );
		$this->assertSame( 0xF800, $result->value );
	}
}
