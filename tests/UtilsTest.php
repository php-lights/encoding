<?php

namespace Neoncitylights\Encoding\Tests;

use Neoncitylights\Encoding\Utils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass( Utils::class )]
class UtilsTest extends TestCase {
	#[DataProvider( "provideScalarFromSurrogates" )]
	public function testScalarFromSurrogates(
		int $scalar,
		int $leading,
		int $trailing,
	): void {
		$this->assertSame( $scalar, Utils::getScalarFromSurrogate( $leading, $trailing ) );
	}

	public static function provideScalarFromSurrogates(): array {
		return [
			[ 0x1F600, 0xD83D, 0xDE00 ], // 😀 (U+1F600)
			[ 0x1F602, 0xD83D, 0xDE02 ], // 😂 (U+1F602)
			[ 0x10437, 0xD801, 0xDC37 ], // 𐐷 (U+10437)
			[ 0x10FFFF, 0xDBFF, 0xDFFF ],
		];
	}
}
