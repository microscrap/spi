---
type: Orientation
title: Pair with protocol peers
description: "ext-posi below; uart / i2c / gpio peers; ftdi/mpsse alternate SPI path; scrapyard-io/framework above."
resource: .
tags: [orientation, spi, posi, uart, i2c, gpio, ftdi, composition]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Bindings role; ext-posi dependency
  - id: agents
    resource: AGENTS.md
    title: Bindings-only role; no Chassis
  - id: composer
    resource: composer.json
    title: require ext-posi; suggest scrapyard-io/framework
---

# Composition boundary

`microscrap/spi` is **bindings only** — Linux `spidev` helpers, enums, and data objects. It does not register Chassis providers or implement higher GPIO orchestration.[^readme][^agents]

| Concern | Package |
|---------|---------|
| POSIX FD / ioctl / memory primitives | `ext-posi` `^0.10.0` (**below** — required; registers `posix_*`, `ioctl`, `posi_mem_*`) |
| SPI / spidev (this package) | `microscrap/spi` |
| UART | `microscrap/uart` `^0.10` (peer) |
| I2C | `microscrap/i2c` `^0.10` (peer) |
| GPIO | `microscrap/gpio` `^0.10` (peer) |
| USB MPSSE / FTDI SPI path | `microscrap/ftdi` / `microscrap/mpsse` (**beside** — alternate SPI transport, not a spidev child) |
| Higher GPIO orchestration | `scrapyard-io/framework` `^0.10.0` (**above**)[^composer] |

# Typical flow

1. Depend on this package (pulls **ext-posi** `^0.10.0`).
2. Open `/dev/spidev*` via `spi_open` and transfer with `spi_transfer` / half-duplex helpers.
3. Application / `scrapyard-io/framework` composes peers — do not invent framework providers inside this package.[^agents]

# Caveats

- Full-duplex needs `spi_transfer` — see [Half-duplex vs full-duplex](../traps/half-duplex-vs-full-duplex.md).
- Transfer path is native `spi_transfer(int $fd, …)` when loaded, otherwise `posi_mem_*` — see [Native `spi_transfer` vs `posi_mem`](../traps/spi-transfer-native-vs-posi-mem.md).

# Related

* [Package (0.10)](package.md)
* [Helpers → Device → posix](../architecture/helpers-device-posix.md)

[^readme]: Bindings role; ext-posi dependency
[^agents]: Bindings-only role; no Chassis
[^composer]: require ext-posi; suggest scrapyard-io/framework
