<?php

namespace Neoncitylights\Encoding;

enum HandleState {
	/** @see https://encoding.spec.whatwg.org/#finished */
	case Finished;
	case OneOrMore;
	/** @see https://encoding.spec.whatwg.org/#error */
	case Error;
	/** @see https://encoding.spec.whatwg.org/#continue */
	case Continue;
}
