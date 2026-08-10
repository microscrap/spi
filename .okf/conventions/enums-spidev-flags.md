---
type: Convention
title: Enums for spidev flags
description: "SPIMode and SPIOpCode are int-backed with FULLY UPPERCASE cases from linux spidev.h."
resource: src/Enums/
tags: [convention, enums, spi, spidev, linux]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Enum tables and FULLY UPPERCASE rule
  - id: spi-mode
    resource: src/Enums/SPIMode.php
    title: SPIMode enum
  - id: spi-opcode
    resource: src/Enums/SPIOpCode.php
    title: SPIOpCode enum
  - id: agents
    resource: AGENTS.md
    title: Enum case naming rule
---

# Why enums live here

Linux `<linux/spi/spidev.h>` exposes mode bitmasks and `SPI_IOC_*` ioctl request numbers as `#define`s. This package ships typed int-backed enums so callers avoid raw magic numbers for the usual cases.[^readme]

# Rules

- Use **int-backed** enums under `Microscrap\Bindings\SPI\Enums\`.[^spi-mode][^spi-opcode]
- Case names are **FULLY UPPERCASE** (e.g. `SPIMode::CPHA`, `SPIOpCode::SPI_IOC_WR_MODE32`).[^agents][^readme]
- No class-level constants in `src/` — prefer enums.[^agents]
- Pass `->value` (or a raw `int`) into helpers; helpers take integers / data objects, not enum objects for mode/speed args.[^readme]

# Enum inventory (0.7.0)

| Enum | Purpose | Cases (summary) |
|------|---------|-----------------|
| `SPIMode` | `SPI_IOC_WR_MODE32` bitmask | `MODE_0`, `CPHA`, `CPOL`, `CS_HIGH`, `LSB_FIRST`, `THREE_WIRE`, `LOOP`, `NO_CS`, `READY`, dual/quad/octal lane bits, `CS_WORD`, `THREE_WIRE_HIZ`[^spi-mode] |
| `SPIOpCode` | `SPI_IOC_*` request numbers | `SPI_IOC_RD/WR_MODE`, `…_LSB_FIRST`, `…_BITS_PER_WORD`, `…_MAX_SPEED_HZ`, `…_MODE32`, `MESSAGE_1`; plus `messageN(int $n)` for batched transfers[^spi-opcode] |

Classical SPI modes 0–3 are `CPOL`/`CPHA` combinations (`MODE_0` = 0; mode 3 = `CPOL \| CPHA`).[^readme]

# Related

* [1:1 extension wrap](one-to-one-extension-wrap.md)
* [Helpers → Device → posix](../architecture/helpers-device-posix.md)

[^readme]: Enum tables and FULLY UPPERCASE rule
[^spi-mode]: SPIMode enum
[^spi-opcode]: SPIOpCode enum
[^agents]: Enum case naming rule
