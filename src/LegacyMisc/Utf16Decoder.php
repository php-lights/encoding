<?php

namespace Neoncitylights\Encoding\LegacyMisc;

use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;
use Neoncitylights\Encoding\Utils;

/**
 * @see https://encoding.spec.whatwg.org/#shared-utf-16-decoder
 */
class Utf16Decoder implements TextCodec {
	private ?int $utf16LeadByte = null;
	private ?int $utf16LeadingSurrogate = null;

	public function __construct(
		public readonly bool $isBigEndian = true,
	) {
	}

	/**
	 * @see https://encoding.spec.whatwg.org/#utf-16be-decoder
	 */
	public static function forBigEndian(): self {
		return new self( true );
	}

	/**
	 * @see https://encoding.spec.whatwg.org/#utf-16le-decoder
	 */
	public static function forLittleEndian(): self {
		return new self( false );
	}

	public function handler( Queue $queue, int $byte ): HandleStateResult {
		if (
			$queue->endOfQueue() === $byte &&
			( $this->utf16LeadByte !== null || $this->utf16LeadingSurrogate !== null )
		) {
			$this->utf16LeadByte = null;
			$this->utf16LeadByte = null;
			return HandleStateResult::asError();
		}

		if (
			$queue->endOfQueue() === $byte
			&& $this->utf16LeadByte === null
			&& $this->utf16LeadingSurrogate === null
		) {
			return HandleStateResult::asFinished();
		}

		if ( $this->utf16LeadByte === null ) {
			$this->utf16LeadByte = $byte;
			return HandleStateResult::asContinue();
		}

		$codeUnit = match ( $this->isBigEndian ) {
			true => ( $this->utf16LeadByte << 8 ) + $byte,
			false => ( $byte << 8 ) + $this->utf16LeadByte,
		};
		$this->utf16LeadByte = null;

		if ( $this->utf16LeadingSurrogate !== null ) {
			$leadingSurrogate = $this->utf16LeadingSurrogate;
			$this->utf16LeadingSurrogate = null;

			if ( Utils::isTrailingSurrogate( $codeUnit ) ) {
				return HandleStateResult::asOneOrMoreItems( [
					Utils::getScalarFromSurrogate( $leadingSurrogate, $codeUnit )
				] );
			}

			$byte1 = $codeUnit >> 8;
			$byte2 = $codeUnit & 0x00FF;
			$bytes = match ( $this->isBigEndian ) {
				true => [ $byte1, $byte2 ],
				false => [ $byte2, $byte1 ],
			};
			$queue->restoreListOfItems( $bytes );

			return HandleStateResult::asError();
		}

		if ( Utils::isLeadingSurrogate( $codeUnit ) ) {
			$this->utf16LeadingSurrogate = $codeUnit;
			return HandleStateResult::asContinue();
		}

		if ( Utils::isTrailingSurrogate( $codeUnit ) ) {
			return HandleStateResult::asError();
		}

		return HandleStateResult::asOneOrMoreItems( [ $codeUnit ] );
	}
}
