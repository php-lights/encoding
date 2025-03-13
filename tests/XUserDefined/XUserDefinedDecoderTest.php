<?php

namespace Neoncitylights\Encoding\XUserDefined\Tests;

use Neoncitylights\Encoding\HandleState;
use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\XUserDefined\XUserDefinedDecoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( XUserDefinedDecoder::class )]
#[UsesClass( HandleState::class )]
#[UsesClass( HandleStateResult::class )]
#[UsesClass( Queue::class )]
class XUserDefinedDecoderTest extends TestCase {
	public function testEndOfQueueFinished() {
		$decoder = new XUserDefinedDecoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 2 );
		$this->assertTrue( $result->state->isFinished() );
	}

	public function testAsciiOneOrMore() {
		$decoder = new XUserDefinedDecoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 4 );
		$this->assertTrue( $result->state->isOneOrMore() );
		$this->assertSame( [ 4 ], $result->value );
	}

	public function testNotAsciiOneOrMore() {
		$decoder = new XUserDefinedDecoder();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );

		$result = $decoder->handler( $queue, 128 );
		$this->assertTrue( $result->state->isOneOrMore() );
		$this->assertSame( [ 0xF780 ], $result->value );
	}
}
