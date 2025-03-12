<?php

namespace Neoncitylights\Encoding\Encode\Tests;

use Neoncitylights\Encoding\Encode\TextEncoder;
use Neoncitylights\Encoding\Encoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPunit\Framework\TestCase;

#[CoversClass( TextEncoder::class )]
class TextEncoderTest extends TestCase {
	public function testDefault(): void {
		$defaultEncoder = new TextEncoder();
		$this->assertSame( Encoding::Utf8, $defaultEncoder->getEncoding() );
	}
}
