<?php

namespace Neoncitylights\Encoding\Encode;

/**
 * @codeCoverageIgnore
 */
class TextEncoderEncodeIntoResult {
	public function __construct(
		public int $read,
		public int $written,
	) {
	}
}
