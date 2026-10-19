# PRD — COBIT Explorer
**Product Requirements Document**

| Informasi | Detail |
|---|---|
| Produk | COBIT Explorer |
| Status | Draft — perlu validasi pemilik project |
| Project | `D:\devproject\fullstack\cobit-explorer` |
| Stack yang diketahui | Laravel 12, Livewire 3, Blade, Tailwind CSS v3 |
| Fokus | Workflow penelitian dan audit berbasis COBIT 2019, dari perencanaan hingga laporan |
| Dokumen terkait | `TASKS.md` |

---

## 1. Ringkasan Produk

COBIT Explorer adalah aplikasi pendamping untuk membantu mahasiswa/peneliti atau auditor merencanakan, melaksanakan, mendokumentasikan, dan melaporkan audit berbasis COBIT 2019.

Sistem diharapkan menghubungkan seluruh alur penelitian: konteks dan scope, pemilihan objective, pemetaan peran RACI, penyusunan instrumen, pengumpulan evidence, evaluasi, findings, rekomendasi, dan laporan. Pada tahap lanjutan, sistem dapat menyediakan bantuan AI untuk menemukan kandidat domain/practice/activity, menyusun draft instrumen, dan meninjau konsistensi penilaian dengan evidence. Keputusan akhir tetap berada pada auditor.

**Penting:** PRD ini menjelaskan kebutuhan dan target produk, bukan klaim bahwa semua fitur sudah tersedia. Status implementasi aktual harus ditentukan melalui audit repository. Untuk saat ini, COBIT Explorer diperlakukan sebagai alat bantu workflow penelitian/TA; apakah aplikasi ini juga menjadi produk utama TA harus dikonfirmasi dengan dosen pembimbing.

## 2. Latar Belakang dan Masalah

Proses audit berbasis COBIT memerlukan keterhubungan yang konsisten antara tujuan penelitian, ruang lingkup, objective/practice yang dipilih, peran yang terlibat, instrumen, evidence, evaluasi, temuan, rekomendasi, dan laporan.

Tanpa alur dan keterlacakan yang baik, pengguna berisiko:
- kehilangan hubungan antara masalah penelitian, scope, dan kesimpulan;
- memilih objective atau practice tanpa alasan yang jelas;
- sulit mengetahui evidence mana yang mendukung suatu evaluasi;
- mencampuradukkan kesiapan administratif dengan hasil penilaian atau status final laporan;
- menghasilkan rekomendasi yang tidak terhubung ke gap dan target awal;
- menerima saran AI tanpa validasi sumber atau keputusan manusia;
- memiliki status dan ringkasan yang berbeda antarhalaman.

## 3. Tujuan Produk

1. Menyediakan workflow audit/penelitian yang runtut dari perencanaan sampai pelaporan.
2. Menjaga keterlacakan antara scope, COBIT objective/practice/activity, RACI, instrumen, evidence, evaluasi, findings, rekomendasi, dan laporan.
3. Membantu pengguna memahami status pekerjaan, kekurangan data, dan tindakan berikutnya.
4. Mendukung evaluasi capability yang dapat dipertanggungjawabkan, berdasarkan metodologi terverifikasi dan validasi auditor.
5. Menyediakan bantuan AI yang dibatasi pada sumber terverifikasi, dapat ditelusuri, dan selalu berada di bawah kendali manusia.
6. Menyediakan UI/UX konsisten, responsif, mudah dipahami, dan memberi feedback yang jelas.
7. Mempertahankan integritas master data dan fungsi yang telah ada selama pengembangan.

## 4. Pengguna dan Kebutuhan

### 4.1 Pengguna utama — Mahasiswa/peneliti/auditor
Membutuhkan kemampuan untuk:
- menentukan konteks, scope, objective, target, dan metode;
- menelusuri master data COBIT dan RACI;
- menyiapkan instrumen dan mengumpulkan evidence;
- menghubungkan evidence dengan praktik yang dinilai;
- memvalidasi evaluasi dan menetapkan hasil capability;
- menyusun findings, rekomendasi, dan laporan;
- melihat jejak keputusan dan keterbatasan hasil.

### 4.2 Reviewer/pembimbing (jika didukung)
Membutuhkan kemampuan untuk meninjau scope, evidence, evaluasi, findings, rekomendasi, dan laporan sesuai hak akses yang tersedia. Role dan hak akses baru tidak boleh diasumsikan sebelum diperiksa dan disetujui.

## 5. Prinsip Produk

