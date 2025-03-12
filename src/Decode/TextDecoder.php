<?php

namespace Neoncitylights\Encoding\Decode;

use Neoncitylights\Encoding\Encoding;

class TextDecoder implements TextDecoderCommon {
	private Encoding $encoding;
	private bool $fatal;
	private bool $ignoreBom;
	private bool $doNotFlush;
	private DecodeError $errorMode;

	private function __construct(
		Encoding $encoding,
		TextDecoderOptions $options,
		DecodeError $errorMode,
	) {
		$this->encoding = $encoding;
		$this->fatal = $options->fatal;
		$this->ignoreBom = $options->ignoreBom;
		$this->doNotFlush = false;
		$this->errorMode = $errorMode;
	}

	public static function tryNew(
		string $label,
		TextDecoderOptions $options,
	): self|null {
		$encoding = Encoding::tryFromLabel( $label );
		if (
			$encoding === null
			|| $encoding === Encoding::Replacement
		) {
			return null;
		}

		$errorMode = DecodeError::Replacement;
		if ( $options->fatal ) {
			$errorMode = DecodeError::Fatal;
		}

		return new self(
			$encoding,
			$options,
			$errorMode,
		);
	}

	public function getEncoding(): Encoding {
		return $this->encoding;
	}

	public function getFatal(): bool {
		return $this->fatal;
	}

	public function getIgnoreBom(): bool {
		return $this->ignoreBom;
	}
}
