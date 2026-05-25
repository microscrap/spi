<?php

namespace Microscrap\Bindings\SPI;

use Microscrap\Bindings\POSIX\Enums\FileControlFlag;
use Microscrap\Bindings\SPI\DataObjects\SPIDevice;
use Microscrap\Bindings\SPI\DataObjects\SPITransfer;
use Microscrap\Bindings\SPI\Enums\SPIOpCode;

class Device
{
    public static function spiOpen(
        string $path,
        int $mode = 0,
        int $speed = 500_000,
        int $bitsPerWord = 8
    ): ?SPIDevice {
        $fd = posix_open($path, FileControlFlag::O_RDWR->value);
        if ($fd < 0) {
            return null;
        }

        if (self::spiIoctlWriteU32($fd, SPIOpCode::SPI_IOC_WR_MODE32, $mode) !== 0) {
            posix_close($fd);
            return null;
        }

        if (self::spiIoctlWriteU32($fd, SPIOpCode::SPI_IOC_WR_MAX_SPEED_HZ, $speed) !== 0) {
            posix_close($fd);
            return null;
        }

        if (self::spiIoctlWriteByte($fd, SPIOpCode::SPI_IOC_WR_BITS_PER_WORD, $bitsPerWord) !== 0) {
            posix_close($fd);
            return null;
        }

        return new SPIDevice($fd, $path, $mode, $speed, $bitsPerWord);
    }

    public static function spiClose(SPIDevice $dev): int
    {
        return posix_close($dev->fd);
    }

    public static function spiRead(SPIDevice $dev, int $len): string|false
    {
        return posix_read($dev->fd, $len);
    }

    public static function spiWrite(SPIDevice $dev, string $data): int
    {
        return posix_write($dev->fd, $data, strlen($data));
    }

    public static function spiGetMode(SPIDevice $dev): int
    {
        return self::spiIoctlReadU32($dev->fd, SPIOpCode::SPI_IOC_RD_MODE32);
    }

    public static function spiGetSpeed(SPIDevice $dev): int
    {
        return self::spiIoctlReadU32($dev->fd, SPIOpCode::SPI_IOC_RD_MAX_SPEED_HZ);
    }

    public static function spiGetBitsPerWord(SPIDevice $dev): int
    {
        return self::spiIoctlReadByte($dev->fd, SPIOpCode::SPI_IOC_RD_BITS_PER_WORD);
    }

    public static function spiGetLsbFirst(SPIDevice $dev): bool
    {
        return self::spiIoctlReadByte($dev->fd, SPIOpCode::SPI_IOC_RD_LSB_FIRST) !== 0;
    }

    public static function spiSetMode(SPIDevice $dev, int $mode): int
    {
        return self::spiIoctlWriteU32($dev->fd, SPIOpCode::SPI_IOC_WR_MODE32, $mode);
    }

    public static function spiSetSpeed(SPIDevice $dev, int $hz): int
    {
        return self::spiIoctlWriteU32($dev->fd, SPIOpCode::SPI_IOC_WR_MAX_SPEED_HZ, $hz);
    }

    public static function spiSetBitsPerWord(SPIDevice $dev, int $bits): int
    {
        return self::spiIoctlWriteByte($dev->fd, SPIOpCode::SPI_IOC_WR_BITS_PER_WORD, $bits);
    }

    public static function spiSetLsbFirst(SPIDevice $dev, bool $lsb): int
    {
        return self::spiIoctlWriteByte($dev->fd, SPIOpCode::SPI_IOC_WR_LSB_FIRST, (int) $lsb);
    }

    public static function spiTransfer(SPIDevice $dev, SPITransfer ...$transfers): string|false
    {
        if ($transfers === []) {
            return '';
        }

        // Prefer native spi_transfer(int $fd, ...) when a low-level extension
        // provides it. Avoid calling the procedural helper of the same name
        // (first arg SPIDevice), which would recurse back into this method.
        if (function_exists('spi_transfer')) {
            $reflection = new \ReflectionFunction('spi_transfer');
            $firstParam = $reflection->getParameters()[0] ?? null;
            $firstType  = $firstParam?->getType();

            if ($firstType instanceof \ReflectionNamedType && $firstType->getName() === 'int') {
                /** @var string|false */
                return \spi_transfer($dev->fd, $transfers);
            }
        }

        // Posi\Memory path: packs a spi_ioc_transfer struct per segment and
        // issues SPI_IOC_MESSAGE(1) via ioctl. Works with ext-posi only —
        // no legacy SPI extension required.
        if (function_exists('posi_mem_alloc')) {
            return self::spiTransferViaPosi($dev, ...$transfers);
        }

        return false;
    }

    private static function spiTransferViaPosi(SPIDevice $dev, SPITransfer ...$transfers): string|false
    {
        $chunks = [];

        foreach ($transfers as $transfer) {
            $len = $transfer->len;
            $tx  = substr(str_pad($transfer->tx, $len, "\0"), 0, $len);

            $txPtr = posi_mem_alloc($len);
            $rxPtr = posi_mem_alloc($len);

            posi_mem_write($txPtr, $tx);

            // spi_ioc_transfer layout (32 bytes, 64-bit Linux):
            //   tx_buf(Q) rx_buf(Q) len(V) speed_hz(V) delay_usecs(v)
            //   bits_per_word(C) cs_change(C) tx_nbits(C) rx_nbits(C)
            //   word_delay_usecs(C) pad(C)
            $struct = pack(
                'QQVVvCCCCCC',
                $txPtr,
                $rxPtr,
                $len,
                $transfer->speedHz,
                $transfer->delayUsecs,
                $transfer->bitsPerWord,
                (int) $transfer->csChange,
                $transfer->txNbits,
                $transfer->rxNbits,
                $transfer->wordDelayUsecs,
                0
            );

            $unused = null;
            $ret = ioctl($dev->fd, SPIOpCode::messageN(1), ['data' => $struct], $unused);

            if ($ret < 0) {
                posi_mem_free($txPtr);
                posi_mem_free($rxPtr);
                return false;
            }

            $chunks[] = posi_mem_read($rxPtr, $len);

            posi_mem_free($txPtr);
            posi_mem_free($rxPtr);
        }

        return implode('', $chunks);
    }

    private static function spiIoctlReadByte(int $fd, SPIOpCode $op): int
    {
        $buffer = str_repeat("\0", 1);
        $ret = ioctl($fd, $op->value, ['data' => $buffer], $buffer);
        if ($ret !== 0 || ! is_string($buffer) || strlen($buffer) < 1) {
            return -1;
        }

        return unpack('C', $buffer)[1];
    }

    private static function spiIoctlWriteByte(int $fd, SPIOpCode $op, int $val): int
    {
        $buffer = pack('C', $val & 0xFF);
        return ioctl($fd, $op->value, ['data' => $buffer], $buffer);
    }

    private static function spiIoctlReadU32(int $fd, SPIOpCode $op): int
    {
        $buffer = str_repeat("\0", 4);
        $ret = ioctl($fd, $op->value, ['data' => $buffer], $buffer);
        if ($ret !== 0 || ! is_string($buffer) || strlen($buffer) < 4) {
            return -1;
        }

        return unpack('V', $buffer)[1];
    }

    private static function spiIoctlWriteU32(int $fd, SPIOpCode $op, int $val): int
    {
        $buffer = pack('V', $val);
        return ioctl($fd, $op->value, ['data' => $buffer], $buffer);
    }
}
