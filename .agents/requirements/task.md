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

- [x] **T3 — Research & Methodology Hub (Standar Resmi ISACA COBIT 2019) (`/research-methodology`)**
  - Sediakan landasan teori resmi ISACA: Pemisahan wewenang Governance vs Management, 6 Prinsip Sistem Tata Kelola (Figure 3.1), 3 Prinsip Kerangka Kerja Tata Kelola (Figure 3.2), COBIT Goals Cascade (Figure 4.16: Stakeholder Needs -> 13 EG -> 13 AG -> 40 Objectives), 7 Komponen Tata Kelola (Figure 4.1), serta 11 Design Factors & 3 Dampak Desain (Figure 4.4 & 7.1).
  - Tampilkan rincian 5 domain & 40 subdomain (COBIT objectives) lengkap dengan Purpose Statement resmi.
  - Dokumentasikan alur metodologi perancangan sistem 4 tahapan ISACA (Stage 1 s.d Stage 4 - Figure 7.2), skala kapabilitas proses CMMI 0–5, kriteria skor NPLF (0-15%, >15-50%, >50-85%, >85-100%), aturan emas milestone ISACA (Milestone Rule), dan formula matematis Gap Analysis (GAP = Target - Current).
  - Sertakan prinsip penugasan matriks RACI resmi ISACA (prinsip tunggal Accountable per aktivitas).
  - Sediakan glosarium istilah baku COBIT 2019 interaktif dengan filter pencarian instan.
  - Sediakan matriks studi komparatif penelitian terdahulu (State of the Art) untuk referensi Bab 2.
  - Test `tests/Feature/ResearchHubTest.php` (6 test, 44 assertion lulus).

## Kriteria selesai
Tugas ditandai DONE hanya setelah implementasi nyata dan pemeriksaan yang relevan. Catat blocker konkret; jangan mengulang audit menyeluruh.