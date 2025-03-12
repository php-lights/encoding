<?php

namespace Neoncitylights\Encoding\Tests;

use Neoncitylights\Encoding\Queue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass( Queue::class )]
class QueueTest extends TestCase {
	public function testNewEmpty() {
		$emptyQueue = Queue::newEmpty();
		$this->assertInstanceOf( Queue::class, $emptyQueue );
	}

	public function testNewFromArray() {
		$queue = Queue::newFromArray( [ 0, 1, 2, 3 ] );
		$this->assertInstanceOf( Queue::class, $queue );
	}

	#[DataProvider( "providePush" )]
	public function testPush(
		array $queueItems,
		int $initialSize,
		int $item,
		int $pushedSize,
	) {
		$queue = Queue::newFromArray( $queueItems );
		$this->assertSame( $initialSize, $queue->size() );

		$queue->push( $item );
		$this->assertSame( $pushedSize, $queue->size() );
	}

	public static function providePush(): array {
		return [
			[
				[],
				0, 1, 1,
			],
			[
				[ 0, 1, 2, 3 ],
				4, 4, 5,
			]
		];
	}

	#[DataProvider( "provideReadOk" )]
	public function testReadOk(
		array $queueItems,
		int $initialSize,
		int|false $expected,
		int $afterReadSize,
	) {
		$queue = Queue::newFromArray( $queueItems );
		$this->assertSame( $initialSize, $queue->size() );

		$read = $queue->tryRead();
		$this->assertSame( $expected, $read );
		$this->assertSame( $afterReadSize, $queue->size() );
	}

	public static function provideReadOk(): array {
		return [
			[
				[ 0, 1, 2, 3 ],
				4, // initial array size,
				0,
				3, // after-read size
			],
		];
	}

	public function testReadError() {
		$queue = Queue::newEmpty();
		$read = $queue->tryRead();
		$this->assertFalse( $read );
	}

	public function tryReadRetainsSizeOf1() {
		$queue = Queue::newFromArray( [ 42, 57, 63 ] );
		$this->assertSame( 3, $queue->size() );

		// read once
		$q1 = $queue->tryRead();
		$this->assertSame( 42, $q1 );
		$this->assertSame( 2, $queue->size() );

		// read twice
		$q2 = $queue->tryRead();
		$this->assertSame( 57, $q2 );
		$this->assertSame( 1, $queue->size() );

		// read three times
		$q3 = $queue->tryRead();
		$this->assertSame( 63, $q3 );
		$this->assertSame( 1, $queue->size() );

		// read four times, should produce same results
		$q4 = $queue->tryRead();
		$this->assertSame( 63, $q4 );
		$this->assertSame( 1, $queue->size() );
	}
}
