---
okf_version: "0.2"
---

# microscrap/spi Knowledge Bundle

Package knowledge for `microscrap/spi` (Linux spidev bindings over **ext-posi**, v0.10.0).
Read this index first; open only the concepts needed for the task.

**Trust rule:** Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
**Placement:** This bundle lives at the **package root** only — never under `src/`.
**Links:** Concept cross-links use paths relative to each file.
**Scope:** Document the bindings-only package (helpers + enums + data objects). Do **not** invent ServiceProviders, Chassis/Core coupling, or Fabricate remaps here.
**Dist note:** `.okf/` and root `AGENTS.md` are `export-ignore` in `.gitattributes` so Composer dist packages do not ship this bundle.

# Orientation

* [Package (0.10)](orientation/package.md) - Composer identity, namespace, helpers over ext-posi.
* [Ecosystem docs](orientation/ecosystem-docs.md) - Published 0.10.x overview and docs site entrypoint.
* [Pair with protocol peers](orientation/pairing-protocol-peers.md) - ext-posi below; uart / i2c / gpio peers; ftdi/mpsse alternate SPI; scrapyard-io/framework above.

# Architecture

* [Helpers → Device → posix](architecture/helpers-device-posix.md) - Call stack: `spi_*` helpers → `Device` → ext-posi `posix_*` / `posi_mem_*`.

# Conventions

* [1:1 extension wrap](conventions/one-to-one-extension-wrap.md) - Helpers → `Device` only; Device uses ext-posi; no invented APIs.
* [Enums for spidev flags](conventions/enums-spidev-flags.md) - `SPIMode` / `SPIOpCode`; int-backed, FULLY UPPERCASE from linux spidev.h.

# Traps

* [Native `spi_transfer` vs `posi_mem`](traps/spi-transfer-native-vs-posi-mem.md) - Reflection check for int-first native helper vs recursion; `posi_mem_*` otherwise.
* [Half-duplex vs full-duplex](traps/half-duplex-vs-full-duplex.md) - `spi_read` / `spi_write` are half-duplex; use `spi_transfer` for full-duplex.

# Log

* [Directory update log](log.md)
