# Panduan Pembuatan Modul Baru (Yii2 Template Backend)

Ikuti langkah-langkah di bawah ini untuk membuat modul CRUD/fitur baru pada template ini.

---

## 1. Database Table
Buat tabel baru di PostgreSQL (misal: `tugas`). Gunakan skema bawaan dan tambahkan ke `DB/template_db.sql` untuk referensi dump.

---

## 2. Model
Buat file model di `__modul/models/NamaModel.php` (namespace `app\models`).
* Menggunakan koneksi default (`db`).
* Contoh:
```php
namespace app\models;

use yii\db\ActiveRecord;

class Tugas extends ActiveRecord
{
    public static function tableName()
    {
        return 'public.tugas';
    }

    public function rules()
    {
        return [
            [['nama_tugas', 'status'], 'required'],
            [['deskripsi'], 'string'],
            [['nama_tugas'], 'string', 'max' => 255],
        ];
    }
}
```

---

## 3. Controller
Buat file controller di `__modul/controllers/NamaController.php` (namespace `app\controllers`).
* **PENTING**: Controller **WAJIB** mengekstend `BaseController` agar fitur otentikasi & pengecekan hak akses bawaan aktif.
* Contoh:
```php
namespace app\controllers;

use Yii;
use app\models\Tugas;
use yii\data\ActiveDataProvider;

class TugasController extends BaseController
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tugas::find(),
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
}
```

---

## 4. Views & Styling Flat Able
Buat folder view baru di `__modul/views/nama-controller/`. Ikuti panduan styling Flat Able agar visualnya konsisten:

### A. Struktur Page Header & Breadcrumbs
Setiap halaman wajib memiliki `.page-header` untuk judul halaman dan link navigasi:
```php
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-auto">
                <div class="page-header-title">
                    <h5 class="mb-0 fw-bold">NAMA HALAMAN</h5>
                </div>
            </div>
            <div class="col-sm-auto">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['beranda/index']) ?>"><i class="ph-duotone ph-house"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page">NAMA HALAMAN</li>
                </ul>
            </div>
        </div>
    </div>
</div>
```

### B. Desain Card & Form
Gunakan tag `.card` bawaan Flat Able untuk membungkus form atau tabel tanpa menambahkan CSS bayangan/border kustom:
```php
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Judul Card</h5>
    </div>
    <div class="card-body">
        <!-- Konten / Form / Tabel -->
    </div>
</div>
```

### C. Desain Tabel & Tombol Aksi
* **Tabel**: Gunakan kelas `.table .table-striped .table-hover` untuk striping baris. Override warna tulisan header menjadi putih jika link sorting Yii aktif.
* **Badges**: Gunakan badge solid bawaan bootstrap (misal: `<span class="badge bg-success">Active</span>`).
* **Tombol Aksi**: Di dalam tabel daftar data, tombol aksi HANYA boleh berisi ikon saja (tanpa teks) dengan class warna solid:
  * Detail: `<a class="btn btn-sm btn-info text-white"><i class="ti ti-eye"></i></a>`
  * Edit: `<a class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></a>`
  * Hapus: `<a class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></a>`
* **Ikon**: Gunakan pustaka **Tabler Icons** (`ti ti-*`) dan **Phosphor Icons** (`ph-duotone ph-*`).
* **Form**: Gunakan standard `yii\widgets\ActiveForm` untuk pembuatan field input form.

---

## 5. Pendaftaran Menu & Hak Akses (Opsional)
Secara default, jika rute/controller belum didaftarkan di DB, `BaseController` akan mengizinkan akses langsung (untuk mempermudah development).

Jika ingin memasukkan modul ke sidebar menu:
1. Daftarkan menu ke tabel `public.sub_modul`.
2. Daftarkan hak akses ke tabel `public.hak_akses`.
