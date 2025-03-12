<?php

namespace Neoncitylights\Encoding\Encode;

/**
 * An error mode for encoders.
 * @see https://encoding.spec.whatwg.org/#error-mode
 */
enum EncodeError {
	case Fatal;
	case Html;
}
