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
			// #0: big endian
			[
				true,
				[ 0, 1, 2 ],
				3,
				2,
			]
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
			// #0: big endian
			[
				true,
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
			]
		];
	}

	// @TODO: CONVERT TO USE DATA PROVIDER
	public function testSecondContinueState(): void {
		$decoder = Utf16Decoder::forBigEndian();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );
		$states = [];

		// Send high surrogate first
		$result1 = $decoder->handler( $queue, 0xD8 );
		\array_push( $states, $result1->state );
		$result2 = $decoder->handler( $queue, 0x00 );
		\array_push( $states, $result2->state );

		// Start sending another surrogate
		$result3 = $decoder->handler( $queue, 0xDC );
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

	// @TODO: CONVERT TO USE DATA PROVIDER
	public function testFirstOneOrMoreState(): void {
		$decoder = Utf16Decoder::forBigEndian();
		$queue = Queue::newFromArray( [] );
		$states = [];

		// send high/trailing surrogate
		$result1 = $decoder->handler( $queue, 0xd8 );
		\array_push( $states, $result1->state );
		$result2 = $decoder->handler( $queue, 0x00 );
		\array_push( $states, $result2->state );

		// send low/leading surrogate
		$result3 = $decoder->handler( $queue, 0xdc );
		\array_push( $states, $result3->state );
		$result4 = $decoder->handler( $queue, 0x00 );
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

	// @TODO: CONVERT TO USE DATA PROVIDER
	public function testSecondErrorState(): void {
		$decoder = Utf16Decoder::forBigEndian();
		$queue = Queue::newFromArray( [ 0, 1, 2 ] );
		$states = [];

		// Send high/trailing surrogate first
		$result1 = $decoder->handler( $queue, 0xD8 );
		\array_push( $states, $result1->state );
		$result2 = $decoder->handler( $queue, 0x00 );
		\array_push( $states, $result2->state );

		// Send non-trailing surrogate (error case)
		$result3 = $decoder->handler( $queue, 0x00 );
		\array_push( $states, $result3->state );
		$result4 = $decoder->handler( $queue, 0x41 );
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

		// Send trailing surrogate without a leading surrogate first
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
		];
	}
}
