<?php

declare(strict_types=1);

use Microscrap\Bindings\SPI\DataObjects\SPITransfer;
use Microscrap\Bindings\SPI\Device;

it('points tx_buf at a transfer\'s address, discards its rx and makes no buffers for it', function () {
    [$structs, $buffers] = Device::spiTransferStructs(new SPITransfer('', 960, speedHz: 10_000_000, txAddress: 0x7F00_1000));

    expect(strlen($structs))->toBe(32)
        ->and(unpack('Qtx/Qrx/Vlen/Vspeed', $structs))->toBe(['tx' => 0x7F00_1000, 'rx' => 0, 'len' => 960, 'speed' => 10_000_000])
        ->and($buffers)->toBe([]);
});

it('copies a string transfer into native buffers, as before', function () {
    [$structs, $buffers] = Device::spiTransferStructs(new SPITransfer("\x9F\x00", 2), new SPITransfer('', 4, txAddress: 0x2000));
    $first = unpack('Qtx/Qrx/Vlen', $structs);

    expect(strlen($structs))->toBe(64)
        ->and($buffers)->toHaveCount(1)
        ->and([$first['tx'], $first['rx'], $first['len']])->toBe($buffers[0])
        ->and(posi_mem_read($buffers[0][0], 2))->toBe("\x9F\x00");

    posi_mem_free($buffers[0][0]);
    posi_mem_free($buffers[0][1]);
});
