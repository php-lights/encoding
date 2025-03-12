<?php

namespace Neoncitylights\Encoding\EucJp;

use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;
use Neoncitylights\Encoding\Utils;

/**
 * @see https://encoding.spec.whatwg.org/#shared-utf-16-decoder
 */
class EucJpEncoder implements TextCodec {
	public function handler( Queue $queue, int $codePoint ): HandleStateResult {
		if ( $queue->endOfQueue() === $codePoint ) {
			return HandleStateResult::asFinished();
		}

		if ( Utils::isAscii( $codePoint ) ) {
			return HandleStateResult::asOneOrMoreItems( [ $codePoint ] );
		}

		if ( $codePoint === 0x00a5 ) {
			return HandleStateResult::asOneOrMoreItems( [ 0x5c ] );
		}

		if ( $codePoint === 0x203e ) {
			return HandleStateResult::asOneOrMoreItems( [ 0x7e ] );
		}

		if ( Utils::isWithin( $codePoint, 0xff61, 0xff9f ) ) {
			return HandleStateResult::asOneOrMoreItems( [ 0x8e, $codePoint - 0xff61 + 0xa1 ] );
		}

		if ( $codePoint === 0x2212 ) {
			$codePoint = 0xff0d;
		}

		// @todo: must be index pointer from index jis0208
		$pointer = 0;
		if ( $pointer === null ) {
			HandleStateResult::asError( $codePoint );
		}

		$lead = $pointer / 94 + 0xa1;
		$trail = $pointer % 94 + 0xa1;

		return HandleStateResult::asOneOrMoreItems( [ $lead, $trail ] );
	}
}
