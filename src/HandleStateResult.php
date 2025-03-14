<?php

namespace Neoncitylights\Encoding;

/**
 * The state for an encoder/decoder handler, optionally paired with a value.
 * @see https://encoding.spec.whatwg.org/#handler
 */
class HandleStateResult {
	private function __construct(
		public readonly HandleState $state,
		public readonly null|int|array $value,
	) {
	}

	public static function asFinished(): self {
		return new self( HandleState::Finished, null );
	}

	/**
	 * @param int[] $items
	 */
	public static function asOneOrMoreItems( array $items ): self {
		return new self( HandleState::OneOrMore, $items );
	}

	public static function asError( ?int $codePoint = null ): self {
		return new self( HandleState::Error, $codePoint );
	}

	public static function asContinue(): self {
		return new self( HandleState::Continue, null );
	}
}
