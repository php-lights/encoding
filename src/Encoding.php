<?php

namespace Neoncitylights\Encoding;

enum Encoding {
	// Main encoding
	case Utf8;

	// Legacy single-byte encodings
	case Ibm866;
	case Iso8559_2;
	case Iso8859_3;
	case Iso8859_4;
	case Iso8859_5;
	case Iso8859_6;
	case Iso8859_7;
	case Iso8859_I;
	case Iso8859_10;
	case Iso8859_13;
	case Iso8859_14;
	case Iso8859_15;
	case Iso8859_16;
	case Koi8R;
	case Koi8U;
	case Macintosh;
	case Windows874;
	case Windows1250;
	case Windows1251;
	case Windows1252;
	case Windows1253;
	case Windows1254;
	case Windows1255;
	case Windows1256;
	case Windows1257;
	case Windows1258;
	case XMacCyrillic;

	// Legacy multi-byte Chinese (simplified) encodings
	case Gbk;
	case Gb18030;
	case Big5;

	// Legacy multi-byte Chinese (traditional) encodings
	case EucJp;
	case Iso2022Jp;
	case ShiftJis;

	// Legacy multi-byte Korean encodings
	case EucKr;

	// Legacy miscellaneous encodings
	case Replacement;
	case Utf16Be;
	case Utf16Le;
	case XUserDefined;
}