- **Human-in-the-loop:** AI memberi saran; auditor memutuskan.
- **Evidence-based:** hasil penilaian dan rekomendasi harus punya dasar yang dapat ditelusuri.
- **Deterministic calculations:** perhitungan resmi dilakukan backend berdasarkan aturan yang disetujui, bukan oleh LLM.
- **Source-grounded AI:** saran AI dibatasi pada kandidat dari master data/sumber yang disetujui.
- **Honest status:** readiness, evaluasi tervalidasi, dan finalisasi laporan adalah status yang berbeda.
- **Traceability by design:** setiap hasil dapat ditelusuri ke sumber, konteks, dan keputusan yang relevan.
- **Privacy by default:** dokumen auditee tidak dikirim ke layanan eksternal tanpa persetujuan eksplisit.
- **Incremental delivery:** audit dan desain dilakukan sebelum implementasi; fitur lanjutan tidak dibangun sebelum kebutuhan dan risikonya dinilai.

## 6. Workflow End-to-End

### Tahap 1 — Perencanaan dan penetapan scope
**Tujuan:** menetapkan konteks audit dan apa yang akan dinilai.

Input yang mungkin diperlukan:
- objek dan konteks penelitian;
- masalah, tujuan, batasan, dan pertanyaan penelitian;
- scope assessment;
- objective COBIT yang dipilih;
- target capability per objective;
- metode pengumpulan evidence dan metode penilaian.

Output:
- rencana assessment dan scope yang dapat ditinjau;
- daftar objective dalam scope;
- target capability yang ditetapkan sebelum penilaian aktual;
- catatan asumsi dan keputusan yang belum disetujui.

Target capability merupakan acuan untuk analisis gap dan rekomendasi di akhir. Sistem tidak boleh menganggap target otomatis sebagai level aktual.

### Tahap 2 — Pemetaan COBIT dan RACI
**Tujuan:** membantu memilih objective/practice/activity yang relevan dan memahami peran yang terlibat.

Kebutuhan:
- menyediakan pencarian dan penelusuran master data COBIT 2019 yang tersedia;
- menampilkan hubungan objective, practice, dan activity;
- menyediakan RACI berdasarkan master data ISACA yang digunakan project;
- memungkinkan pemetaan role generik COBIT ke jabatan/struktur organisasi auditee setelah dikonfirmasi manusia;
- mencatat alasan pemilihan atau pemetaan yang penting.

Output:
- objective/practice/activity yang dipilih dan disetujui;
- RACI acuan;
- pemetaan role auditee yang sudah divalidasi.

AI dapat diusulkan untuk memberi kandidat dan alasan, tetapi tidak boleh menetapkan pemetaan organisasi secara otomatis tanpa review.

### Tahap 3 — Penyusunan instrumen dan pengumpulan evidence
**Tujuan:** mengumpulkan informasi dan bukti yang relevan dengan scope.

Kebutuhan:
- menyusun atau mengelola draft pertanyaan wawancara/kuesioner berdasarkan objective/practice, role, dan metode penelitian yang disetujui;
- mendukung evidence dari wawancara, observasi, inspeksi, atau metode lain yang ditetapkan;
- menghubungkan evidence/workpaper ke assessment, objective, dan practice yang relevan;
- menyimpan metadata dan status verifikasi evidence;
- memberikan validasi file, kontrol akses, dan penyimpanan yang sesuai.

Ekstraksi teks PDF dan OCR untuk dokumen hasil scan merupakan **fitur lanjutan yang perlu dievaluasi**, bukan asumsi kemampuan yang sudah ada. OCR tidak otomatis membuktikan keaslian atau kecukupan evidence.

Output:
- instrumen yang dapat ditinjau;
- evidence yang tertaut ke konteks yang tepat;
- status verifikasi dan daftar bukti yang masih kurang.

### Tahap 4 — Evaluasi practice dan capability
**Tujuan:** menghasilkan penilaian yang transparan dan dapat dipertanggungjawabkan.

Kebutuhan:
- menyimpan evaluasi practice/aktivitas sesuai metodologi yang disetujui;
- mengaitkan penilaian dengan evidence dan metode pengumpulan;
- menampilkan kekurangan bukti dan keterbatasan;
- menyediakan validasi dan konfirmasi oleh assessor/auditor;
- melakukan perhitungan deterministik di backend hanya setelah aturan dan formula diverifikasi.

