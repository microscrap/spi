<?php

namespace Microscrap\Bindings\SPI\Enums;

enum SPIOpCode: int
{
    case SPI_IOC_RD_MODE = 0x80016B01;
    case SPI_IOC_WR_MODE = 0x40016B01;
    case SPI_IOC_RD_LSB_FIRST = 0x80016B02;
    case SPI_IOC_WR_LSB_FIRST = 0x40016B02;
    case SPI_IOC_RD_BITS_PER_WORD = 0x80016B03;
    case SPI_IOC_WR_BITS_PER_WORD = 0x40016B03;
    case SPI_IOC_RD_MAX_SPEED_HZ = 0x80046B04;
    case SPI_IOC_WR_MAX_SPEED_HZ = 0x40046B04;
    case SPI_IOC_RD_MODE32 = 0x80046B05;
    case SPI_IOC_WR_MODE32 = 0x40046B05;
    case MESSAGE_1 = 0x40206B00;

    public static function messageN(int $n): int
    {
        return 0x40000000 | (($n * 32) << 16) | 0x6B00;
    }
}
