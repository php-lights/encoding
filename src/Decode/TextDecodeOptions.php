<?php

namespace Neoncitylights\Encoding\Decode;

/**
 * @codeCoverageIgnore
 * @see https://encoding.spec.whatwg.org/#textdecodeoptions
 */
class TextDecodeOptions {
	public function __construct(
		public bool $stream = false,
	) {
	}
}
