<?php

namespace Neoncitylights\Encoding;

/**
 * An enum of all encoding variants that a user agent must support.
 *
 * Note that while every variant is tied to multiple labels,
 * every variant is backed by a "main" label, which is defined
 * as the encoding converted via ASCII-lowering.
 *
 * @see https://encoding.spec.whatwg.org/#names-and-labels
 */
enum Encoding: string {
	// Main encoding
	case Utf8 = 'utf-8';

	// Legacy single-byte encodings
	case Ibm866 = 'ibm866';
	case Iso8859_2 = 'iso-8859-2';
	case Iso8859_3 = 'iso-8859-3';
	case Iso8859_4 = 'iso-8859-4';
	case Iso8859_5 = 'iso-8859-5';
	case Iso8859_6 = 'iso-8859-6';
	case Iso8859_7 = 'iso-8859-7';
	case Iso8859_8 = 'iso-8859-8';
	case Iso8859_8I = 'iso-8859-8i';
	case Iso8859_10 = 'iso-8859-10';
	case Iso8859_13 = 'iso-8859-13';
	case Iso8859_14 = 'iso-8859-14';
	case Iso8859_15 = 'iso-8859-15';
	case Iso8859_16 = 'iso-8859-16';
	case Koi8R = 'koi8-r';
	case Koi8U = 'koi8-u';
	case Macintosh = 'macintosh';
	case Windows874 = 'windows-874';
	case Windows1250 = 'windows-1250';
	case Windows1251 = 'windows-1251';
	case Windows1252 = 'windows-1252';
	case Windows1253 = 'windows-1253';
	case Windows1254 = 'windows-1254';
	case Windows1255 = 'windows-1255';
	case Windows1256 = 'windows-1256';
	case Windows1257 = 'windows-1257';
	case Windows1258 = 'windows-1258';
	case XMacCyrillic = 'x-mac-cyrillic';

	// Legacy multi-byte Chinese (simplified) encodings
	case Gbk = 'gbk';
	case Gb18030 = 'gb18030';

	// Legacy multi-byte Chinese (traditional) encodings
	case Big5 = 'big5';

	// Legacy multi-byte Japanese encodings
	case EucJp = 'euc-jp';
	case Iso2022Jp = 'iso-2022-jp';
	case ShiftJis = 'shift_jis';

	// Legacy multi-byte Korean encodings
	case EucKr = 'euc-kr';

	// Legacy miscellaneous encodings
	case Replacement = 'replacement';
	case Utf16Be = 'utf-16be';
	case Utf16Le = 'utf-16le';
	case XUserDefined = 'x-user-defined';

	/**
	 * Checks if the encoding is UTF-8.
	 * @see https://encoding.spec.whatwg.org/#the-encoding
	 */
	public function isUtf8(): bool {
		return $this === self::Utf8;
	}

	/**
	 * Checks if the encoding is a legacy single-byte encoding.
	 * @see https://encoding.spec.whatwg.org/#legacy-single-byte-encodings
	 */
	public function isLegacySb(): bool {
		return match ( $this ) {
			self::Ibm866,
			self::Iso8859_2,
			self::Iso8859_3,
			self::Iso8859_4,
			self::Iso8859_5,
			self::Iso8859_6,
			self::Iso8859_7,
			self::Iso8859_8,
			self::Iso8859_8I,
			self::Iso8859_10,
			self::Iso8859_13,
			self::Iso8859_14,
			self::Iso8859_15,
			self::Iso8859_16,
			self::Koi8R,
			self::Koi8U,
			self::Macintosh,
			self::Windows874,
			self::Windows1250,
			self::Windows1251,
			self::Windows1252,
			self::Windows1253,
			self::Windows1254,
			self::Windows1255,
			self::Windows1256,
			self::Windows1257,
			self::Windows1258,
			self::XMacCyrillic => true,
			default => false,
		};
	}

