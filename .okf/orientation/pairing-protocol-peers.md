---
type: Orientation
title: Pair with protocol peers
description: "posix below; uart / i2c / gpio peers; ftdi/mpsse alternate SPI path; gpio-framework above."
resource: .
tags: [orientation, spi, posix, uart, i2c, gpio, ftdi, composition]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Bindings role; posix + ext-posi dependencies
  - id: agents
    resource: AGENTS.md
    title: Bindings-only role; no Chassis
  - id: composer
    resource: composer.json
    title: require posix / ext-posi; suggest gpio-framework
---

# Composition boundary

`microscrap/spi` is **bindings only** — Linux `spidev` helpers, enums, and data objects. It does not register Chassis providers or implement higher GPIO orchestration.[^readme][^agents]

| Concern | Package |
|---------|---------|
| POSIX FD / syscall helpers | `microscrap/posix` `^0.7` (**below** — required) |
| Native memory / ioctl primitives | `ext-posi` `^0.7` (**below** — required) |
| SPI / spidev (this package) | `microscrap/spi` |
| UART | `microscrap/uart` `^0.7` (peer) |
| I2C | `microscrap/i2c` `^0.7` (peer) |
| GPIO | `microscrap/gpio` `^0.7` (peer) |
| USB MPSSE / FTDI SPI path | `microscrap/ftdi` / `microscrap/mpsse` (**beside** — alternate SPI transport, not a spidev child) |
| Higher GPIO orchestration | `scrapyard-io/gpio-framework` `^0.7` (**above**)[^composer] |

# Typical flow

1. Depend on this package (pulls **ext-posi** + **microscrap/posix** `^0.7.0`).
2. Open `/dev/spidev*` via `spi_open` and transfer with `spi_transfer` / half-duplex helpers.
3. Application / `gpio-framework` composes peers — do not invent framework providers inside this package.[^agents]

# Caveats

- Full-duplex needs `spi_transfer` — see [Half-duplex vs full-duplex](../traps/half-duplex-vs-full-duplex.md).
- Transfer path depends on native `spi_transfer(int $fd, …)` or `posi_mem_*` — see [Native `spi_transfer` vs `posi_mem`](../traps/spi-transfer-native-vs-posi-mem.md).

# Related

* [Package (0.7)](package.md)
* [Helpers → Device → posix](../architecture/helpers-device-posix.md)

[^readme]: Bindings role; posix + ext-posi dependencies
[^agents]: Bindings-only role; no Chassis
[^composer]: require posix / ext-posi; suggest gpio-framework
