<?php

namespace Neoncitylights\Encoding\Encode;

use Neoncitylights\Encoding\Encoding;

/**
 * @see https://encoding.spec.whatwg.org/#textencodercommon
 */
interface TextEncoderCommon {
	public function getEncoding(): Encoding;
}
