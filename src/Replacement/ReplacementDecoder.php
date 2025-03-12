<?php

namespace Neoncitylights\Encoding\Replacement;

use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;

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
