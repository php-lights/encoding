<?php

namespace Neoncitylights\Encoding\Iso2022Jp;

use Exception;
use Neoncitylights\Encoding\HandleStateResult;
use Neoncitylights\Encoding\Queue;
use Neoncitylights\Encoding\TextCodec;

class Iso2022JpEncoder implements TextCodec {
	public EncoderState $encoderState;

	public function __construct( EncoderState $encoderState ) {
		$this->encoderState = $this->encoderState;
	}

	public function handler( Queue $queue, int $codePoint ): HandleStateResult {
		if ( $queue->endOfQueue() === $codePoint ) {
			if ( $this->encoderState !== EncoderState::Ascii ) {
				$this->encoderState = EncoderState::Ascii;
				return HandleStateResult::asOneOrMoreItems( [ 0x1b, 0x28, 0x42 ] );
			} else {
				return HandleStateResult::asFinished();
			}
		}

		if (
			\in_array( $this->encoderState, [ EncoderState::Ascii, EncoderState::Roman ] )
			&& \in_array( $codePoint, [ 0x000E, 0x000F, 0x000B ] )
		) {
			return HandleStateResult::asOneOrMoreItems( [ 0xFFFD ] );
		}

		// todo: check codepoint is ASCII
		if ( $this->encoderState === EncoderState::Ascii && $codePoint ) {
			return HandleStateResult::asOneOrMoreItems( [ $codePoint ] );
		}

		// todo: check codepoint is ASCII
		if (
			$this->encoderState === EncoderState::Ascii
			&& (
				$codePoint
				&& !\in_array( $codePoint, [ 0x005c, 0x007e ] )
				&& !\in_array( $codePoint, [ 0x00a5, 0x203e ] )
			)
		) {
			// todo
			throw new Exception();
		}

		throw new Exception();
	}
}
