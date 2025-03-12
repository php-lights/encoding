<?php

use Neoncitylights\Encoding\Encoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass( Encoding::class )]
class EncodingTest extends TestCase {
	#[DataProvider( 'provideGetOutput' )]
	public function testGetOutput( Encoding $expectedOutput, $encodingGiven ): void {
		$this->assertSame( $expectedOutput, $encodingGiven->getOutput() );
	}

	public static function provideGetOutput(): array {
		return [
			[ Encoding::Utf8, Encoding::Replacement ],
			[ Encoding::Utf8, Encoding::Utf16Be ],
			[ Encoding::Utf8, Encoding::Utf16Le ],
			[ Encoding::Koi8U, Encoding::Koi8U ],
		];
	}
}
