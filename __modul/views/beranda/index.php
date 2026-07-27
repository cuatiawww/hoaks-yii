<?php
use yii\helpers\Url;

$this->title = 'Beranda Utama';
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
$user_fullname = $user->nama_lengkap ?? 'Administrator';
?>

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-auto">
                <div class="page-header-title">
                    <h5 class="mb-0 font-weight-600 fw-bold">BERANDA</h5>
                </div>
                <p class="text-muted">Selamat datang di Beranda Utama.</p>
            </div>
            <div class="col-sm-auto">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['beranda/index']) ?>"><i class="ph-duotone ph-house"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page">BERANDA</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body py-5 text-center">
                <div class="mb-4">
                    <i class="ph-duotone ph-house" style="font-size: 4rem; color: #229799;"></i>
                </div>
                <h3 class="fw-bold text-dark">Selamat Datang, <?= htmlspecialchars($user_fullname) ?>!</h3>
                <p class="text-muted col-lg-6 mx-auto mt-2">
                    Anda telah berhasil masuk ke dalam sistem pengelolaan template. Gunakan menu navigasi di sebelah kiri untuk mengelola data user, level akses, modul, dan sub modul.
                </p>
                <div class="mt-4">
                    <a class="btn btn-primary btn-sm px-4 rounded-pill" href="<?= Url::to(['/user-model/index']) ?>">
                        <i class="ti ti-users me-1"></i> Mulai Kelola User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
