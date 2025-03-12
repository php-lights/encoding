<?php

namespace Neoncitylights\Encoding;

class HandleStateValue {
	private function __construct(
		public readonly HandleState $handleState,
		public readonly null|int|array $value,
	) {
	}

	public static function asFinished() {
		return new self( HandleState::Finished, null );
	}

	public static function asOneOrMoreItems( array $items ) {
		return new self( HandleState::Finished, $items );
	}

	public static function asError( ?int $codePoint = null ) {
		return new self( HandleState::Finished, $codePoint );
	}

	public static function asContinue() {
		return new self( HandleState::Continue, null );
	}
}
