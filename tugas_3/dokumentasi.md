# Dokumentasi Deteksi dan Analisis Error (Tugas PHP & MySQL)

Dokumentasi ini menguraikan error dan kerentanan keamanan yang teridentifikasi pada kode program: `form-daftar.php`, `proses-pendaftaran-2.php`, dan `koneksi.php` yang disediakan.

## I. Error dan Kerentanan pada `proses-pendaftaran-2.php`

| # | Pesan Error Lengkap | Jenis Error | Letak File & Baris |
| :---: | :--- | :--- | :--- |
| **1** | Undefined variable $sekolah | Syntax/Runtime Error | `proses-pendaftaran-2.php`, Baris 10 |
| **2** | Potential SQL Injection Vulnerability | Security Vulnerability/Logic Error | `proses-pendaftaran-2.php`, Baris 13 |
| **3** | Kesalahan sintaks SQL (Menggunakan `VALUE` alih-alih `VALUES`) | Logic/Runtime Error | `proses-pendaftaran-2.php`, Baris 13 |
| **4** | Misdirected Redirect URL | Logic Error/Typo | `proses-pendaftaran-2.php`, Baris 22 |

### Uraian Detail dan Perbaikan

### 1. Error: Undefined variable $sekolah

* **Penyebab:** Variabel untuk menampung data `sekolah_asal` tidak didefinisikan dengan benar karena hilangnya tanda *dollar sign* (`$`).
    * *Kode Asli:* `sekolah = $_POST['sekolah_asal'];`
* **Cara Memperbaiki:** Tambahkan `$` di depan nama variabel.
    * *Perbaikan:* `$sekolah = $_POST['sekolah_asal'];`

### 2. Kerentanan: Potential SQL Injection Vulnerability

* **Penyebab:** Variabel yang diambil langsung dari input pengguna (`$_POST`) langsung dimasukkan ke dalam *query* SQL. Jika pengguna memasukkan karakter khusus atau perintah SQL, mereka dapat merusak, melihat, atau menghapus data database (*SQL Injection Attack*).
    * *Kode Asli:* `$sql = "INSERT INTO ... VALUE ('$nama', '$alamat', ...)"`
* **Cara Memperbaiki:** Terapkan **Prepared Statements** menggunakan `mysqli_prepare` dan `mysqli_stmt_bind_param`. Metode ini memisahkan struktur *query* (SQL) dari data (variabel input), memastikan data diperlakukan hanya sebagai data, bukan kode SQL.

### 3. Error: Kesalahan sintaks SQL (Keyword `VALUE`)

* **Penyebab:** Dalam perintah `INSERT INTO ...`, kata kunci yang benar untuk menentukan nilai yang akan disisipkan adalah **`VALUES`** (jamak), bukan `VALUE` (tunggal).
* **Cara Memperbaiki:** Ganti kata kunci `VALUE` menjadi `VALUES` dalam *query* SQL.

### 4. Error: Misdirected Redirect URL

* **Penyebab:** Terdapat kesalahan pengetikan pada URL pengalihan (redirect) untuk status gagal.
    * *Kode Asli:* `header('Location: indek.ph?status=gagal');`
* **Cara Memperbaiki:** Perbaiki kesalahan pengetikan URL menjadi `index.php`.
    * *Perbaikan:* `header('Location: index.php?status=gagal');`


## II. Error dan Best Practice pada `form-daftar.php`

| # | Pesan Error/Masalah | Jenis Error | Letak File & Baris |
| :---: | :--- | :--- | :--- |
| **5** | Missing Doctype Tag | Code Standard/Best Practice | `form-daftar.php`, Baris 1 |

### Uraian Detail dan Perbaikan

### 5. Error: Missing Doctype Tag

* **Penyebab:** Tag pembuka *DOCTYPE* pada HTML tidak ditulis dengan sintaks yang benar. Tertulis `<DOCTYPE >` yang tidak valid.
* **Cara Memperbaiki:** Gunakan sintaks standar untuk HTML5.
    * *Perbaikan:* Ganti `<DOCTYPE >` menjadi `<!DOCTYPE html>`.
