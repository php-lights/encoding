<?php

namespace Neoncitylights\Encoding\Encode;

use Exception;
use Neoncitylights\Encoding\Encoding;

class TextEncoder implements TextEncoderCommon {
	private Encoding $encoding;

	public function __construct() {
		$this->encoding = Encoding::Utf8;
	}

	public function getEncoding(): Encoding {
		return $this->encoding;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function encode( string $input ): array {
		// TODO: implement method
		throw new Exception();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function encodeInto( string $source, array $dest ): TextEncoderEncodeIntoResult {
		// TODO: implement method
		throw new Exception();
	}
}
