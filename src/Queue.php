<?php

namespace Neoncitylights\Encoding;

use Countable;

/**
 * A queue data structure that runs in intermediate mode
 * instead of streaming mode, which therefore does not block.
 * End-of-queue items are represented as the last item.
 *
 * @internal
 * @see https://encoding.spec.whatwg.org/#concept-stream
 */
class Queue implements Countable {
	/** @var int[] */
	private array $array;
	private int $count;

	public function __construct( array $array, int $size ) {
		$this->array = $array;
		$this->count = $size;
	}

	public static function newEmpty(): self {
		return new self( [], 0 );
	}

	public static function newFromArray( array $array ) {
		return new self( $array, count( $array ) );
	}

	public function count(): int {
		return $this->count;
	}

	/** @see https://encoding.spec.whatwg.org/#end-of-stream */
	public function endOfQueue(): int|null {
		return $this->count() === 0
			? null
			: $this->array[$this->count() - 1];
	}

	/** @see https://encoding.spec.whatwg.org/#concept-stream-read */
	public function tryRead(): int|false {
		if ( $this->count === 0 ) {
			return false;
		}

		if ( $this->count === 1 ) {
			return $this->array[0];
		}

		$value = $this->array[0];
		\array_shift( $this->array );
		$this->count--;

		return $value;
	}

	/** @see https://encoding.spec.whatwg.org/#i-o-queue-peek */
	public function peek(): int|false {
		return \current( $this->array );
	}

	/** @see https://encoding.spec.whatwg.org/#i-o-queue-peek */
	public function push( int $item ): void {
		\array_push( $this->array, $item );
		$this->count++;
	}

	/**
	 * @see https://encoding.spec.whatwg.org/#concept-stream-prepend
	 */
	public function restoreItem( int $item ): void {
		\array_unshift( $this->array, $item );
		$this->count += 1;
	}

	/**
	 * @param int[] $items
	 * @see https://encoding.spec.whatwg.org/#concept-stream-prepend
	 */
	public function restoreListOfItems( array $items ): void {
		\array_unshift( $this->array, ...$items );
		$this->count += count( $items );
	}
}
