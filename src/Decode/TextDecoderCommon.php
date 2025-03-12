<?php

namespace Neoncitylights\Encoding\Decode;

use Neoncitylights\Encoding\Encoding;

/**
 * @see https://encoding.spec.whatwg.org/#textdecodercommon
 */
interface TextDecoderCommon {
	public function getEncoding(): Encoding;

	public function getFatal(): bool;

	public function getIgnoreBom(): bool;
}
