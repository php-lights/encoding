<?php

namespace Neoncitylights\Encoding\LegacyMisc;

use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;

/**
 * @see https://encoding.spec.whatwg.org/#replacement-decoder
 */
class ReplacementDecoder implements TextCodec {
	private bool $replacementErrorReturned;

	public function __construct() {
		$this->replacementErrorReturned = false;
	}

	public function handler( Queue $queue, int $byte ): HandleStateResult {
		if ( $queue->endOfQueue() === $byte ) {
			return HandleStateResult::asFinished();
		}

		if ( !$this->replacementErrorReturned ) {
			$this->replacementErrorReturned = true;
			return HandleStateResult::asError();
		}

		return HandleStateResult::asFinished();
	}
}
