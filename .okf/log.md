## 2026-10-03
* **Update**: ported to 0.10.0 against `ext-posi` ^0.10.0, a C rewrite where `posix_*`, `ioctl`, `posi_mem_*` are native global functions. `microscrap/posix` is gone from `require`; `spi_open` opens with the `O_RDWR | O_CLOEXEC` constants the extension registers. The `posi_mem_*` transfer path lost its `function_exists` guard. Docs URLs now `0.10.x`; suggest is `scrapyard-io/framework` ^0.10.0.

## 2026-09-24
* **Fix**: the posi path sends every segment in one `SPI_IOC_MESSAGE(N)`; it used to send one message per segment, dropping chip select between them. Zero-length segments allowed. `spi_open` opens with `O_CLOEXEC`. [Native vs posi_mem](/traps/spi-transfer-native-vs-posi-mem.md) updated.
* **Update**: relabeled 0.8.0 → 0.9.0 with `ext-posi` / `microscrap/posix` ^0.9.0. No code change.

## 2026-09-14
* **Update**: relabeled 0.7.0 → 0.8.0 with `ext-posi` / `ext-ftdi` 0.8.0. No code change.

# Log

## 2026-08-10

* **Creation**: Initial OKF v0.2 bundle for `microscrap/spi` **0.7.0** (gpio microscrap stack → 0.7) from package sources + `okf/SPEC.md` (GoogleCloudPlatform/knowledge-catalog).
* **Creation**: Orientation — [Package (0.7)](/orientation/package.md), [Ecosystem docs](/orientation/ecosystem-docs.md), [Pair with protocol peers](/orientation/pairing-protocol-peers.md).
* **Creation**: Architecture — [Helpers → Device → posix](/architecture/helpers-device-posix.md).
* **Creation**: Conventions — [1:1 extension wrap](/conventions/one-to-one-extension-wrap.md), [Enums for spidev flags](/conventions/enums-spidev-flags.md).
* **Creation**: Traps — [Native `spi_transfer` vs `posi_mem`](/traps/spi-transfer-native-vs-posi-mem.md), [Half-duplex vs full-duplex](/traps/half-duplex-vs-full-duplex.md).
* **Creation**: Subdirectory indexes under `orientation/`, `architecture/`, `conventions/`, `traps/`; root [index.md](/index.md).
* **Note**: Root `AGENTS.md` already maps these concept paths; all concepts left `status: draft` pending human verification.
