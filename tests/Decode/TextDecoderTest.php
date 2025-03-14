<?php

namespace Neoncitylights\Encoding\Decode\Tests;

use Neoncitylights\Encoding\Decode\TextDecoder;
use Neoncitylights\Encoding\Decode\TextDecoderOptions;
use Neoncitylights\Encoding\Encoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( TextDecoder::class )]
#[UsesClass( TextDecoderOptions::class )]
class TextDecoderTest extends TestCase {
	#[DataProvider( 'provideTryNewOk' )]
	public function testTryNewOk(
		string $label,
		TextDecoderOptions $options,
		Encoding $expectedEncoding,
		bool $expectedFatal,
		bool $expectedIgnoreBom,
	): void {
		$decoder = TextDecoder::tryNew( $label, $options );
		$this->assertSame( $expectedEncoding, $decoder->getEncoding() );
		$this->assertSame( $expectedFatal, $decoder->getFatal() );
		$this->assertSame( $expectedIgnoreBom, $decoder->getIgnoreBom() );
	}

	public static function provideTryNewOk(): array {
		return [
			[
				'utf8',
				new TextDecoderOptions( false, false ),
				Encoding::Utf8,
				false,
				false,
			],
			[
				'utf8',
				new TextDecoderOptions( true, true ),
				Encoding::Utf8,
				true,
				true,
			],
		];
	}

	#[DataProvider( 'provideTryNewError' )]
	public function testTryNewError(
		string $label,
		TextDecoderOptions $options,
	): void {
		$decoder = TextDecoder::tryNew( $label, $options );
		$this->assertNull( $decoder );
	}

	public static function provideTryNewError(): array {
		return [
			[
				'foobar',
				new TextDecoderOptions( true, true ),
			],
			[
				'replacement',
				new TextDecoderOptions( true, true ),
			]
		];
	}
}