**Aturan penting:**
- Jangan menghitung capability hanya dari rata-rata jawaban kuesioner.
- Jangan mengonversi rating NI/PI/LI/FI menjadi capability secara otomatis kecuali metodologi resmi yang disetujui secara eksplisit menetapkan mekanisme tersebut.
- COBIT 2019 performance/capability rating criteria harus diverifikasi terhadap panduan resmi yang benar-benar dipakai. Ambang, urutan level, dan rumus tidak boleh dikarang.
- Capability final harus mencerminkan metode penilaian dan validasi auditor, bukan keputusan LLM.
- Readiness administratif bukan bukti bahwa penilaian sudah valid atau report sudah final.

Output:
- hasil evaluasi practice/aktivitas;
- hasil capability yang telah dikonfirmasi sesuai metode;
- catatan dasar penilaian dan keterbatasan.

### Tahap 5 — AI-assisted review (fitur lanjutan)
**Tujuan:** membantu auditor menemukan potensi ketidaksesuaian yang perlu ditinjau.

Contoh:
- skor dinilai Fully, tetapi evidence yang tersedia tampak tidak mendukung sebagian kriteria;
- SOP yang diunggah tidak jelas menunjukkan review berkala;
- bukti tidak sesuai objective/practice yang sedang dinilai.

Kebutuhan:
- AI hanya memberikan flag, alasan, dan sumber/fragmen evidence yang relevan bila tersedia;
- AI tidak mengubah skor, capability, findings, atau status final secara otomatis;
- auditor dapat menerima, menolak, atau mengedit saran;
- keterbatasan dan ketidakpastian hasil harus ditampilkan dengan jelas.

Saran AI bukan bukti final bahwa kontrol berjalan atau tidak berjalan. Auditor perlu memeriksa dokumen dan konteks aslinya.

### Tahap 6 — Gap, findings, dan rekomendasi
**Tujuan:** menghubungkan kondisi aktual yang tervalidasi dengan target dan tindakan perbaikan.

Kebutuhan:
- membandingkan capability aktual yang sudah dikonfirmasi dengan target yang ditetapkan pada tahap perencanaan;
- mendokumentasikan findings dengan kondisi, kriteria, sebab, dampak, serta evidence terkait sesuai metode yang digunakan;
- menyusun rekomendasi yang berhubungan dengan temuan, gap, objective/practice, dan role yang relevan;
- mengurutkan prioritas berdasarkan kriteria risiko yang didefinisikan dan disetujui.

Risk index tidak boleh menjadi angka dekoratif. Formula, skala, sumber input, dan cara interpretasinya harus didokumentasikan sebelum digunakan. Jika informasi risiko belum cukup, sistem harus menunjukkan bahwa prioritas belum dapat dihitung dengan andal.

AI dapat menyarankan draft rekomendasi, tetapi auditor bertanggung jawab atas validasi dan persetujuannya.

Output:
- gap yang didasarkan pada target dan hasil aktual tervalidasi;
- findings yang dapat ditelusuri ke evidence;
- rekomendasi dan prioritas yang memiliki dasar jelas.

### Tahap 7 — Pelaporan dan kesimpulan
**Tujuan:** menyusun hasil penelitian/audit yang konsisten.

Kebutuhan:
- menyajikan konteks, scope, metode, hasil, findings, rekomendasi, keterbatasan, dan kesimpulan sesuai kebutuhan yang disetujui;
- menautkan hasil ke sumber data dan keputusan terkait;
- menandai data yang belum tersedia atau belum tervalidasi;
- memisahkan status draft, review, kesiapan finalisasi, dan finalisasi resmi;
- menjaga kesimpulan tetap menjawab masalah dan tujuan penelitian.

Struktur Bab I–VI harus mengikuti pedoman kampus dan arahan pembimbing, bukan diasumsikan oleh aplikasi. Status `completed` tidak boleh dianggap sama dengan `finalized` tanpa definisi lifecycle yang terverifikasi.

## 7. Kebutuhan AI dan Pencegahan Halusinasi

AI adalah kemampuan tambahan yang dikembangkan setelah workflow inti, sumber data, keamanan, dan metode penilaian siap.

### 7.1 Retrieval sebelum generasi
- Mulai dari pencarian/filter atas master data COBIT dan dokumen yang telah disetujui.
- Evaluasi kebutuhan semantic search/embedding berdasarkan kualitas pencarian aktual.
- Jangan mewajibkan pgvector atau PostgreSQL sebelum arsitektur database dan kebutuhan teknis diperiksa.
- Kirim kandidat terbatas dan konteks yang diperlukan ke model; jangan meminta model mengarang ID dari ingatan.

