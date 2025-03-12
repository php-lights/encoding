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
