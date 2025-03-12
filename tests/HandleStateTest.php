<?php

namespace Neoncitylights\Encoding\Tests;

use Neoncitylights\Encoding\HandleState;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( HandleState::class )]
class HandleStateTest extends TestCase {
	public function testIsFinished(): void {
		$this->assertTrue( HandleState::Finished->isFinished() );
	}

	public function testIsOneOrMore(): void {
		$this->assertTrue( HandleState::OneOrMore->isOneOrMore() );
	}

	public function testIsError(): void {
		$this->assertTrue( HandleState::Error->isError() );
	}

	public function testIsContinue(): void {
		$this->assertTrue( HandleState::Continue->isContinue() );
	}
}