### 7.2 Output terstruktur dan validasi
- Definisikan schema output untuk tiap jenis saran, misalnya objective/practice/activity candidate, rationale, supporting source, uncertainty/confidence, dan recommendation draft.
- Backend wajib memvalidasi ID dan relasi terhadap database.
- ID yang tidak valid atau relasi yang tidak sah harus ditolak, bukan diam-diam dipakai.
- Confidence model hanya sinyal tambahan dan bukan probabilitas kebenaran yang terjamin.

### 7.3 Human-in-the-loop dan audit trail
Untuk saran AI yang disimpan, rancang pencatatan proporsional yang dapat mencakup:
- konteks/input yang digunakan dengan minimisasi data sensitif;
- sumber/master data dan evidence yang dirujuk;
- hasil saran dan versi model/prompt atau proses;
- status accepted/rejected/edited;
- pengguna yang memutuskan dan waktu keputusan;
- alasan keputusan jika dibutuhkan untuk akuntabilitas.

### 7.4 Privasi dan keamanan
Sebelum memakai OCR, embedding, atau LLM eksternal, evaluasi:
- apakah dokumen mengandung data rahasia atau personal;
- persetujuan auditee dan pemilik data;
- lokasi pemrosesan dan penyimpanan;
- retensi dan penghapusan;
- kontrol akses dan logging;
- kebijakan vendor dan ketentuan hukum yang berlaku.

Tidak boleh ada pengiriman evidence ke API eksternal sebelum persetujuan eksplisit.

## 8. Modul Produk

Modul berikut merupakan peta produk yang harus dicocokkan dengan repository aktual:

1. **Overview / Audit Workspace:** status, hambatan, dan tindakan berikutnya berdasarkan data aktual.
2. **Research & Planning:** konteks penelitian, scope, objective, target, dan keputusan.
3. **COBIT Framework Explorer:** pencarian objective, practice, activity, dan relasi master data.
4. **RACI Matrix:** penelusuran role dan assignment serta pemetaan role auditee yang tervalidasi.
5. **Instruments:** draft/daftar instrumen wawancara atau kuesioner jika disetujui.
6. **Evidence & Workpapers:** dokumen, bukti, metode, status verifikasi, dan relasi ke practice.
7. **Assessment & Evaluation:** evaluasi berbasis metode yang disetujui dan konfirmasi assessor.
8. **AI Suggestions/Review:** saran yang dibatasi sumber, dapat ditinjau, dan memiliki audit trail; fitur lanjutan.
9. **Findings & Recommendations:** temuan, gap, prioritas, dan rencana perbaikan.
10. **Reports:** ringkasan, hasil, keterbatasan, review, dan finalisasi.

Modul Research & Planning, Instruments, dan AI tidak boleh diasumsikan sudah ada. Jika belum ada, evaluasi apakah diperlukan dalam scope awal sebelum implementasi.

## 9. Persyaratan UI/UX

### 9.1 Prinsip
- Navigasi mengikuti alur kerja, bukan sekadar daftar tabel.
- Halaman menunjukkan tujuan, status, tindakan utama, dan keterkaitan data.
- Informasi penting diprioritaskan; detail lanjutan tidak memenuhi layar tanpa kebutuhan.
- Status tidak boleh menyesatkan; gunakan label yang jelas dan konsisten.
- Setiap form memiliki label, validasi, dan feedback.
- Tabel dan filter mendukung pencarian dan pembacaan data padat.
- Sediakan empty, loading, error, permission-denied, dan partial-data states.
- Pastikan responsivitas dan aksesibilitas dasar.
- Hindari kartu statistik tanpa fungsi dan menu yang belum memiliki kebutuhan jelas.

### 9.2 Design system
Audit pola yang sudah ada sebelum menetapkan:
- warna semantic dan status;
- tipografi dan skala heading;
- spacing, grid, border, radius, dan elevation;
- app shell, sidebar, header, breadcrumb, dan navigasi aktif;
- tombol, input, select, search/filter, tabel, pagination, modal, toast, dan badge;
- pola halaman list, detail, create/edit, review, dan report.

Pertahankan stack dan komponen yang ada jika memadai. Library UI baru membutuhkan alasan dan persetujuan.

## 10. Persyaratan Integritas dan Batasan

