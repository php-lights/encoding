<?php

namespace Neoncitylights\Encoding\Utf16;

use Exception;
use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;

class SharedUtf16Decoder implements TextCodec {
	private ?int $leadByte;
	private ?int $leadingSurrogate;
	protected bool $isUtf16Decoder;

	public function __construct() {
		$this->leadByte = null;
		$this->leadingSurrogate = null;
		$this->isUtf16Decoder = false;
	}

	public function handler( Queue $queue, int $byte ): HandleStateResult {
		throw new Exception();
	}
}
