<?php

namespace Neoncitylights\Encoding\Utf16\Tests;

use Neoncitylights\Encoding\HandleState;
use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\LegacyMisc\Utf16Decoder;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( Utf16Decoder::class )]
#[UsesClass( HandleState::class )]
#[UsesClass( HandleStateResult::class )]
#[UsesClass( Queue::class )]
class Utf16DecoderTest extends TestCase {
	public function testIsTextCodec(): void {
		$this->assertInstanceOf( TextCodec::class, Utf16Decoder::forBigEndian() );
		$this->assertInstanceOf( TextCodec::class, Utf16Decoder::forLittleEndian() );
	}

	public function testSanityChecks(): void {
		$this->assertTrue( Utf16Decoder::forBigEndian()->isBigEndian );
		$this->assertFalse( Utf16Decoder::forLittleEndian()->isBigEndian );
	}

	/**
	 * Decoder reached the first continue state and then
	 * reached the first error state, as follows:
	 * *If byte is end-of-queue and either UTF-16 lead byte
	 * or UTF-16 leading surrogate is non-null, set UTF-16 lead byte
	 * and UTF-16 leading surrogate to null, and return error.*
	 */
	#[DataProvider( 'provideFirstErrorState' )]
	public function testFirstErrorState(
		bool $isDecoderBigEndian,
		array $queueItems,
		int $byte1,
		int $byte2,
	): void {
		$decoder = new Utf16Decoder( $isDecoderBigEndian );
		$queue = Queue::newFromArray( $queueItems );
		$states = [];

		$result1 = $decoder->handler( $queue, $byte1 );
		\array_push( $states, $result1->state );

		$result2 = $decoder->handler( $queue, $byte2 );
		\array_push( $states, $result2->state );

		$this->assertSame(
			[
				HandleState::Continue,
				HandleState::Error,
			],
			$states
		);
	}

	public static function provideFirstErrorState(): array {
		return [
			[
				true,
				[ 0, 1, 2 ],
				3,
				2,
			],
			[
				false,
				[ 0, 1, 2 ],
				3,
				2,
			],
		];
	}

	/**
	 * Decoder reached the first finished state, as follows:
	 * *If byte is end-of-queue and UTF-16 lead byte
	 * and UTF-16 leading surrogate are null, return finished.*
	 */
	#[DataProvider( 'provideFirstFinishedState' )]
	public function testFirstFinishedState(
		bool $isDecoderBigEndian,
		array $queueItems,
		int $byte,
	): void {
		$decoder = new Utf16Decoder( $isDecoderBigEndian );
		$queue = Queue::newFromArray( $queueItems );

		$states = [];
		$result = $decoder->handler( $queue, $byte );
		\array_push( $states, $result->state );

		$this->assertSame(
			[ HandleState::Finished ],
			$states,
		);
	}

	public static function provideFirstFinishedState(): array {
		return [
			[
				true,
				[ 0, 1, 2 ],
				2,
			],
			[
				false,
				[ 0, 1, 2 ],
				2,
			]
		];
	}

	/**
	 * Decoder reached the first continue state, as follows:
	 * *If UTF-16 lead byte is null, set UTF-16 lead byte
	 * to byte and return continue.*
	 */
	#[DataProvider( 'provideFirstContinueState' )]
	public function testFirstContinueState(
		bool $isDecoderBigEndian,
		array $queueItems,
		int $byte,
	): void {
		$decoder = new Utf16Decoder( $isDecoderBigEndian );
		$queue = Queue::newFromArray( $queueItems );
		$states = [];

		$result = $decoder->handler( $queue, $byte );
		\array_push( $states, $result->state );

		$this->assertSame(
			[ HandleState::Continue ],
			$states
		);
	}

	public static function provideFirstContinueState(): array {
		return [
			[
				true,
				[ 0, 1, 2 ],
				3,
			],
			[
				false,
				[ 0, 1, 2 ],
				3,
			]
		];
	}

	#[DataProvider( 'provideSecondContinueState' )]
	public function testSecondContinueState(
		bool $isDecoderBigEndian,
		array $queueItems,
		int $byte1,
		int $byte2,
		int $byte3,
	): void {
		$decoder = new Utf16Decoder( $isDecoderBigEndian );
		$queue = Queue::newFromArray( $queueItems );
		$states = [];

		// Send high surrogate first
		$result1 = $decoder->handler( $queue, $byte1 );
		\array_push( $states, $result1->state );
		$result2 = $decoder->handler( $queue, $byte2 );
		\array_push( $states, $result2->state );

		// Start sending another surrogate
		$result3 = $decoder->handler( $queue, $byte3 );
		\array_push( $states, $result3->state );

		$this->assertSame(
			[
				HandleState::Continue,
				HandleState::Continue,
				HandleState::Continue,
			],
			$states
		);
	}

