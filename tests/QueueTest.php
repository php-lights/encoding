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
		$this->assertSame( $initialSize, $queue->count() );

		$queue->push( $item );
		$this->assertSame( $pushedSize, $queue->count() );
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

	public function testEndOfQueue(): void {
		$queue = Queue::newFromArray( [ 1, 2, 3 ] );
		$this->assertSame( 3, $queue->endOfQueue() );
	}

	public function testEndOfQueueEmpty(): void {
		$queue = Queue::newEmpty();
		$this->assertNull( $queue->endOfQueue() );
	}

	#[DataProvider( 'providePeek' )]
	public function testPeek(
		array $queueItems,
		int $expectedSize,
		int $expectedPeek,
	): void {
		$queue = Queue::newFromArray( $queueItems );
		$this->assertCount( $expectedSize, $queue );
		$this->assertSame( $expectedPeek, $queue->peek() );
		$this->assertCount( $expectedSize, $queue );
	}

	public static function providePeek(): array {
		return [
			[
				[ 0, 1, 2 ],
				3,
				0,
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
		$this->assertSame( $initialSize, $queue->count() );

		$read = $queue->tryRead();
		$this->assertSame( $expected, $read );
		$this->assertSame( $afterReadSize, $queue->count() );
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

	public function testTryReadRetainsSizeOf1() {
		$queue = Queue::newFromArray( [ 42, 57, 63 ] );
		$this->assertCount( 3, $queue );

		// read once
		$q1 = $queue->tryRead();
		$this->assertSame( 42, $q1 );
		$this->assertCount( 2, $queue );

		// read twice
		$q2 = $queue->tryRead();
		$this->assertSame( 57, $q2 );
		$this->assertCount( 1, $queue );

		// read three times, retain size of 1
		$q3 = $queue->tryRead();
		$this->assertSame( 63, $q3 );
		$this->assertCount( 1, $queue );

		// read four times, should produce same results
		$q4 = $queue->tryRead();
		$this->assertSame( 63, $q4 );
		$this->assertCount( 1, $queue );
	}

	public function testTryReadRetainSizeOf1FromArray1() {
		$queue = Queue::newFromArray( [ 42 ] );
		$this->assertCount( 1, $queue );

		$val = $queue->tryRead();
		$this->assertSame( 42, $val );
	}
}
