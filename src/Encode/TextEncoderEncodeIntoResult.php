<?php

namespace Neoncitylights\Encoding\Encode;

/**
 * @codeCoverageIgnore
 * @see https://encoding.spec.whatwg.org/#dictdef-textencoderencodeintoresult
 */
class TextEncoderEncodeIntoResult {
	public function __construct(
		public int $read,
		public int $written,
	) {
	}
}
