# Gudang Frontend - Aturan Pengembangan (Rules)

Dokumen ini berisi standar kode (*coding standards*) yang **WAJIB** diikuti saat memodifikasi, memanipulasi, atau menambah baris kode di dalam repositori ini.

## 1. Aturan Penggunaan Svelte 5

Proyek ini murni menggunakan **Svelte 5**. Aturan berikut tidak dapat ditawar:
- **DILARANG** menggunakan sintaks lawas Svelte 4 (`export let`, `$:`, dll).
- Gunakan `let { namaProp } = $props();` untuk meneruskan properti komponen.
- Gunakan `$derived()` untuk state komputasi, dan minimalkan penggunaan `$effect()` kecuali untuk manipulasi DOM langsung atau sinkronisasi API eksternal.
- Gunakan `onclick={handler}` (tanpa `on:`).
- Gunakan Snippet (`#snippet`) jika mempassing block UI alih-alih `slot` lama.

## 2. Standar Antarmuka & CSS (Tailwind v4)

- Proyek ini menggunakan **Tailwind CSS v4** yang di-*extend* dengan Custom Properties CSS di `src/routes/layout.css`.
- **DILARANG** menggunakan kelas warna primitif arbitrer jika sudah ada sistem komponen. Gunakan kelas abstraksi dasar dari `layout.css` seperti `.btn`, `.btn-primary`, `.card`, `.badge`, `.input-base`, dan `.page-header` untuk konsistensi UI.
- Gunakan `@lucide/svelte` secara eksklusif untuk seluruh ikon.
- Jangan tulis CSS inline atau block `<style>` untuk styling layout standar; gunakan Tailwind. Jika butuh kustomisasi yang ekstensif, letakkan di `layout.css`.

## 3. Tooling & Paket (Package Management)

- Proyek menggunakan **Bun** sebagai *package manager* utama (terdapat `bun.lock`).
- **DILARANG** menggunakan perintah `npm`, `yarn`, atau `pnpm`. Gunakan eksklusif `bun install`, `bun add <paket>`, dan `bun dev`.

## 4. Struktur File Komponen

- Komponen *reusable* (seperti `Modal.svelte`, `Table.svelte`, `FormInput.svelte`) disimpan di `src/lib/components/`.
- Penamaan file komponen Svelte wajib menggunakan *PascalCase*.
- Penamaan file fungsi utilitas atau state menggunakan *camelCase* (contoh: `api.ts`, `auth.svelte.ts`).

## 5. Fetching & Error Handling API

- Seluruh pemanggilan API harus menggunakan wrapper `api.get`, `api.post`, `api.put`, `api.delete` dari `src/lib/api.ts`.
- Wrapper otomatis mengurus header `Authorization: Bearer` dan penanganan `401 Unauthorized` (auto logout & redirect).
- Selalu gunakan blok `try...catch` dan pastikan komponen UI memiliki visualisasi *loading state* (baik berupa spinner maupun *skeleton pulse*) saat operasi asinkron sedang berlangsung. Tampilkan notifikasi error dasar kepada user (misalnya via `alert()` atau indikator UI yang bersih).
