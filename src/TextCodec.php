<?php

namespace Neoncitylights\Encoding;

/**
 * @see https://encoding.spec.whatwg.org/#handler
 */
interface TextCodec {
	public function handler( Queue $queue, int $byteOrCodepoint ): HandleStateResult;
}
