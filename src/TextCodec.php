<?php

namespace Neoncitylights\Encoding;

interface TextCodec {
	public function handler( Queue $queue, int $byteOrCodepoint ): HandleStateValue;
}