	public static function provideSecondContinueState(): array {
		return [
			[
				true,
				[ 0, 1, 2 ],
				0xD8, 0x00,
				0xDC
			],
		];
	}

	#[DataProvider( 'provideFirstOneOrMoreState' )]
	public function testFirstOneOrMoreState(
		bool $isDecoderBigEndian,
		array $queueItems,
		int $byte1,
		int $byte2,
		int $byte3,
		int $byte4,
	): void {
		$decoder = new Utf16Decoder( $isDecoderBigEndian );
		$queue = Queue::newFromArray( $queueItems );
		$states = [];

		// send high/trailing surrogate
		$result1 = $decoder->handler( $queue, $byte1 );
		\array_push( $states, $result1->state );
		$result2 = $decoder->handler( $queue, $byte2 );
		\array_push( $states, $result2->state );

		// send low/leading surrogate
		$result3 = $decoder->handler( $queue, $byte3 );
		\array_push( $states, $result3->state );
		$result4 = $decoder->handler( $queue, $byte4 );
		\array_push( $states, $result4->state );

		$this->assertSame(
			[
				HandleState::Continue,
				HandleState::Continue,
				HandleState::Continue,
				HandleState::OneOrMore,
			],
			$states
		);
	}

	public static function provideFirstOneOrMoreState(): array {
		return [
			[
				true,
				[],
				0xD8, 0x00,
				0xDC, 0x00,
			],
			[
				false,
				[],
				0x00, 0xD8,
				0x00, 0xDC,
			]
		];
	}

	#[DataProvider( 'provideSecondErrorState' )]
	public function testSecondErrorState(
		bool $isDecoderBigEndian,
		array $queueItems,
		int $byte1,
		int $byte2,
		int $byte3,
		int $byte4,
	): void {
		$decoder = new Utf16Decoder( $isDecoderBigEndian );
		$queue = Queue::newFromArray( $queueItems );
		$states = [];

		// Send high/trailing surrogate first
		$result1 = $decoder->handler( $queue, $byte1 );
		\array_push( $states, $result1->state );
		$result2 = $decoder->handler( $queue, $byte2 );
		\array_push( $states, $result2->state );

		// Send non-trailing surrogate (error case)
		$result3 = $decoder->handler( $queue, $byte3 );
		\array_push( $states, $result3->state );
		$result4 = $decoder->handler( $queue, $byte4 );
		\array_push( $states, $result4->state );

		$this->assertSame(
			[
				HandleState::Continue,
				HandleState::Continue,
				HandleState::Continue,
				HandleState::Error,
			],
			$states
		);
	}

	public static function provideSecondErrorState(): array {
		return [
			[
				true,
				[],
				0xD8, 0x00,
				0x00, 0x41
			],
			[
				false,
				[],
				0x00, 0xD8,
				0x41, 0x00,
			]
		];
	}

	#[DataProvider( 'provideThirdErrorState' )]
	public function testThirdErrorState(
		bool $isDecoderBigEndian,
		array $queueItems,
		int $byte1,
		int $byte2,
	): void {
		$decoder = new Utf16Decoder( $isDecoderBigEndian );
		$queue = Queue::newFromArray( $queueItems );
		$states = [];

		$result1 = $decoder->handler( $queue, $byte1 );
		\array_push( $states, $result1->state );
		$result2 = $decoder->handler( $queue, $byte2 );
		\array_push( $states, $result2->state );

		$this->assertSame(
			[
				HandleState::Continue,
				HandleState::Error,
			],
			$states
		);
	}

	public static function provideThirdErrorState(): array {
		return [
			[ true, [ 0, 1, 2 ], 0xDC, 0x00 ],
			[ false, [ 0, 1, 2 ], 0x00, 0xDC ],
		];
	}

	#[DataProvider( 'provideSecondOneOrMoreState' )]
	public function testSecondOneOrMoreState(
		bool $isDecoderBigEndian,
		array $queueItems,
		int $byte1,
		int $byte2,
	): void {
		$decoder = new Utf16Decoder( $isDecoderBigEndian );
		$queue = Queue::newFromArray( $queueItems );
		$states = [];

		$result1 = $decoder->handler( $queue, $byte1 );
		\array_push( $states, $result1->state );
		$result2 = $decoder->handler( $queue, $byte2 );
		\array_push( $states, $result2->state );

		$this->assertSame(
			[
				HandleState::Continue,
				HandleState::OneOrMore,
			],
			$states
		);
	}

	public static function provideSecondOneOrMoreState(): array {
		return [
			[ true, [], 0x00, 0x41 ],
			[ false, [], 0x41, 0x00 ],
		];
	}
}
