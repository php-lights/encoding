<?php

namespace Neoncitylights\Encoding\Iso2022Jp;

class Iso2022JpDecoder {
	public DecoderState $decoderState;
	public DecoderState $decoderOutputState;
	public int $lead;
	public bool $output;
}
