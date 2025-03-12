<?php

namespace Neoncitylights\Encoding\Decode;

/**
 * An error mode for decoders.
 * @see https://encoding.spec.whatwg.org/#error-mode
 */
enum DecodeError {
	case Fatal;
	case Replacement;
}
