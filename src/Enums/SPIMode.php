<?php

namespace Microscrap\Bindings\SPI\Enums;

enum SPIMode: int
{
    case MODE_0 = 0x00;
    case CPHA = 0x01;
    case CPOL = 0x02;
    case CS_HIGH = 0x04;
    case LSB_FIRST = 0x08;
    case THREE_WIRE = 0x10;
    case LOOP = 0x20;
    case NO_CS = 0x40;
    case READY = 0x80;
    case TX_DUAL = 0x100;
    case RX_DUAL = 0x200;
    case TX_QUAD = 0x400;
    case RX_QUAD = 0x800;
    case CS_WORD = 0x1000;
    case TX_OCTAL = 0x2000;
    case RX_OCTAL = 0x4000;
    case THREE_WIRE_HIZ = 0x8000;
}
