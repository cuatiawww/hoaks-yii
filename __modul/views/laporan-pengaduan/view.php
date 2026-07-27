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
          <h5 class="mb-0 fw-bold">TINJAU LAPORAN PENGADUAN</h5>
          <p>No. Tiket: <strong><?= Html::encode($model->no_tiket) ?></strong></p>
        </div>
      </div>
      <div class="col-sm-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= Url::to(['/site/index']) ?>"><i class="ph-duotone ph-house"></i></a></li>
          <li class="breadcrumb-item"><a href="<?= Url::to(['index']) ?>">PENGADUAN</a></li>
          <li class="breadcrumb-item">TINJAU</li>
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
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h5>RINCIAN ISU & PELAPOR</h5>
          <p>Detail informasi yang disampaikan oleh masyarakat.</p>
        </div>
        <div>
          <?php if ($model->status_verifikasi === 'BARU'): ?>
            <span class="badge bg-warning">BARU</span>
          <?php elseif ($model->status_verifikasi === 'TERVERIFIKASI'): ?>
            <span class="badge bg-success">TERVERIFIKASI</span>
          <?php elseif ($model->status_verifikasi === 'DITOLAK'): ?>
            <span class="badge bg-danger">DITOLAK</span>
          <?php else: ?>
            <span class="badge bg-secondary"><?= Html::encode($model->status_verifikasi) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th style="width: 180px;">No. Tiket</th>
            <td><strong><?= Html::encode($model->no_tiket) ?></strong></td>
          </tr>
          <tr>
            <th>Judul Isu Hoaks</th>
            <td><strong><?= Html::encode($model->judul_isu) ?></strong></td>
          </tr>
          <tr>
            <th>Informasi Pelapor</th>
            <td>
              <div><i class="ti ti-user me-1"></i><strong><?= Html::encode($model->nama_pelapor) ?></strong></div>
              <div class="text-muted text-xs"><i class="ti ti-mail me-1"></i><?= Html::encode($model->email_pelapor) ?> | <i class="ti ti-phone me-1"></i><?= Html::encode($model->telepon_pelapor ?: '-') ?></div>
            </td>
          </tr>
          <tr>
            <th>Kategori Isu</th>
            <td><span class="badge bg-info text-uppercase"><?= Html::encode(str_replace('-', ' ', $model->kategori_slug ?? 'pengaduan')) ?></span></td>
          </tr>
          <tr>
            <th>Deskripsi Isu</th>
            <td><div class="p-3 bg-light rounded"><?= nl2br(Html::encode($model->deskripsi_isu)) ?></div></td>
          </tr>
          <tr>
            <th>Bukti Lampiran</th>
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

    <div class="card">
      <div class="card-header">
        <h5>FORM PENELUSURAN CEK FAKTA</h5>
        <p>Analisis & penjelasan resmi tim verifikator Kemenkes RI.</p>
      </div>
      <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

          <div class="mb-3">
            <label class="form-label fw-bold">Kesimpulan Status Isu</label>
            <select name="LaporanHoaks[status_hoaks]" class="form-select">
              <option value="1" <?= $model->status_hoaks ? 'selected' : '' ?>>🔴 HOAKS / DISINFORMASI (Informasi Palsu/Sesat)</option>
              <option value="0" <?= !$model->status_hoaks ? 'selected' : '' ?>>🟢 FAKTA / RESMI (Informasi Benar/Terverifikasi)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Hasil Penelusuran Fakta Resmi Kemenkes RI</label>
            <textarea name="LaporanHoaks[penjelasan_fakta]" rows="5" class="form-control" placeholder="Tuliskan uraian klarifikasi & fakta resmi oleh tim verifikator Kemenkes..."><?= Html::encode($model->penjelasan_fakta) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Link Sumber Fakta Resmi (Counter-Fact URLs)</label>
            <input type="text" name="LaporanHoaks[counter_fact_urls]" value="<?= Html::encode($model->counter_fact_urls) ?>" class="form-control" placeholder="Contoh: https://sehatnegeriku.kemkes.go.id/...">
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Penelusuran Fakta</button>
          </div>

        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5>AKSI & SMTP MAILER</h5>
        <p>Pengiriman email otomatis ke pelapor.</p>
      </div>
      <div class="card-body">
        <p class="text-muted small mb-3">
          Aksi di bawah ini akan memperbarui status sistem dan secara otomatis mengirimkan email resmi dari <strong>SIPKK Notification</strong> ke <strong><?= Html::encode($model->email_pelapor) ?></strong>.
        </p>

        <div class="p-3 bg-light-success rounded mb-3 border border-success">
          <h6 class="text-success fw-bold mb-1"><i class="ti ti-check me-1"></i> 1. Verifikasi & Kirim Email</h6>
          <p class="text-muted small mb-3">Setujui laporan dan kirimkan ringkasan hasil cek fakta ke email pelapor.</p>
          <a href="<?= Url::to(['verifikasi', 'id' => $model->id]) ?>" class="btn btn-success w-100" onclick="return confirm('Verifikasi laporan ini dan kirim notifikasi email ke pelapor?');">
            <i class="ti ti-mail-forward me-1"></i> Verifikasi & Kirim Email
          </a>
        </div>

        <div class="p-3 bg-light-danger rounded border border-danger">
          <h6 class="text-danger fw-bold mb-1"><i class="ti ti-x me-1"></i> 2. Tolak Laporan</h6>
          <p class="text-muted small mb-2">Tolak laporan jika informasi tidak valid atau kurang lengkap.</p>
          <?php $formTolak = ActiveForm::begin(['action' => Url::to(['tolak', 'id' => $model->id])]); ?>
            <div class="mb-2">
              <input type="text" name="alasan_penolakan" class="form-control form-control-sm" placeholder="Isi alasan penolakan..." required>
            </div>
            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak laporan ini dan kirim pemberitahuan email ke pelapor?');">
              <i class="ti ti-x me-1"></i> Tolak & Kirim Email
            </button>
          <?php ActiveForm::end(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
