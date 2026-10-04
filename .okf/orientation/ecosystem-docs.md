---
type: Reference
title: Ecosystem docs
description: "Published ScrapyardIO ecosystem docs for microscrap/spi 0.10.x."
resource: "https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/spi/0.10.x/overview"
tags: [orientation, docs, ecosystem, 0.10]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: README production docs link and badges
  - id: composer
    resource: composer.json
    title: homepage and support.docs URLs
  - id: overview
    resource: "https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/spi/0.10.x/overview"
    title: Ecosystem overview page
---

# Entrypoint

Human-facing package docs live on the ScrapyardIO ecosystem site:[^overview][^readme][^composer]

[https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/spi/0.10.x/overview](https://scrapyard-io.projectsaturnstudios.com/ecosystem/microscrap/spi/0.10.x/overview)

README badges, the production docs banner, and `composer.json` `homepage` / `support.docs` point at that overview.[^readme][^composer]

# How agents should use it

- Prefer this OKF bundle for **in-repo** agent rules (wrap layer, enums, traps, peer pairing).
- Prefer the ecosystem site for **published** narrative docs aimed at humans.
- When either drifts from `src/` or README helper tables, update the stale side and note it in [log.md](../log.md).

# Related

* [Package (0.10)](package.md)

[^readme]: README production docs link and badges
[^composer]: homepage and support.docs URLs
[^overview]: Ecosystem overview page
