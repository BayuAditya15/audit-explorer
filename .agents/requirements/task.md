# TASKS — COBIT Explorer

## Aturan kerja
- Development-first; jangan mengulang inspeksi repository secara menyeluruh.
- Kerjakan satu menu per tahap hingga tuntas (UI + data + test).
- Pertahankan master data Excel COBIT dan hasil impor RACI (40 Objective, 231 Practice, 1.202 Activity, 33 Roles, 1.614 RACI assignments).
- Gunakan autentikasi standar (Email + Password) tanpa pembatasan role/permission rumit.
- Tes hanya fitur yang berubah dan dependensi langsungnya menggunakan SQLite `:memory:`.

## Status Tugas Per Menu & Autentikasi
- [x] **T0 — Pembersihan Scope & Database Non-Utama**
  - Rollback dan arsipkan migrasi modul assessment, evidence, findings, reports, audit logs, dan assignments.
  - Sederhanakan navigasi sidebar hanya memuat menu utama COBIT Framework (*Overview*, *COBIT Explorer*, *RACI Matrix*).
  - Hapus dependensi tabel assessment pada `CobitOverview`.

- [x] **Auth — Autentikasi Standar (Login & Logout Biasa Tanpa Role)**
  - Kembalikan model `User` ke standar Laravel (Email, Password, Name).
  - Hapus kolom dan logika `system_role`.
  - Buat `LoginController` untuk rute `GET /login`, `POST /login`, dan `POST /logout`.
  - Buat tampilan antarmuka `auth/login.blade.php` yang rapi, modern, dan selaras dengan tema sistem.
  - Tambahkan profile chip dan tombol logout fungsional di sidebar dan topbar.
  - Seluruh pengguna terautentikasi dapat mengakses semua menu tanpa restriksi role.
  - Buat test `tests/Feature/SimpleAuthTest.php` (6 test, 33 assertion lulus).

- [x] **T1 — Menu COBIT Explorer (`/cobit-framework`)**
  - Selesaikan hirarki 5 Domain, 40 Governance & Management Objectives, dan 231 Practices.
  - Integrasikan inspeksi rincian 1.202 Activities per practice dari data Excel resmi.
  - Sediakan filter domain, pencarian kode/kata kunci/aktivitas, accordion toggle "Buka/Tutup Semua", dan Practice Inspector Modal.
  - Test `tests/Feature/CobitExplorerTest.php` (8 test, 31 assertion lulus).

- [x] **T2 — Menu RACI Matrix (`/raci-matrix`)**
  - Pastikan kelengkapan 33 Standar Roles COBIT 2019.
  - Verifikasi keutuhan 1.614 RACI assignments (tepat 231 Accountable dan 1.383 Responsible).
  - Sempurnakan filter per role, per domain, dan per practice, query string sync, serta tombol cetak matriks.
  - Test `tests/Feature/RaciMatrixTest.php` (9 test, 32 assertion lulus).

- [ ] **T3 — Menu Research / Diagnostic Workspace**
  - Sediakan telaah instrumen awareness diagnostic (dari Excel Toolkit ISACA COBIT 2019 Management Awareness Diagnostic).
  - Integrasikan pemetaan domain dan panduan implementasi tata kelola.

## Kriteria selesai
Tugas ditandai DONE hanya setelah implementasi nyata dan pemeriksaan yang relevan. Catat blocker konkret; jangan mengulang audit menyeluruh.