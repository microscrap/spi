# Traps

* [Native `spi_transfer` vs `posi_mem`](spi-transfer-native-vs-posi-mem.md) - Reflection check for int-first native helper vs recursion; needs `posi_mem_*` fallback.
* [Half-duplex vs full-duplex](half-duplex-vs-full-duplex.md) - `spi_read` / `spi_write` are half-duplex; use `spi_transfer` for full-duplex.
