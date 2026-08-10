---
type: Architecture
title: "Helpers → Device → posix"
description: "Global spi_* helpers call Device; Device uses posix_open/read/write/close/ioctl and posi_mem_*; native spi_transfer(int) fast path optional."
resource: src/Device.php
tags: [architecture, bindings, spi, helpers, posix, posi]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: helpers
    resource: src/Helpers/spi-device.php
    title: Helper file delegating to Device
  - id: device
    resource: src/Device.php
    title: Device facade; posix and transfer paths
  - id: composer
    resource: composer.json
    title: Autoload files list; require posix + ext-posi
  - id: readme
    resource: README.md
    title: Helper API and transfer path notes
  - id: agents
    resource: AGENTS.md
    title: Helpers → Device → posix only
---

# Call stack

```
app / peers / tests
    │
    └─ spi_open / spi_read / spi_write / spi_transfer / …
            └─► Device::spi*                    # this package
                    ├─ posix_open / posix_read / posix_write / posix_close / ioctl
                    │       └─► microscrap/posix → Posi\System (ext-posi)
                    │
                    └─ spi_transfer paths
                            ├─ native spi_transfer(int $fd, …)   # optional low-level ext
                            └─ posi_mem_alloc / write / read / free + ioctl(SPI_IOC_MESSAGE)
                                    └─► Posi\Memory (ext-posi)
```

Rules:[^agents][^readme][^helpers][^device]

1. Global helpers call `Microscrap\Bindings\SPI\Device` only.
2. `Device` uses posix helpers (`posix_open`, `posix_read`, `posix_write`, `posix_close`, `ioctl`, `posi_mem_*`) — do not invent parallel APIs.
3. Keep 1:1 coverage with helpers already in `src/Helpers/spi-device.php`.

# Autoload

Composer `autoload.files` registers:[^composer]

- `src/Helpers/spi-device.php`

Each function is wrapped in `if (! function_exists(...))` so a prior definition wins.[^helpers]

# Helper groups (0.7 surface)

| Group | Examples | Device / lower target |
|-------|----------|------------------------|
| Lifecycle | `spi_open`, `spi_close` | `posix_open` / `posix_close` + mode/speed/bits ioctl |
| Half-duplex I/O | `spi_read`, `spi_write` | `posix_read` / `posix_write` |
| Config | `spi_get_*` / `spi_set_*` | `ioctl` via `SPIOpCode` |
| Full-duplex | `spi_transfer` | Native `spi_transfer(int)` **or** `posi_mem_*` + `SPI_IOC_MESSAGE` |

# `spi_transfer` paths

`Device::spiTransfer` prefers a **native** `spi_transfer(int $fd, …)` when Reflection shows the first parameter is `int` (avoids recursing into this package’s procedural helper, which takes `SPIDevice`). Otherwise it uses the **ext-posi** memory path (`posi_mem_*` + per-segment `SPI_IOC_MESSAGE(1)`). If neither path is available, it returns `false`.[^device][^readme]

See [Native `spi_transfer` vs `posi_mem`](../traps/spi-transfer-native-vs-posi-mem.md).

# Errors / style

- C-style return codes (`null` / `-1` / `false` on failure) — no exceptions from the wrap surface.[^readme]
- Prefer `is_null($var)` over `$var === null` in package code.[^agents]

# Related

* [1:1 extension wrap](../conventions/one-to-one-extension-wrap.md)
* [Enums for spidev flags](../conventions/enums-spidev-flags.md)
* [Half-duplex vs full-duplex](../traps/half-duplex-vs-full-duplex.md)

[^helpers]: Helper file delegating to Device
[^device]: Device facade; posix and transfer paths
[^composer]: Autoload files list; require posix + ext-posi
[^readme]: Helper API and transfer path notes
[^agents]: Helpers → Device → posix only
