# Indeks Dokumentasi TELAGA v2

Pelacak pengerjaan: [`../tasklist.md`](../tasklist.md) (task per tahap, progress berbobot, dan titik lanjut untuk agen berikutnya).

## Fondasi produk
- [`prd.md`](00-fondasi/prd.md) — kebutuhan dan scope produk.
- [`rules.md`](00-fondasi/rules.md) — aturan bisnis utama.
- [`glossary.md`](00-fondasi/glossary.md) — kamus istilah.
- [`decision-log.md`](00-fondasi/decision-log.md) — keputusan teknologi dan produk.
- [`roadmap.md`](00-fondasi/roadmap.md) — ringkasan tahapan produk (urutan otoritatif ada di `implementation-plan.md`).
- [`feature-catalog.md`](00-fondasi/feature-catalog.md) — inventaris fitur inti, ditunda, dan pengecualian.

## Arsitektur dan data
- [`domain-architecture.md`](01-arsitektur/domain-architecture.md) — batas domain dan prinsip integrasi.
- [`erd.md`](01-arsitektur/erd.md) — ERD logis Mermaid.
- [`data-dictionary.md`](01-arsitektur/data-dictionary.md) — entitas dan field logis.
- [`workflows.md`](01-arsitektur/workflows.md) — alur PPDB, finansial, tabungan, rapor, dokumen, komunikasi.
- [`status-lifecycle.md`](01-arsitektur/status-lifecycle.md) — status dan transisi.
- [`rapor-template-spec.md`](01-arsitektur/rapor-template-spec.md) — detail LCPA dan snapshot.

## Backend
- [`backend-guide.md`](02-backend/backend-guide.md)
- [`api-contract-conventions.md`](02-backend/api-contract-conventions.md) — konvensi props/route/error Inertia.
- [`database-conventions.md`](02-backend/database-conventions.md)
- [`auth-rbac.md`](02-backend/auth-rbac.md)
- [`permission-matrix.md`](02-backend/permission-matrix.md)
- [`security-baseline.md`](02-backend/security-baseline.md)
- [`documents-and-storage.md`](02-backend/documents-and-storage.md)
- [`email-notifications-queue.md`](02-backend/email-notifications-queue.md)
- [`reports-and-pdf.md`](02-backend/reports-and-pdf.md)
- [`testing-strategy.md`](02-backend/testing-strategy.md)
- [`deployment-runbook.md`](02-backend/deployment-runbook.md)
- [`repository-and-review.md`](02-backend/repository-and-review.md)
- [`backup-and-recovery.md`](02-backend/backup-and-recovery.md)

## UI/UX
- [`information-architecture.md`](03-ui-ux/information-architecture.md)
- [`ui-ux-direction.md`](03-ui-ux/ui-ux-direction.md)
- [`design-system.md`](03-ui-ux/design-system.md)
- [`frontend-implementation.md`](03-ui-ux/frontend-implementation.md)
- [`accessibility-and-ui-states.md`](03-ui-ux/accessibility-and-ui-states.md)

## AI coding workflow dan perencanaan
- [`backend-agent.md`](04-ai-agents/backend-agent.md) — Claude/Codex.
- [`uiux-agent.md`](04-ai-agents/uiux-agent.md) — Gemini/Antigravity.
- [`workflow.md`](04-ai-agents/workflow.md)
- [`task-template.md`](04-ai-agents/task-template.md)
- [`implementation-plan.md`](05-perencanaan/implementation-plan.md) — urutan tahap otoritatif.
- [`report-catalog.md`](05-perencanaan/report-catalog.md) — katalog laporan, dashboard, dan ekspor.
- [`legacy-reference.md`](05-perencanaan/legacy-reference.md) — ringkasan sistem lama (referensi, bukan sumber kode).
- [`hasil-pemeriksaan-lingkungan.md`](05-perencanaan/hasil-pemeriksaan-lingkungan.md)
- [`open-decisions.md`](05-perencanaan/open-decisions.md)
- [`acceptance-checklist.md`](05-perencanaan/acceptance-checklist.md)
- [`requirements-traceability.md`](05-perencanaan/requirements-traceability.md)
