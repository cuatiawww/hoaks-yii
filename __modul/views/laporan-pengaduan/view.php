<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Tinjau Laporan: ' . $model->no_tiket;
$this->params['active_menu'] = 'laporan-pengaduan';
?>

<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center justify-content-between">
      <div class="col-sm-auto">
        <div class="page-header-title">
          <h5 class="mb-0 fw-bold">VERIFIKASI & PUBLIKASI LAPORAN ISU HOAKS</h5>
          <p>No. Tiket Pengaduan: <strong><?= Html::encode($model->no_tiket) ?></strong></p>
        </div>
      </div>
      <div class="col-sm-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= Url::to(['/site/index']) ?>"><i class="ph-duotone ph-house"></i></a></li>
          <li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">PENGADUAN</a></li>
          <li class="breadcrumb-item">PUBLIKASI</li>
        </ul>
      </div>
      <div style="margin-top: 10px;" class="d-flex">
        <a class="btn btn-sm btn-secondary rounded-pill px-3" href="<?= Url::to(['index']) ?>">
          <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <!-- Card Rincian Laporan -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h5>RINCIAN ISU & INFORMASI PELAPOR</h5>
          <p>Detail pengaduan yang disampaikan oleh masyarakat.</p>
        </div>
        <div>
          <?php if ($model->status_verifikasi === 'BARU'): ?>
            <span class="badge bg-warning">BARU</span>
          <?php elseif ($model->status_verifikasi === 'TERVERIFIKASI'): ?>
            <span class="badge bg-success">TERVERIFIKASI & DIPUBLIKASI</span>
          <?php elseif ($model->status_verifikasi === 'DITOLAK'): ?>
            <span class="badge bg-danger">DITOLAK</span>
          <?php else: ?>
            <span class="badge bg-secondary"><?= Html::encode($model->status_verifikasi) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered align-middle">
          <tr>
            <th style="width: 180px;" class="bg-light">No. Tiket Pengaduan</th>
            <td><strong class="font-monospace text-primary fs-6"><?= Html::encode($model->no_tiket) ?></strong></td>
          </tr>
          <tr>
            <th class="bg-light">Judul Isu Hoaks</th>
            <td><strong class="fs-6 text-dark"><?= Html::encode($model->judul_isu) ?></strong></td>
          </tr>
          <tr>
            <th class="bg-light">Informasi Pelapor</th>
            <td>
              <div><i class="ti ti-user me-1 text-muted"></i><strong><?= Html::encode($model->nama_pelapor) ?></strong></div>
              <div class="text-muted text-xs mt-1">
                <i class="ti ti-mail me-1 text-muted"></i><?= Html::encode($model->email_pelapor) ?> 
                | <i class="ti ti-phone me-1 text-muted"></i><?= Html::encode($model->telepon_pelapor ?: '-') ?>
              </div>
            </td>
          </tr>
          <tr>
            <th class="bg-light">Kategori Isu</th>
            <td><span class="badge bg-info text-uppercase"><?= Html::encode(str_replace('-', ' ', $model->kategori_slug ?? 'pengaduan')) ?></span></td>
          </tr>
          <tr>
            <th class="bg-light">Deskripsi / Narasi Isu</th>
            <td><div class="p-3 bg-light rounded text-dark leading-relaxed"><?= nl2br(Html::encode($model->deskripsi_isu)) ?></div></td>
          </tr>
          <tr>
            <th class="bg-light">Bukti Lampiran</th>
            <td>
              <?php if ($model->bukti_url): ?>
                <a href="<?= Html::encode($model->bukti_url) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ti ti-external-link me-1"></i> Buka Link / File Lampiran Bukti</a>
              <?php else: ?>
                <span class="text-muted small">Tidak ada lampiran bukti.</span>
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Form Analisis Fakta & Link Publikasi -->
    <div class="card">
      <div class="card-header">
        <h5>FORM HASIL PENELUSURAN & ARTIKEL PUBLIKASI</h5>
        <p>Analisis, uraian penjelasan & URL artikel publikasi resmi Kemenkes RI.</p>
      </div>
      <div class="card-body">
        <?php $form = ActiveForm::begin(['action' => Url::to(['verifikasi', 'id' => $model->id]), 'method' => 'post']); ?>

          <div class="mb-3">
            <label class="form-label fw-bold">1. Kesimpulan Verifikasi Isu <span class="text-danger">*</span></label>
            <select name="status_hoaks" class="form-select">
              <option value="1" <?= $model->status_hoaks ? 'selected' : '' ?>>🔴 HOAKS / DISINFORMASI (Informasi Palsu / Sesat)</option>
              <option value="0" <?= !$model->status_hoaks ? 'selected' : '' ?>>🟢 FAKTA / RESMI (Informasi Benar / Terverifikasi)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">2. Uraian Penelusuran Cek Fakta Resmi <span class="text-danger">*</span></label>
            <textarea name="penjelasan_fakta" rows="5" class="form-control" placeholder="Tuliskan uraian hasil verifikasi & klarifikasi resmi tim Kemenkes RI..." required><?= Html::encode($model->penjelasan_fakta) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">3. Link URL Artikel / Berita Publikasi Cek Fakta Resmi</label>
            <div class="input-group">
              <span class="input-group-text"><i class="ti ti-link"></i></span>
              <input type="url" name="counter_fact_urls" value="<?= Html::encode($model->counter_fact_urls) ?>" class="form-control" placeholder="Contoh: https://sehatnegeriku.kemkes.go.id/baca/isu-123 atau https://kemkes-page.vercel.app/detail/...">
            </div>
            <span class="text-muted text-xs mt-1 d-block">Link ini akan otomatis dikirimkan ke email pelapor sebagai tautan artikel publikasi resmi.</span>
          </div>

          <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success btn-lg rounded-pill px-4">
              <i class="ti ti-mail-forward me-1"></i> Verifikasi, Dipublikasikan & Kirim Email
            </button>
          </div>

        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5>STATUS & PENOLAKAN LAPORAN</h5>
        <p>Aksi penolakan & pemberitahuan pelapor.</p>
      </div>
      <div class="card-body">
        <?php if (!empty($model->counter_fact_urls)): ?>
          <div class="p-3 bg-light-info rounded mb-4 border border-info">
            <h6 class="text-info fw-bold mb-1"><i class="ti ti-external-link me-1"></i> Link Publikasi Aktif</h6>
            <a href="<?= Html::encode($model->counter_fact_urls) ?>" target="_blank" class="small text-break fw-bold text-decoration-underline">
              <?= Html::encode($model->counter_fact_urls) ?>
            </a>
          </div>
        <?php endif; ?>

        <div class="p-3 bg-light-danger rounded border border-danger">
          <h6 class="text-danger fw-bold mb-1"><i class="ti ti-x me-1"></i> Tolak Laporan</h6>
          <p class="text-muted small mb-2">Tolak laporan jika informasi tidak valid atau kurang lengkap.</p>
          <?php $formTolak = ActiveForm::begin(['action' => Url::to(['tolak', 'id' => $model->id]), 'method' => 'post']); ?>
            <div class="mb-3">
              <textarea name="alasan_penolakan" rows="3" class="form-control form-control-sm" placeholder="Tuliskan alasan penolakan..." required><?= Html::encode($model->alasan_penolakan ?: 'Informasi yang disampaikan kurang lengkap atau tidak memenuhi kriteria isu hoaks kesehatan.') ?></textarea>
            </div>
            <button type="submit" class="btn btn-danger w-100 rounded-pill" onclick="return confirm('Tolak laporan ini dan kirim email pemberitahuan ke pelapor?');">
              <i class="ti ti-x me-1"></i> Tolak & Kirim Email
            </button>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
