<?php

namespace Neoncitylights\Encoding\Decode;

use Neoncitylights\Encoding\Encoding;

interface TextDecoderCommon {
	public function getEncoding(): Encoding;

	public function getFatal(): bool;

	public function getIgnoreBom(): bool;
}
