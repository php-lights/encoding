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
	 * @todo
	 * @codeCoverageIgnore
	 */
	public function encode( string $input ): array {
		throw new Exception();
	}

	/**
	 * @todo
	 * @codeCoverageIgnore
	 */
	public function encodeInto( string $source, array $dest ): TextEncoderEncodeIntoResult {
		throw new Exception();
	}
}
