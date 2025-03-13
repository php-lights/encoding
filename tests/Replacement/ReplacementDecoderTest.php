<?php

namespace Neoncitylights\Encoding\Replacement\Tests;

use Neoncitylights\Encoding\HandleState;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\Replacement\ReplacementDecoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( ReplacementDecoder::class )]
#[UsesClass( Queue::class )]
class ReplacementDecoderTest extends TestCase {
	public function testEndOfQueueEarlyFinished() {
		$decoder = new ReplacementDecoder();
		$queue = Queue::newFromArray( [ 1, 2, 3 ] );

		$result = $decoder->handler( $queue, 3 );
		$this->assertSame( HandleState::Finished, $result->state );
	}

	public function testError() {
		$decoder = new ReplacementDecoder();
		$queue = Queue::newFromArray( [ 1, 2, 3 ] );

		$result = $decoder->handler( $queue, 4 );
		$this->assertSame( HandleState::Error, $result->state );
	}

	public function testFinishedAfterError() {
		$decoder = new ReplacementDecoder();
		$queue = Queue::newFromArray( [ 1, 2, 3 ] );

		$result = $decoder->handler( $queue, 4 );
		$result = $decoder->handler( $queue, 4 );
		$this->assertSame( HandleState::Finished, $result->state );
	}
}
