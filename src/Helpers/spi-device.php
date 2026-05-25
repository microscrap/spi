<?php

use Microscrap\Bindings\SPI\DataObjects\SPIDevice;
use Microscrap\Bindings\SPI\DataObjects\SPITransfer;
use Microscrap\Bindings\SPI\Device;

if (! function_exists('spi_open')) {
    function spi_open(string $path, int $mode = 0, int $speed = 500_000, int $bitsPerWord = 8): ?SPIDevice
    {
        return Device::spiOpen($path, $mode, $speed, $bitsPerWord);
    }
}

if (! function_exists('spi_close')) {
    function spi_close(SPIDevice $dev): int
    {
        return Device::spiClose($dev);
    }
}

if (! function_exists('spi_read')) {
    function spi_read(SPIDevice $dev, int $len): string|false
    {
        return Device::spiRead($dev, $len);
    }
}

if (! function_exists('spi_write')) {
    function spi_write(SPIDevice $dev, string $data): int
    {
        return Device::spiWrite($dev, $data);
    }
}

if (! function_exists('spi_get_mode')) {
    function spi_get_mode(SPIDevice $dev): int
    {
        return Device::spiGetMode($dev);
    }
}

if (! function_exists('spi_set_mode')) {
    function spi_set_mode(SPIDevice $dev, int $mode): int
    {
        return Device::spiSetMode($dev, $mode);
    }
}

if (! function_exists('spi_get_speed')) {
    function spi_get_speed(SPIDevice $dev): int
    {
        return Device::spiGetSpeed($dev);
    }
}

if (! function_exists('spi_set_speed')) {
    function spi_set_speed(SPIDevice $dev, int $hz): int
    {
        return Device::spiSetSpeed($dev, $hz);
    }
}

if (! function_exists('spi_get_bits_per_word')) {
    function spi_get_bits_per_word(SPIDevice $dev): int
    {
        return Device::spiGetBitsPerWord($dev);
    }
}

if (! function_exists('spi_set_bits_per_word')) {
    function spi_set_bits_per_word(SPIDevice $dev, int $bits): int
    {
        return Device::spiSetBitsPerWord($dev, $bits);
    }
}

if (! function_exists('spi_get_lsb_first')) {
    function spi_get_lsb_first(SPIDevice $dev): bool
    {
        return Device::spiGetLsbFirst($dev);
    }
}

if (! function_exists('spi_set_lsb_first')) {
    function spi_set_lsb_first(SPIDevice $dev, bool $lsb): int
    {
        return Device::spiSetLsbFirst($dev, $lsb);
    }
}

if (! function_exists('spi_transfer')) {
    function spi_transfer(SPIDevice $dev, SPITransfer ...$transfers): string|false
    {
        return Device::spiTransfer($dev, ...$transfers);
    }
}
