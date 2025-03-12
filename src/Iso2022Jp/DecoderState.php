<?php

namespace Neoncitylights\Encoding\Iso2022Jp;

/**
 * @see https://encoding.spec.whatwg.org/#iso-2022-jp-decoder-state
 * @internal
 */
enum DecoderState {
	case Ascii;
	case Roman;
	case Katakana;
	case LeadByte;
	case TrailByte;
	case EscapeStart;
	case Escape;
}
