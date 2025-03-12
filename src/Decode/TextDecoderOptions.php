<?php

namespace Neoncitylights\Encoding\Decode;

/**
 * @codeCoverageIgnore
 */
class TextDecoderOptions {
	public function __construct(
		public bool $fatal = false,
		public bool $ignoreBom = false,
	) {
	}
}
