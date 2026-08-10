---
type: Orientation
title: Package (0.7)
description: "microscrap/spi 0.7.0 — Linux spidev PHP bindings over ext-posi + microscrap/posix; no ServiceProvider."
resource: .
tags: [orientation, spi, spidev, microscrap, bindings, 0.7]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Package name, namespace, require, suggest, autoload
  - id: readme
    resource: README.md
    title: Package README (0.7.x requirements and surface)
  - id: helpers
    resource: src/Helpers/spi-device.php
    title: Global helper autoload file
  - id: agents
    resource: AGENTS.md
    title: Agent rules for this package
---

# What it is

Composer package `microscrap/spi` at **0.7.0** — PHP helpers, enums, and data objects wrapping Linux `spidev` over [**php-io-extensions/posi**](https://github.com/php-io-extensions/posi) (`ext-posi`) and [`microscrap/posix`](https://github.com/microscrap/posix).[^readme][^composer]

| Field | Value |
|-------|-------|
| Name | `microscrap/spi`[^composer] |
| Version | `0.7.0`[^composer] |
| PHP | `^8.4\|^8.5\|^8.6`[^composer] |
| Namespace | `Microscrap\Bindings\SPI\` → `src/`[^composer] |
| Require | `ext-posi` `^0.7.0`, `microscrap/posix` `^0.7.0`[^composer] |
| Suggest | `scrapyard-io/gpio-framework` `^0.7`[^composer] |
| Homepage | Ecosystem docs overview (see [Ecosystem docs](ecosystem-docs.md))[^readme] |
| Discovery | **None** — no provider / Chassis registration in this package[^agents] |
| Role | Bindings layer only (global helpers + enums + data objects)[^agents][^readme] |

Autoloads `src/Helpers/spi-device.php` (each helper guarded with `function_exists`).[^composer][^helpers]

# What it is not

- Not `php-io-extensions/posi` (the native extension) — this package *uses* that extension via posix helpers / `posi_mem_*`.[^readme]
- Not a ServiceProvider package — no Chassis / Core / Machine coupling; no Fabricate remaps.[^agents]
- Not `gpio-framework` — application orchestration sits above (see [Pair with protocol peers](pairing-protocol-peers.md)).
- Not the FTDI / MPSSE SPI path — that lives beside in `microscrap/ftdi` / `microscrap/mpsse`.

# Public surface (summary)

| Layer | Location | Role |
|-------|----------|------|
| Helpers | `src/Helpers/spi-device.php` | Global `spi_*` functions |
| Facade | `src/Device.php` | Static methods; posix / ioctl / transfer paths |
| Enums | `src/Enums/*` | `SPIMode`, `SPIOpCode` from linux spidev.h |
| Data objects | `src/DataObjects/*` | `SPIDevice`, `SPITransfer` |

# Related

| Topic | Concept |
|-------|---------|
| Call stack | [Helpers → Device → posix](../architecture/helpers-device-posix.md) |
| Wrap rules | [1:1 extension wrap](../conventions/one-to-one-extension-wrap.md) |
| Enums | [Enums for spidev flags](../conventions/enums-spidev-flags.md) |
| Protocol peers | [Pair with protocol peers](pairing-protocol-peers.md) |
| Docs site | [Ecosystem docs](ecosystem-docs.md) |
| Transfer trap | [Native `spi_transfer` vs `posi_mem`](../traps/spi-transfer-native-vs-posi-mem.md) |

[^composer]: Package name, namespace, require, suggest, autoload
[^readme]: Package README (0.7.x requirements and surface)
[^helpers]: Global helper autoload file
[^agents]: Agent rules for this package
