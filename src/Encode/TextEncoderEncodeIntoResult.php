<?php

namespace Neoncitylights\Encoding\Encode;

class TextEncoderEncodeIntoResult {
	public function __construct(
		public int $read,
		public int $written,
	) {
	}
}
