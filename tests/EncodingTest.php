<?php

use Neoncitylights\Encoding\Encoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass( Encoding::class )]
class EncodingTest extends TestCase {
	#[DataProvider( 'provideIsUtf8' )]
	public function testIsUtf8(): void {
		$this->assertTrue( Encoding::Utf8->isUtf8() );
	}

	public static function provideIsUtf8() {
		return [
			[ true, Encoding::Utf8 ],
			[ false, Encoding::Replacement ],
		];
	}

	#[DataProvider( 'provideIsLegacySb' )]
	public function testIsLegacySb( bool $expected, Encoding $encoding ): void {
		$this->assertSame( $expected, $encoding->isLegacySb() );
	}

	public static function provideIsLegacySb() {
		return [
			[ true, Encoding::Ibm866 ],
			[ true, Encoding::Iso8859_2 ],
			[ true, Encoding::Iso8859_3 ],
			[ true, Encoding::Iso8859_4 ],
			[ true, Encoding::Iso8859_5 ],
			[ true, Encoding::Iso8859_6 ],
			[ true, Encoding::Iso8859_7 ],
			[ true, Encoding::Iso8859_8 ],
			[ true, Encoding::Iso8859_8I ],
			[ true, Encoding::Iso8859_10 ],
			[ true, Encoding::Iso8859_13 ],
			[ true, Encoding::Iso8859_14 ],
			[ true, Encoding::Iso8859_15 ],
			[ true, Encoding::Iso8859_16 ],
			[ true, Encoding::Koi8R ],
			[ true, Encoding::Koi8U ],
			[ true, Encoding::Macintosh ],
			[ true, Encoding::Windows874 ],
			[ true, Encoding::Windows1250 ],
			[ true, Encoding::Windows1251 ],
			[ true, Encoding::Windows1252 ],
			[ true, Encoding::Windows1253 ],
			[ true, Encoding::Windows1254 ],
			[ true, Encoding::Windows1255 ],
			[ true, Encoding::Windows1256 ],
			[ true, Encoding::Windows1257 ],
			[ true, Encoding::Windows1258 ],
			[ true, Encoding::XMacCyrillic ],
			[ false, Encoding::Utf8 ],
		];
	}

	#[DataProvider( 'provideIsLegacyMbChineseSimplified' )]
	public function testIsLegacyMbChineseSimplified( bool $expected, Encoding $encoding ): void {
		$this->assertSame( $expected, $encoding->isLegacyMbChineseSimplified() );
	}

	public static function provideIsLegacyMbChineseSimplified(): array {
		return [
			[ true, Encoding::Gbk ],
			[ true, Encoding::Gb18030 ],
			[ true, Encoding::Big5 ],
			[ false, Encoding::Utf8 ],
		];
	}

	#[DataProvider( 'provideIsLegacyMbChineseTraditional' )]
	public function testIsLegacyMbChineseTraditional( bool $expected, Encoding $encoding ): void {
		$this->assertSame( $expected, $encoding->isLegacyMbChineseTraditional() );
	}

	public static function provideIsLegacyMbChineseTraditional(): array {
		return [
			[ true, Encoding::EucJp ],
			[ true, Encoding::Iso2022Jp ],
			[ true, Encoding::ShiftJis ],
			[ false, Encoding::Utf8 ],
		];
	}

	#[DataProvider( 'provideIsLegacyMbKorean' )]
	public function testIsLegacyMbKorean( bool $expected, Encoding $encoding ): void {
		$this->assertSame( $expected, $encoding->isLegacyMbKorean() );
	}

	public static function provideIsLegacyMbKorean(): array {
		return [
			[ true, Encoding::EucKr ],
			[ false, Encoding::Utf8 ],
		];
	}

	#[DataProvider( 'provideIsLegacyMisc' )]
	public function testIsLegacyMisc( bool $expected, Encoding $encoding ): void {
		$this->assertSame( $expected, $encoding->isLegacyMisc() );
	}

	public static function provideIsLegacyMisc(): array {
		return [
			[ true, Encoding::Replacement ],
			[ true, Encoding::Utf16Be ],
			[ true, Encoding::Utf16Le ],
			[ true, Encoding::XUserDefined ],
			[ false, Encoding::Utf8 ],
		];
	}

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
