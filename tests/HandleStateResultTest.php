<?php

namespace Neoncitylights\Encoding\Tests;

use Neoncitylights\Encoding\HandleState;
use Neoncitylights\Encoding\HandleStateResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( HandleStateResult::class )]
class HandleStateResultTest extends TestCase {
	public function testAsFinished(): void {
		$result = HandleStateResult::asFinished();
		$this->assertSame( HandleState::Finished, $result->state );
		$this->assertNull( $result->value );
	}

	public function testAsOneOrMoreItems(): void {
		$result = HandleStateResult::asOneOrMoreItems( [ 0, 1, 2 ] );
		$this->assertSame( HandleState::OneOrMore, $result->state );
		$this->assertSame( [ 0, 1, 2 ], $result->value );
	}

	public function testAsError(): void {
		$result = HandleStateResult::asError( 1 );
		$this->assertSame( HandleState::Error, $result->state );
		$this->assertSame( 1, $result->value );
	}

	public function testAsContinue(): void {
		$result = HandleStateResult::asContinue();
		$this->assertSame( HandleState::Continue, $result->state );
		$this->assertNull( $result->value );
	}
}
