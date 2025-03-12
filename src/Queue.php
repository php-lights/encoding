<?php

namespace Neoncitylights\Encoding;

use Countable;
use Exception;

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

	public function push( int $item ): void {
		\array_push( $this->array, $item );
		$this->count++;
	}

	public function peek(): int|false {
		return \current( $this->array );
	}

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

	/**
	 * @todo
	 * @codeCoverageIgnore
	 */
	public function restoreItem( int $item ): void {
		throw new Exception();
	}

	/**
	 * @todo
	 * @codeCoverageIgnore
	 */
	public function restoreListOfItems( array $items ): void {
		throw new Exception();
	}
}
