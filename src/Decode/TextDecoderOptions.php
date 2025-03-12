<?php

namespace Neoncitylights\Encoding\Decode;

/**
 * @codeCoverageIgnore
 * @see https://encoding.spec.whatwg.org/#textdecoderoptions
 */
class TextDecoderOptions {
	public function __construct(
		public bool $fatal = false,
		public bool $ignoreBom = false,
	) {
	}
}
