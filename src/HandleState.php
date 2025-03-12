<?php

namespace Neoncitylights\Encoding;

/**
 * The state for an encoder/decoder handler.
 * @see https://encoding.spec.whatwg.org/#handler
 */
enum HandleState {
	case Finished;
	case OneOrMore;
	case Error;
	case Continue;

	public function isFinished(): bool {
		return $this === self::Finished;
	}

	public function isOneOrMore(): bool {
		return $this === self::OneOrMore;
	}

	public function isError(): bool {
		return $this === self::Error;
	}

	public function isContinue(): bool {
		return $this === self::Continue;
	}
}
