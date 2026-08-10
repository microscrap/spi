---
type: Trap
title: "Native spi_transfer vs posi_mem"
description: "Device reflects on spi_transfer for an int-first native path; otherwise needs posi_mem_*; wrong signature would recurse into the procedural helper."
resource: src/Device.php
tags: [trap, spi, transfer, posi, reflection]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: device
    resource: src/Device.php
    title: spiTransfer native vs posi_mem paths
  - id: readme
    resource: README.md
    title: Transfer path documentation
  - id: helpers
    resource: src/Helpers/spi-device.php
    title: Procedural spi_transfer takes SPIDevice
  - id: agents
    resource: AGENTS.md
    title: Transfer-path OKF map entry
---

# Symptom

`spi_transfer(...)` returns `false`, recurses until a stack overflow, or never exercises the expected native / memory path.

# Cause

`Device::spiTransfer` has two supported paths:[^device][^readme]

1. **Native fast path** — if `function_exists('spi_transfer')` and Reflection shows the first parameter type is **`int`**, call `\spi_transfer($dev->fd, $transfers)`. That signature belongs to a low-level extension, not this package’s helper.
2. **`ext-posi` fallback** — if `posi_mem_alloc` exists, pack each `spi_ioc_transfer` and issue `SPI_IOC_MESSAGE(1)` per segment via `ioctl`.
3. Otherwise return `false`.

This package’s procedural helper is `spi_transfer(SPIDevice $dev, …)`. Calling that from `Device` without the int-type check would **recurse** back into `Device::spiTransfer`.[^helpers][^device]

# Mitigation

- Ensure **ext-posi** is loaded so `posi_mem_*` is available when no native int-first `spi_transfer` exists.[^readme]
- When adding or diagnosing a native extension helper, keep the first argument as `int $fd` — that is what the Reflection gate looks for.[^device]
- Do not “fix” transfers by having `Device` call the global helper without the type check.

# Related

* [Helpers → Device → posix](../architecture/helpers-device-posix.md)
* [Half-duplex vs full-duplex](half-duplex-vs-full-duplex.md)

[^device]: spiTransfer native vs posi_mem paths
[^readme]: Transfer path documentation
[^helpers]: Procedural spi_transfer takes SPIDevice
[^agents]: Transfer-path OKF map entry
