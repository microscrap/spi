<?php

declare(strict_types=1);

if (! extension_loaded('posi')) {
    throw new RuntimeException('ext-posi is not loaded');
}

/** Descriptors a test opened through track(); closed after each test. */
$GLOBALS['openFds'] = [];

function track(int $fd): int
{
    if ($fd < 0) {
        throw new RuntimeException('open failed, errno ' . posi_errno());
    }
    $GLOBALS['openFds'][] = $fd;

    return $fd;
}

/** A FIFO opened at both ends without blocking: [read fd, write fd]. Stands in for a device fd. */
function fifoPair(): array
{
    $path = sys_get_temp_dir() . '/microscrap-fifo-' . getmypid() . '-' . bin2hex(random_bytes(4));
    posix_mkfifo($path, 0600);
    $read = track(posix_open($path, O_RDONLY | O_NONBLOCK));
    $write = track(posix_open($path, O_WRONLY | O_NONBLOCK));
    unlink($path);

    return [$read, $write];
}

pest()->afterEach(function (): void {
    foreach ($GLOBALS['openFds'] as $fd) {
        posix_close($fd);
    }
    $GLOBALS['openFds'] = [];
})->in(__DIR__);
