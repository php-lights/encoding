<?php

namespace Neoncitylights\Encoding\EucJp;

use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;
use Neoncitylights\Encoding\Utils;

class EucJpDecoder implements TextCodec {
	private bool $jis0212;
	private int $lead;

	public function __construct() {
		$this->jis0212 = false;
		$this->lead = 0x00;
	}

	public function handler( Queue $queue, int $byte ): HandleStateResult {
		if ( $queue->endOfQueue() === $byte ) {
			if ( $byte !== 0x00 ) {
				$this->lead = 0x00;
				return HandleStateResult::asError();
			} else {
				return HandleStateResult::asFinished();
			}
		}

		if ( $this->lead == 0x8e && Utils::isWithin( $byte, 0xa1, 0xdf ) ) {
			$this->lead = 0x00;
			return HandleStateResult::asOneOrMoreItems( [ 0xff61 - 0xa1 + $byte ] );
		}

		if ( $this->lead == 0x8f && Utils::isWithin( $byte, 0xa1, 0xfe ) ) {
			$this->jis0212 = true;
			$this->lead = $byte;
			return HandleStateResult::asContinue();
		}

		if ( $this->lead !== 0x00 ) {
			$this->lead = 0x00;
			$codePoint = null;

			if (
				Utils::isWithin( $this->lead, 0xa1, 0xfe )
				&& Utils::isWithin( $byte, 0xa1, 0xfe )
			) {
				$codePoint = match ( $this->jis0212 ) {
					true => ( $this->lead - 0xa1 ) * 94 + $byte - 0xa1,
					false => ( $this->lead - 0xa1 ) * 94 + $byte - 0xa1,
				};
			}

			$this->jis0212 = false;
			if ( $codePoint !== null ) {
				return HandleStateResult::asOneOrMoreItems( [ $codePoint ] );
			}

			if ( Utils::isAscii( $byte ) ) {
				$queue->restoreItem( $byte );
			}

			return HandleStateResult::asError();
		}

		if ( Utils::isAscii( $byte ) ) {
			return HandleStateResult::asOneOrMoreItems( [ $byte ] );
		}

		if ( $byte === 0x8e || $byte === 0x8f || Utils::isWithin( $byte, 0xa1, 0xfe ) ) {
			$this->lead = $byte;
			return HandleStateResult::asContinue();
		}

		return HandleStateResult::asError();
	}
}
