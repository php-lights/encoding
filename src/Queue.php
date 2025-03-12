<?php

namespace Neoncitylights\Encoding;

class Queue {
	/** @var int[] */
	private array $array;
	private int $count;

	public function __construct( array $array, int $count ) {
		$this->array = $array;
		$this->count = $count;
	}

	public static function newEmpty(): self {
		return new self( [], 0 );
	}

	public static function newFromArray( array $array ) {
		return new self( $array, count( $array ) );
	}

	public function push( int $item ): void {
		\array_push( $this->array, $item );
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
		unset( $this->array[0] );
		$this->count--;

		return $value;
	}

	public function restoreItem( int $item ): void {
	}

	public function restoreListOfItems( array $items ): void {
	}
}
