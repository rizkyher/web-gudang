# Gudang Frontend - Knowledge Base

Dokumen ini memuat fondasi teknis dan arsitektur dari aplikasi **Gudang Frontend**, sebagai panduan bagi agen AI dan *developer* yang bekerja pada *codebase* ini.

## Arsitektur Aplikasi

Proyek ini adalah aplikasi web moderen yang dibangun menggunakan **SvelteKit**. Sistem ini terbagi menjadi dua portal utama:
1.  **Portal Admin (`/admin`)**: Modul manajemen inventori terpadu, menangani master data barang, persetujuan (approval) permintaan, dan manajemen pengguna. Tampilan berfokus pada layout *dashboard* yang padat data (data-dense) dengan Sidebar.
2.  **Portal User (`/user`)**: Antarmuka berbasis *mobile-first* bagi pengguna/karyawan untuk meminta barang (consumables/assets) dari gudang dan melacak riwayat serta status persetujuan mereka. Tampilan berfokus pada navigasi tab bawah (Bottom Navigation).

## Integrasi Backend (Laravel REST API)

Sistem ini dirancang untuk berkomunikasi dengan **Laravel** sebagai penyedia API utama.
-   **Autentikasi**: Menggunakan **Laravel Sanctum** dengan pola *Bearer Token*. Token disimpan di `localStorage` melalui *store* autentikasi di frontend. Setiap *request* ke API wajib menyertakan header `Authorization: Bearer <token>`.
-   **API Wrapper**: Seluruh komunikasi dilakukan melalui `src/lib/api.ts`. Wrapper ini secara otomatis menangani penyisipan token dan melakukan *auto-redirect* ke halaman `/login` jika server mengembalikan status `401 Unauthorized` (sesi kedaluwarsa).
-   **Postman Collection**: Skrip koleksi API tersedia di *root* repositori (`Gudang-API.postman_collection.json`).

## State Management (Svelte 5 Runes)

Aplikasi ini **sepenuhnya mengadopsi Svelte 5 Runes** untuk manajemen *state* (reaktivitas).
*   **Global State**: Menggunakan *class-based state* dengan *rune* `$state` untuk manajemen yang kompleks. (Contoh: `src/lib/stores/auth.svelte.ts`).
*   **Local State**: Menggunakan `let namaVariabel = $state(nilai)` di dalam file `.svelte`.
*   **Derivations**: Menggunakan `$derived` untuk data komputasi, contoh: `let filteredItems = $derived(...)`.
*   **Props**: Komponen menerima argumen melalui `let { propA, propB } = $props();`.

## Modul & Rute

| Rute Utama | Deskripsi Modul | Hak Akses |
| :--- | :--- | :--- |
| `/login` | Pintu masuk aplikasi. Halaman minimalis berbasis tipografi. | Publik |
| `/user/*` | *Dashboard* pengguna, form permintaan (`/request`), riwayat (`/history`), dan profil. | User |
| `/admin/*` | *Dashboard* admin, master barang, persetujuan, dan manajemen pengguna. | Admin |

**Catatan:** Desain komponen dan *layouting* diatur melalui satu set sistem token CSS kustom di `src/routes/layout.css` yang beroperasi bersama Tailwind v4.
