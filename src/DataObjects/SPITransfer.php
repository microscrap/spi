<?php

namespace Microscrap\Bindings\SPI\DataObjects;

final readonly class SPITransfer
{
    public function __construct(
        public string $tx,
        public int $len,
        public int $speedHz = 0,
        public int $delayUsecs = 0,
        public int $bitsPerWord = 0,
        public bool $csChange = false,
        public int $txNbits = 0,
        public int $rxNbits = 0,
        public int $wordDelayUsecs = 0,
    ) {}
}