	/**
	 * Checks if the encoding is a legacy multi-byte Chinese (simplified) encoding.
	 * @see https://encoding.spec.whatwg.org/#legacy-multi-byte-chinese-(simplified)-encodings
	 */
	public function isLegacyMbChineseSimplified(): bool {
		return match ( $this ) {
			self::Gbk,
			self::Gb18030 => true,
			default => false,
		};
	}

	/**
	 * Checks if the encoding is a legacy multi-byte Chinese (traditional) encoding.
	 * @see https://encoding.spec.whatwg.org/#legacy-multi-byte-chinese-(traditional)-encodings
	 */
	public function isLegacyMbChineseTraditional(): bool {
		return $this === self::Big5;
	}

	/**
	 * Checks if the encoding is a legacy multi-byte Japanese encoding.
	 * @see https://encoding.spec.whatwg.org/#legacy-multi-byte-japanese-encodings
	 */
	public function isLegacyMbJapanese(): bool {
		return match ( $this ) {
			self::EucJp,
			self::Iso2022Jp,
			self::ShiftJis => true,
			default => false,
		};
	}

	/**
	 * Checks if the encoding is a legacy multi-byte Korean encoding.
	 * @see https://encoding.spec.whatwg.org/#legacy-multi-byte-korean-encodings
	 */
	public function isLegacyMbKorean(): bool {
		return $this === self::EucKr;
	}

	/**
	 * Checks if the encoding is a legacy-miscellaneous encoding.
	 * @see https://encoding.spec.whatwg.org/#legacy-miscellaneous-encodings
	 */
	public function isLegacyMisc(): bool {
		return match ( $this ) {
			self::Replacement,
			self::Utf16Be,
			self::Utf16Le,
			self::XUserDefined => true,
			default => false,
		};
	}

	/**
	 * Checks if the encoding is either UTF-16 BE (Big Endian) or
	 * UTF-16 LE (Little Endian).
	 * @see https://encoding.spec.whatwg.org/#utf-16be-le
	 */
	public function isUtf16BeLe(): bool {
		return match ( $this ) {
			self::Utf16Be,
			self::Utf16Le => true,
			default => false,
		};
	}

	/**
	 * Get an output encoding, useful for URL parsing and HTML form submission.
	 * @see https://encoding.spec.whatwg.org/#get-an-output-encoding
	 */
	public function getOutput(): self {
		return match ( $this ) {
			self::Replacement, self::Utf16Le, self::Utf16Be => self::Utf8,
			default => $this,
		};
	}

