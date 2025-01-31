# 📝 Task List - Installation Steps for Internship System

## ✅ 1. Konfigurasi Sekolah (School Configuration)

-   [x] 🔨 **Membuat Model dan Tabel Database untuk Sekolah**:

    -   Tambahkan atribut pada model `School`:
        -   `id` (Primary Key)
        -   `name` (Nama sekolah)
        -   `logo` (Logo sekolah)
        -   `address` (Alamat sekolah)
        -   `postcode` (Kode pos sekolah)
        -   `email` (Informasi email sekolah)
        -   `telp` (Informasi kontak telepon)
        -   `fax` (Informasi kontak fax)
        -   `principal_name` (Nama kepala sekolah)
        -   `created_at` (Timestamp)
        -   `updated_at` (Timestamp)
    -   Pastikan tabel `schools` ada dengan kolom yang dibutuhkan.

-   [x] 🛠️ **Service Layer untuk Sekolah**:

    -   **SchoolService**: Buat service untuk menangani logika terkait dengan konfigurasi sekolah, seperti menyimpan data sekolah.

-   [x] 📋 **Form Input untuk Sekolah (Livewire Component)**:

    -   Buat form untuk input data sekolah.
    -   Validasi data yang dimasukkan oleh pengguna (misalnya nama sekolah wajib diisi).
    -   Panggil `SchoolService` untuk menyimpan data sekolah ke database.

-   [x] 🔄 **Menghubungkan Sekolah ke Department dan Classroom**:
    -   Pastikan data yang dimasukkan di sekolah terhubung dengan **department** dan **classroom** dalam konfigurasi berikutnya.

## ✅ 2. Konfigurasi Jurusan (Department and Classroom Configuration)

-   [ ] 🔨 **Membuat Model dan Tabel Database untuk Jurusan dan Kelas**:

    -   Tambahkan atribut pada model `Department`:
        -   `id` (Primary Key)
        -   `name` (Nama jurusan)
        -   `school_id` (Foreign Key ke `School`, menunjukkan jurusan ini milik sekolah mana)
        -   `created_at` (Timestamp)
        -   `updated_at` (Timestamp)
    -   Tambahkan atribut pada model `Classroom`:
        -   `id` (Primary Key)
        -   `name` (Nama kelas)
        -   `department_id` (Foreign Key ke `Department`)
        -   `created_at` (Timestamp)
        -   `updated_at` (Timestamp)
    -   Pastikan tabel `departments` dan `classrooms` ada dengan kolom yang dibutuhkan.

-   [ ] 🛠️ **Service Layer untuk Department dan Classroom**:

    -   **DepartmentService**: Buat service untuk menangani logika pembuatan jurusan dan menghubungkannya dengan sekolah.
    -   **ClassroomService**: Buat service untuk menangani logika pembuatan kelas dan menghubungkannya dengan jurusan.

-   [ ] 📋 **Form Input untuk Jurusan dan Kelas (Livewire Component)**:

    -   Buat form untuk input **jurusan** dan **kelas**.
    -   Pastikan pengguna dapat memilih **sekolah** untuk jurusan dan **jurusan** untuk kelas.
    -   Validasi data yang dimasukkan dan panggil `DepartmentService` dan `ClassroomService` untuk menyimpan data.

-   [ ] 🧪 **Uji Coba dan Validasi**:
    -   Pastikan data **jurusan** bisa ditambahkan dengan benar dan terhubung ke **sekolah**.
    -   Pastikan **kelas** bisa ditambahkan dan terhubung ke **jurusan** yang dipilih.

## ✅ 3. Konfigurasi Administrator (Administrator Setup)

-   [ ] 🔨 **Membuat Model dan Tabel Database untuk User**:

    -   Tambahkan atribut pada model `User`:
        -   `id` (Primary Key)
        -   `name` (Nama lengkap pengguna)
        -   `email` (Email pengguna)
        -   `password` (Password pengguna)
        -   `role` (Role pengguna: `admin`, `teacher`, `student`, dll.)
        -   `department_id` (Foreign Key ke `Department`)
        -   `classroom_id` (Foreign Key ke `Classroom`, bisa null)
        -   `created_at` (Timestamp)
        -   `updated_at` (Timestamp)
    -   Pastikan tabel `users` ada dengan kolom yang dibutuhkan.

-   [ ] 🛠️ **Service Layer untuk User**:

    -   **UserService**: Buat service untuk menangani pembuatan akun administrator dan menghubungkannya dengan **department** dan **classroom**.

-   [ ] 📋 **Form Input untuk Administrator (Livewire Component)**:

    -   Buat form untuk membuat akun **admin**.
    -   Validasi data input (misalnya, email harus valid, password harus sesuai kebijakan keamanan).
    -   Panggil `UserService` untuk membuat akun admin dan menyimpannya di database.
    -   Pastikan akun admin terhubung ke **department** dan **classroom** sesuai konfigurasi.

-   [ ] 🧪 **Uji Coba dan Validasi**:
    -   Pastikan akun admin bisa dibuat dengan benar.
    -   Pastikan admin dapat dihubungkan ke **department** dan **classroom**.

## ✅ 4. Finishing (Mark Application as Installed)

-   [ ] 🔨 **Update Status Aplikasi (System Model)**:

    -   Tambahkan atribut pada model `System`:
        -   `id` (Primary Key)
        -   `is_installed` (Boolean: `true` jika aplikasi sudah terinstal, `false` jika belum)
        -   `created_at` (Timestamp)
        -   `updated_at` (Timestamp)
    -   Pastikan tabel `systems` ada dengan kolom yang dibutuhkan.
    -   Update status **`is_installed = true`** pada tabel `systems` untuk menandakan bahwa instalasi selesai.

-   [ ] 🛠️ **Service Layer untuk System**:

    -   **SystemService**: Buat service untuk memperbarui status aplikasi ke `is_installed = true`.

-   [ ] 📋 **Tampilkan Status Instalasi Selesai (Livewire Component)**:

    -   Tampilkan pesan sukses atau konfirmasi bahwa instalasi telah berhasil.
    -   Berikan tombol untuk melanjutkan ke aplikasi utama setelah instalasi selesai.

-   [ ] 🧪 **Uji Coba dan Validasi**:
    -   Pastikan status **is_installed** berubah menjadi `true` setelah instalasi selesai.
    -   Verifikasi bahwa aplikasi dapat melanjutkan ke tahap berikutnya setelah instalasi selesai.

---

## 🔹 Bonus (Jika Waktu Tersisa)

-   [ ] ✏️ **Add Error Handling and User Feedback**:
    -   Pastikan setiap form input (sekolah, jurusan, kelas, dan admin) menangani error dengan baik.
    -   Tampilkan pesan error yang jelas jika data tidak valid atau ada kesalahan selama proses penyimpanan.
-   [ ] 📊 **Add Progress Bar/Indicator**:
    -   Tambahkan progress bar atau indikator langkah untuk menunjukkan status instalasi, misalnya: `Step 1 of 4` hingga `Step 4 of 4`.
