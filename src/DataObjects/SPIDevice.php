<?php

namespace Microscrap\Bindings\SPI\DataObjects;

final readonly class SPIDevice
{
    public function __construct(
        public int $fd,
        public string $path,
        public int $mode,
        public int $speed,
        public int $bitsPerWord,
    ) {}
}
