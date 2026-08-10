---
type: Convention
title: "1:1 extension wrap"
description: "Helpers call Device only; Device uses posix/posi; keep coverage aligned with spi-device.php."
resource: src/
tags: [convention, bindings, spi, posix, posi]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: agents
    resource: AGENTS.md
    title: Agent wrap rules
  - id: readme
    resource: README.md
    title: Package README wrap description
  - id: helpers
    resource: src/Helpers/spi-device.php
    title: Helper delegation to Device
  - id: device
    resource: src/Device.php
    title: Device uses posix helpers and posi_mem_*
---

# Rule

Match peer protocol bindings (`microscrap/uart`, `microscrap/i2c`, …) — thin wrap over spidev via posix / posi:[^agents][^readme]

1. Global helpers use the names callers expect (`spi_open`, `spi_transfer`, …).
2. Helpers call **`Microscrap\Bindings\SPI\Device` only** — never invent parallel APIs.[^helpers][^agents]
3. `Device` uses posix helpers (`posix_open`, `posix_read`, `posix_write`, `posix_close`, `ioctl`, `posi_mem_*`) — do not reimplement FD I/O or ioctl packing elsewhere.[^device][^agents]
4. Keep 1:1 coverage with helpers already in `src/Helpers/spi-device.php`; document drift in README / ecosystem docs.[^agents]
5. Kernel `#define` flag / ioctl integers live in backed enums — see [Enums for spidev flags](enums-spidev-flags.md).
6. No ServiceProvider, Chassis/Core coupling, or Fabricate remaps in this package.[^agents]
7. Prefer `is_null($var)` over `$var === null`; no class-level constants.[^agents]

# Architecture link

Full call-stack diagram: [Helpers → Device → posix](../architecture/helpers-device-posix.md).

[^agents]: Agent wrap rules
[^readme]: Package README wrap description
[^helpers]: Helper delegation to Device
[^device]: Device uses posix helpers and posi_mem_*
