<?php

namespace Neoncitylights\Encoding\Iso2022Jp;

/**
 * @see https://encoding.spec.whatwg.org/#iso-2022-jp-encoder-state
 * @internal
 */
enum EncoderState {
	case Ascii;
	case Roman;
	case Jis0208;
}
