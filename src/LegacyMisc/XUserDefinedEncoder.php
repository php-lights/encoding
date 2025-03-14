<?php

namespace Neoncitylights\Encoding\LegacyMisc;

use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;
use Neoncitylights\Encoding\Utils;

/**
 * @see https://encoding.spec.whatwg.org/#x-user-defined-encoder
 */
class XUserDefinedEncoder implements TextCodec {
	public function handler( Queue $queue, int $codePoint ): HandleStateResult {
		if ( $queue->endOfQueue() === $codePoint ) {
			return HandleStateResult::asFinished();
		}

		if ( Utils::isAscii( $codePoint ) ) {
			return HandleStateResult::asOneOrMoreItems( [ $codePoint ] );
		}

		if ( Utils::isWithin( $codePoint, 0xF780, 0xF7FF ) ) {
			return HandleStateResult::asOneOrMoreItems( [ $codePoint - 0xF780 + 0x80 ] );
		}

		return HandleStateResult::asError( $codePoint );
	}
}
