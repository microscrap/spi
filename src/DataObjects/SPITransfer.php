<?php

namespace Microscrap\Bindings\SPI\DataObjects;

/**
 * One spi_ioc_transfer. A non-zero txAddress is where tx_buf points: $len bytes are read there by the kernel
 * (trusted), $tx is ignored, nothing is copied and the rx is discarded.
 */
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
        public int $txAddress = 0,
    ) {}
}