- Backend/service yang disepakati adalah sumber kebenaran perhitungan; jangan menduplikasi formula di UI.
- Data legacy (`is_legacy = true`, termasuk Assessment ID 1) tetap read-only dan dikecualikan dari perhitungan aktif/finalisasi.
- Jangan mengubah lifecycle atau definisi status tanpa keputusan pemilik project.
- Jangan menampilkan `FINAL` hanya karena readiness terpenuhi.
- Jangan membuat nilai, evidence, skor, risk index, atau hasil AI yang tidak didukung sumber.
- Jangan mengubah master data COBIT atau makna assignment RACI saat redesign.
- Jangan mengubah database, `.env`, dependency, lockfile, atau Git selama fase audit/perencanaan.
- Jangan menambah integrasi AI/API eksternal tanpa persetujuan eksplisit.

## 11. Prioritas Pengembangan

Urutan awal yang disarankan, disesuaikan setelah audit:

### P0 — Audit dan validasi
- Inventaris fitur dan alur aktual.
- Verifikasi status lifecycle, legacy isolation, authorization, dan sumber perhitungan.
- Identifikasi kekurangan workflow dan UI/UX.

### P1 — Perencanaan UX dan fondasi
- Arsitektur informasi dan navigasi.
- Design system yang konsisten.
- Layout utama, responsive behavior, dan komponen dasar.

### P2 — Workflow inti
- Workspace/perencanaan dan scope.
- Framework Explorer dan RACI.
- Evidence/workpapers dan evaluasi.
- Findings/recommendations dan reports.
- Keterlacakan antarbagian dan status yang benar.

### P3 — QA dan peningkatan
- Pengujian alur end-to-end.
- Aksesibilitas, responsivitas, error/empty states.
- Perbaikan regresi dan inkonsistensi.

### P4 — Fitur lanjutan (butuh persetujuan terpisah)
- OCR/ekstraksi PDF.
- Semantic search/RAG.
- Saran domain/practice/activity oleh AI.
- AI review evidence.
- Draft instrumen dengan bantuan AI.
- Risk scoring/roadmap otomatis jika metodologi risiko telah ditetapkan.
- Literature bank dan ekspor laporan jika kebutuhan dikonfirmasi.

## 12. Kriteria Keberhasilan

Produk memenuhi arah yang dituju apabila:
1. Pengguna dapat mengikuti alur dari perencanaan sampai laporan.
2. Scope, objective, RACI, instrumen, evidence, evaluasi, findings, rekomendasi, dan laporan memiliki keterkaitan yang dapat ditelusuri.
3. Status pekerjaan dan progres bersumber dari data aktual.
4. Target capability ditetapkan sebelum evaluasi dan tidak disamakan dengan capability aktual.
5. Penilaian capability mengikuti metodologi yang diverifikasi dan dikonfirmasi auditor.
6. AI, jika ditambahkan, hanya menyarankan dan seluruh output kritis divalidasi backend serta manusia.
7. Bukti dan keterbatasan hasil terlihat jelas.
8. Readiness dan finalisasi laporan dibedakan.
9. Data legacy tidak memengaruhi kalkulasi aktif.
10. UI konsisten, responsif, dan menyediakan feedback untuk kondisi normal maupun error.
11. Pengujian dan status implementasi dilaporkan berdasarkan hasil nyata.

## 13. Keputusan Terbuka

Hal berikut perlu dikonfirmasi sebelum implementasi terkait:
- Apakah aplikasi ini hanya alat bantu TA atau juga produk akhir TA.
- Pedoman resmi penulisan TA dan kebutuhan ekspor laporan.
- Metodologi capability assessment dan sumber resmi yang menjadi acuan.
- Definisi status lifecycle, khususnya `completed`, `finalized`, dan readiness.
- Role pengguna dan alur persetujuan yang diperlukan.
- Definisi, skala, dan formula risk index jika roadmap berbasis risiko akan dibuat.
- Apakah OCR, semantic search, AI suggestions, dan AI review diperlukan dalam versi awal.
- Teknologi database dan batasan infrastruktur aktual.
- Kebijakan privasi dan persetujuan penggunaan dokumen auditee dengan layanan AI.
- Kebutuhan instrumen wawancara/kuesioner dan kesesuaiannya dengan metodologi TA.

## 14. Tata Kelola Perubahan

PRD ini adalah acuan produk. Setiap perubahan besar pada scope:
1. harus didukung temuan audit atau kebutuhan pengguna;
2. mencatat dampak pada data, arsitektur, keamanan, metode, dan UI/UX;
3. meminta persetujuan pemilik project;
4. memperbarui PRD dan `TASKS.md` secara konsisten;
5. tidak boleh otomatis memicu implementasi sebelum persetujuan.
