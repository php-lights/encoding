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

	#[DataProvider( "provideTryFromUtf8" )]
	public function testTryFromUtf8( ?Encoding $expectedEncoding, string $label ): void {
		$this->assertSame( $expectedEncoding, Encoding::tryFromLabel( $label ) );
	}

	public static function provideTryFromUtf8(): array {
		return [
			[ Encoding::Utf8, 'unicode-1-1-utf-8' ],
			[ Encoding::Utf8, 'unicode20utf8' ],
			[ Encoding::Utf8, 'utf-8' ],
			[ Encoding::Utf8, 'utf8' ],
			[ Encoding::Utf8, 'x-unicode20utf8' ],
			[ null, 'utf9' ],
		];
	}

	#[DataProvider( "provideTryFromIbm866" )]
	public function testTryFromIbm866( ?Encoding $expectedEncoding, string $label ): void {
		$this->assertSame( $expectedEncoding, Encoding::tryFromLabel( $label ) );
	}

	public static function provideTryFromIbm866(): array {
		return [
			[ Encoding::Ibm866, '866', ],
			[ Encoding::Ibm866, 'cp866' ],
			[ Encoding::Ibm866, 'csibm866' ],
			[ Encoding::Ibm866, 'ibm866' ],
			[ null, 'ibm867' ],
		];
	}
}
