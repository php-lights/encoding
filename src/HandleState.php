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
		return $this === Self::Finished;
	}

	public function isOneOrMore(): bool {
		return $this === Self::OneOrMore;
	}

	public function isError(): bool {
		return $this === Self::Error;
	}

	public function isContinue(): bool {
		return $this === Self::Continue;
	}
}
