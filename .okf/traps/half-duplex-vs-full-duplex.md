---
type: Trap
title: Half-duplex vs full-duplex
description: "spi_read / spi_write are half-duplex via posix_read/write; use spi_transfer for byte-correlated full-duplex MOSI/MISO."
resource: src/Device.php
tags: [trap, spi, duplex, transfer, read, write]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Half-duplex vs full-duplex notes
  - id: device
    resource: src/Device.php
    title: spiRead/spiWrite vs spiTransfer
  - id: helpers
    resource: src/Helpers/spi-device.php
    title: Global helper surface
---

# Symptom

Register reads or JEDEC-style transactions look wrong: MISO bytes do not line up with what was clocked on MOSI, or a “read” only clocks dummy traffic without a proper full-duplex message.

# Cause

`spi_read` / `spi_write` go through `posix_read` / `posix_write` on the spidev FD — the kernel’s **half-duplex** path (dummy bytes out on read; MISO ignored on write).[^device][^readme]

True **full-duplex** (byte-for-byte correspondence between TX and RX) requires `spi_transfer` with one or more `SPITransfer` segments (`SPI_IOC_MESSAGE`).[^readme]

# Mitigation

- Use `spi_transfer($dev, new SPITransfer(...), …)` for sensor register reads, JEDEC ID, and any protocol where RX must match TX clocks.
- Reserve `spi_read` / `spi_write` for simple half-duplex bulk I/O where the kernel dummy / ignore semantics are acceptable.
- See transfer-path requirements in [Native `spi_transfer` vs `posi_mem`](spi-transfer-native-vs-posi-mem.md).

# Related

* [Helpers → Device → posix](../architecture/helpers-device-posix.md)
* [Package (0.7)](../orientation/package.md)

[^readme]: Half-duplex vs full-duplex notes
[^device]: spiRead/spiWrite vs spiTransfer
[^helpers]: Global helper surface