	/**
	 * Attempts to return an encoding variant from a label.
	 * If no encoding matches, returns null.
	 * @see https://encoding.spec.whatwg.org/#concept-encoding-get
	 */
	public static function tryFromLabel( string $label ): ?Encoding {
		static $map = [
			'unicode-1-1-utf-8' => Encoding::Utf8,
			'unicode11utf8'     => Encoding::Utf8,
			'unicode20utf8'     => Encoding::Utf8,
			'utf-8'             => Encoding::Utf8,
			'utf8'              => Encoding::Utf8,
			'x-unicode20utf8'   => Encoding::Utf8,

			'866'               => Encoding::Ibm866,
			'cp866'             => Encoding::Ibm866,
			'csibm866'          => Encoding::Ibm866,
			'ibm866'            => Encoding::Ibm866,

			'csisolatin2'       => Encoding::Iso8859_2,
			'iso-8859-2'        => Encoding::Iso8859_2,
			'iso-ir-101'        => Encoding::Iso8859_2,
			'iso8859-2'         => Encoding::Iso8859_2,
			'iso88592'          => Encoding::Iso8859_2,
			'iso_8859-2'        => Encoding::Iso8859_2,
			'iso_8859-2:1987'   => Encoding::Iso8859_2,
			'l2'                => Encoding::Iso8859_2,
			'latin2'            => Encoding::Iso8859_2,

			'csisolatin3'       => Encoding::Iso8859_3,
			'iso-8859-3'        => Encoding::Iso8859_3,
			'iso-ir-109'        => Encoding::Iso8859_3,
			'iso8859-3'         => Encoding::Iso8859_3,
			'iso88593'          => Encoding::Iso8859_3,
			'iso_8859-3'        => Encoding::Iso8859_3,
			'iso_8859-3:1988'   => Encoding::Iso8859_3,
			'l3'                => Encoding::Iso8859_3,
			'latin3'            => Encoding::Iso8859_3,

			'csisolatin4'       => Encoding::Iso8859_4,
			'iso-8859-4'        => Encoding::Iso8859_4,
			'iso-ir-110'        => Encoding::Iso8859_4,
			'iso8859-4'         => Encoding::Iso8859_4,
			'iso88594'          => Encoding::Iso8859_4,
			'iso_8859-4'        => Encoding::Iso8859_4,
			'iso_8859-4:1988'   => Encoding::Iso8859_4,
			'l4'                => Encoding::Iso8859_4,
			'latin4'            => Encoding::Iso8859_4,

			'csisolatincyrillic' => Encoding::Iso8859_5,
			'cyrillic'          => Encoding::Iso8859_5,
			'iso-8859-5'        => Encoding::Iso8859_5,
			'iso-ir-144'        => Encoding::Iso8859_5,
			'iso8859-5'         => Encoding::Iso8859_5,
			'iso88595'          => Encoding::Iso8859_5,
			'iso_8859-5'        => Encoding::Iso8859_5,
			'iso_8859-5:1988'   => Encoding::Iso8859_5,

			'arabic'            => Encoding::Iso8859_6,
			'asmo-708'          => Encoding::Iso8859_6,
			'csiso88596e'       => Encoding::Iso8859_6,
			'csiso88596i'       => Encoding::Iso8859_6,
			'csisolatinarabic'  => Encoding::Iso8859_6,
			'ecma-114'          => Encoding::Iso8859_6,
			'iso-8859-6'        => Encoding::Iso8859_6,
			'iso-8859-6-e'      => Encoding::Iso8859_6,
			'iso-8859-6-i'      => Encoding::Iso8859_6,
			'iso-ir-127'        => Encoding::Iso8859_6,
			'iso8859-6'         => Encoding::Iso8859_6,
			'iso88596'          => Encoding::Iso8859_6,
			'iso_8859-6'        => Encoding::Iso8859_6,
			'iso_8859-6:1987'   => Encoding::Iso8859_6,

			'csisolatingreek'   => Encoding::Iso8859_7,
			'ecma-118'          => Encoding::Iso8859_7,
			'elot_928'          => Encoding::Iso8859_7,
			'greek'             => Encoding::Iso8859_7,
			'greek8'            => Encoding::Iso8859_7,
			'iso-8859-7'        => Encoding::Iso8859_7,
			'iso-ir-126'        => Encoding::Iso8859_7,
			'iso8859-7'         => Encoding::Iso8859_7,
			'iso88597'          => Encoding::Iso8859_7,
			'iso_8859-7'        => Encoding::Iso8859_7,
			'iso_8859-7:1987'   => Encoding::Iso8859_7,
			'sun_eu_greek'      => Encoding::Iso8859_7,

			'csiso88598e'       => Encoding::Iso8859_8,
			'csisolatinhebrew'  => Encoding::Iso8859_8,
			'hebrew'            => Encoding::Iso8859_8,
			'iso-8859-8'        => Encoding::Iso8859_8,
			'iso-8859-8-e'      => Encoding::Iso8859_8,
			'iso-ir-138'        => Encoding::Iso8859_8,
			'iso8859-8'         => Encoding::Iso8859_8,
			'iso88598'          => Encoding::Iso8859_8,
			'iso_8859-8'        => Encoding::Iso8859_8,
			'iso_8859-8:1988'   => Encoding::Iso8859_8,
			'visual'            => Encoding::Iso8859_8,

			'csiso88598i'       => Encoding::Iso8859_8I,
			'iso-8859-8-i'      => Encoding::Iso8859_8I,
			'logical'           => Encoding::Iso8859_8I,

			'csisolatin6'       => Encoding::Iso8859_10,
			'iso-8859-10'       => Encoding::Iso8859_10,
			'iso-ir-157'        => Encoding::Iso8859_10,
			'iso8859-10'        => Encoding::Iso8859_10,
			'iso885910'         => Encoding::Iso8859_10,
			'l6'                => Encoding::Iso8859_10,
			'latin6'            => Encoding::Iso8859_10,

			'iso-8859-13'       => Encoding::Iso8859_13,
			'iso8859-13'        => Encoding::Iso8859_13,
			'iso885913'         => Encoding::Iso8859_13,

			'iso-8859-14'       => Encoding::Iso8859_14,
			'iso8859-14'        => Encoding::Iso8859_14,
			'iso885914'         => Encoding::Iso8859_14,

			'csisolatin9'       => Encoding::Iso8859_15,
			'iso-8859-15'       => Encoding::Iso8859_15,
			'iso8859-15'        => Encoding::Iso8859_15,
			'iso885915'         => Encoding::Iso8859_15,
			'iso_8859-15'       => Encoding::Iso8859_15,
			'l9'                => Encoding::Iso8859_15,

			'iso-8859-16'       => Encoding::Iso8859_16,

			'cskoi8r'           => Encoding::Koi8R,
			'koi'               => Encoding::Koi8R,
			'koi8'              => Encoding::Koi8R,
			'koi8-r'            => Encoding::Koi8R,
			'koi8_r'            => Encoding::Koi8R,

			'koi8-ru'           => Encoding::Koi8U,
			'koi8-u'            => Encoding::Koi8U,

			'csmacintosh'       => Encoding::Macintosh,
			'mac'               => Encoding::Macintosh,
			'macintosh'         => Encoding::Macintosh,
			'x-mac-roman'       => Encoding::Macintosh,

			'dos-874'           => Encoding::Windows874,
			'iso-8859-11'       => Encoding::Windows874,
			'iso8859-11'        => Encoding::Windows874,
			'iso885911'         => Encoding::Windows874,
			'tis-620'           => Encoding::Windows874,
			'windows-874'       => Encoding::Windows874,

			'cp1250'            => Encoding::Windows1250,
			'windows-1250'      => Encoding::Windows1250,
			'x-cp1250'          => Encoding::Windows1250,

			'cp1251'            => Encoding::Windows1251,
			'windows-1251'      => Encoding::Windows1251,
			'x-cp1251'          => Encoding::Windows1251,

			'ansi_x3.4-1968'    => Encoding::Windows1252,
			'ascii'             => Encoding::Windows1252,
			'cp1252'            => Encoding::Windows1252,
			'cp819'             => Encoding::Windows1252,
			'csisolatin1'       => Encoding::Windows1252,
			'ibm819'            => Encoding::Windows1252,
			'iso-8859-1'        => Encoding::Windows1252,
			'iso-ir-100'        => Encoding::Windows1252,
			'iso8859-1'         => Encoding::Windows1252,
			'iso88591'          => Encoding::Windows1252,
			'iso_8859-1'        => Encoding::Windows1252,
			'iso_8859-1:1987'   => Encoding::Windows1252,
			'l1'                => Encoding::Windows1252,
			'latin1'            => Encoding::Windows1252,
			'us-ascii'          => Encoding::Windows1252,
			'windows-1252'      => Encoding::Windows1252,
			'x-cp1252'          => Encoding::Windows1252,

			'cp1253'            => Encoding::Windows1253,
			'windows-1253'      => Encoding::Windows1253,
			'x-cp1253'          => Encoding::Windows1253,

			'cp1254'            => Encoding::Windows1254,
			'csisolatin5'       => Encoding::Windows1254,
			'iso-8859-9'        => Encoding::Windows1254,
			'iso-ir-148'        => Encoding::Windows1254,
			'iso8859-9'         => Encoding::Windows1254,
			'iso88599'          => Encoding::Windows1254,
			'iso_8859-9'        => Encoding::Windows1254,
			'iso_8859-9:1989'   => Encoding::Windows1254,
			'l5'                => Encoding::Windows1254,
			'latin5'            => Encoding::Windows1254,
			'windows-1254'      => Encoding::Windows1254,
			'x-cp1254'          => Encoding::Windows1254,

			'cp1255'            => Encoding::Windows1255,
			'windows-1255'      => Encoding::Windows1255,
			'x-cp1255'          => Encoding::Windows1255,

			'cp1256'            => Encoding::Windows1256,
			'windows-1256'      => Encoding::Windows1256,
			'x-cp1256'          => Encoding::Windows1256,

			'cp1257'            => Encoding::Windows1257,
			'windows-1257'      => Encoding::Windows1257,
			'x-cp1257'          => Encoding::Windows1257,

			'cp1258'            => Encoding::Windows1258,
			'windows-1258'      => Encoding::Windows1258,
			'x-cp1258'          => Encoding::Windows1258,
			'x-mac-cyrillic'    => Encoding::XMacCyrillic,
			'x-mac-ukrainian'   => Encoding::XMacCyrillic,
			'chinese'           => Encoding::Gbk,
			'csgb2312'          => Encoding::Gbk,
			'csiso58gb231280'   => Encoding::Gbk,
			'gb2312'            => Encoding::Gbk,
			'gb_2312'           => Encoding::Gbk,
			'gb_2312-80'        => Encoding::Gbk,
			'gbk'               => Encoding::Gbk,
			'iso-ir-58'         => Encoding::Gbk,
			'x-gbk'             => Encoding::Gbk,
			'gb18030'           => Encoding::Gb18030,

			'big5'              => Encoding::Big5,
			'big5-hkscs'        => Encoding::Big5,
			'cn-big5'           => Encoding::Big5,
			'csbig5'            => Encoding::Big5,
			'x-x-big5'          => Encoding::Big5,

			'cseucpkdfmtjapanese' => Encoding::EucJp,
			'euc-jp'              => Encoding::EucJp,
			'x-euc-jp'            => Encoding::EucJp,

			'csiso2022jp'       => Encoding::Iso2022Jp,
			'iso-2022-jp'       => Encoding::Iso2022Jp,

			'csshiftjis'        => Encoding::ShiftJis,
			'ms932'             => Encoding::ShiftJis,
			'ms_kanji'          => Encoding::ShiftJis,
			'shift-jis'         => Encoding::ShiftJis,
			'shift_jis'         => Encoding::ShiftJis,
			'sjis'              => Encoding::ShiftJis,
			'windows-31j'       => Encoding::ShiftJis,
			'x-sjis'            => Encoding::ShiftJis,

			'cseuckr'           => Encoding::EucKr,
			'csksc56011987'     => Encoding::EucKr,
			'euc-kr'            => Encoding::EucKr,
			'iso-ir-149'        => Encoding::EucKr,
			'korean'            => Encoding::EucKr,
			'ks_c_5601-1987'    => Encoding::EucKr,
			'ks_c_5601-1989'    => Encoding::EucKr,
			'ksc5601'           => Encoding::EucKr,
			'ksc_5601'          => Encoding::EucKr,
			'windows-949'       => Encoding::EucKr,

			'csiso2022kr'       => Encoding::Replacement,
			'hz-gb-2312'        => Encoding::Replacement,
			'iso-2022-cn'       => Encoding::Replacement,
			'iso-2022-cn-ext'   => Encoding::Replacement,
			'iso-2022-kr'       => Encoding::Replacement,
			'replacement'       => Encoding::Replacement,

			'unicodefffe'       => Encoding::Utf16Be,
			'utf-16be'          => Encoding::Utf16Be,

			'csunicode'         => Encoding::Utf16Le,
			'iso-10646-ucs-2'   => Encoding::Utf16Le,
			'ucs-2'             => Encoding::Utf16Le,
			'unicode'           => Encoding::Utf16Le,
			'unicodefeff'       => Encoding::Utf16Le,
			'utf-16'            => Encoding::Utf16Le,
			'utf-16le'          => Encoding::Utf16Le,

			'x-user-defined'    => Encoding::XUserDefined,
		];

		if ( \array_key_exists( $label, $map ) ) {
			return $map[$label];
		} else {
			return null;
		}
	}
}
