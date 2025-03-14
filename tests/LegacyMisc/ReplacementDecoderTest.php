<?php

namespace Neoncitylights\Encoding\LegacyMisc\Tests;

use Neoncitylights\Encoding\HandleState;
use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\LegacyMisc\ReplacementDecoder;
use Neoncitylights\Encoding\Queue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( ReplacementDecoder::class )]
#[UsesClass( HandleState::class )]
#[UsesClass( HandleStateResult::class )]
#[UsesClass( Queue::class )]
class ReplacementDecoderTest extends TestCase {
	public function testEndOfQueueEarlyFinished(): void {
		$decoder = new ReplacementDecoder();
		$queue = Queue::newFromArray( [ 1, 2, 3 ] );

		$result = $decoder->handler( $queue, 3 );
		$this->assertTrue( $result->state->isFinished() );
	}

	public function testError(): void {
		$decoder = new ReplacementDecoder();
		$queue = Queue::newFromArray( [ 1, 2, 3 ] );

		$result = $decoder->handler( $queue, 4 );
		$this->assertTrue( $result->state->isError() );
	}

	public function testFinishedAfterError(): void {
		$decoder = new ReplacementDecoder();
		$queue = Queue::newFromArray( [ 1, 2, 3 ] );

		$result = $decoder->handler( $queue, 4 );
		$result = $decoder->handler( $queue, 4 );
		$this->assertTrue( $result->state->isFinished() );
	}
}
