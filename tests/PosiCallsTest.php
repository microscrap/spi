<?php

declare(strict_types=1);

use Microscrap\Bindings\SPI\DataObjects\SPIDevice;
use Microscrap\Bindings\SPI\DataObjects\SPITransfer;

it('refuses a device that does not take the spidev mode ioctls', function () {
    expect(spi_open('/dev/null'))->toBeNull()
        ->and(spi_open('/dev/no-such-spidev'))->toBeNull();
});

it('reads and writes raw bytes through the device fd', function () {
    [$read, $write] = fifoPair();

    expect(spi_write(new SPIDevice($write, '/dev/spidev0.0', 0, 500_000, 8), "\x9F\x00"))->toBe(2)
        ->and(spi_read(new SPIDevice($read, '/dev/spidev0.0', 0, 500_000, 8), 8))->toBe("\x9F\x00");
});

it('reports a transfer the fd refuses as false with errno', function () {
    $fd = track(posix_open('/dev/null', O_RDWR));
    $dev = new SPIDevice($fd, '/dev/null', 0, 500_000, 8);

    expect(spi_transfer($dev, new SPITransfer("\x9F", 4), new SPITransfer('', 0)))->toBeFalse()
        ->and(posi_errno())->toBe(PHP_OS_FAMILY === 'Darwin' ? ENODEV : ENOTTY);
});

it('closes the device fd', function () {
    [$read] = fifoPair();

    expect(spi_close(new SPIDevice($read, '/dev/spidev0.0', 0, 500_000, 8)))->toBe(0)
        ->and(fcntl($read, F_GETFD, null, $flags))->toBe(-1);
});
