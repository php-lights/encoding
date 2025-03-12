<?php

namespace Neoncitylights\Encoding;

class Queue {
	/** @var int[] */
	private array $array;
	private int $size;

	public function __construct( array $array, int $size ) {
		$this->array = $array;
		$this->size = $size;
	}

	public static function newEmpty(): self {
		return new self( [], 0 );
	}

	public static function newFromArray( array $array ) {
		return new self( $array, count( $array ) );
	}

	public function size(): int {
		return $this->size;
	}

	public function push( int $item ): void {
		\array_push( $this->array, $item );
		$this->size++;
	}

	public function peek(): int|false {
		return \current( $this->array );
	}

	public function tryRead(): int|false {
		if ( $this->size === 0 ) {
			return false;
		}

		if ( $this->size === 1 ) {
			return $this->array[0];
		}

		$value = $this->array[0];
		\array_shift( $this->array );
		$this->size--;

		return $value;
	}

	public function restoreItem( int $item ): void {
	}

	public function restoreListOfItems( array $items ): void {
	}
}
