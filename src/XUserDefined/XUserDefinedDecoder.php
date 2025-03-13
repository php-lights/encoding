<?php

namespace Neoncitylights\Encoding\XUserDefined;

use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;
use Neoncitylights\Encoding\Utils;

/**
 * @see https://encoding.spec.whatwg.org/#x-user-defined-decoder
 */
class XUserDefinedDecoder implements TextCodec {
	public function handler( Queue $queue, int $byte ): HandleStateResult {
		if ( $queue->endOfQueue() === $byte ) {
			return HandleStateResult::asFinished();
		}

		if ( Utils::isAscii( $byte ) ) {
			return HandleStateResult::asOneOrMoreItems( [ $byte ] );
		}

		return HandleStateResult::asOneOrMoreItems( [ 0xF780 + $byte - 0x80 ] );
	}
}
