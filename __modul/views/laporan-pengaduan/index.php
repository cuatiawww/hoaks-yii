<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Manajemen Pengaduan & Laporan Isu Hoaks';
$this->params['active_menu'] = 'laporan-pengaduan';

$swal = Yii::$app->session->getFlash('swal', null);
if ($swal) {
    $swalJson = \yii\helpers\Json::encode($swal);
    $js = <<<JS
(function(){
  var opt = $swalJson;
  if(!opt.toast && opt.position === undefined) {
    if (opt.icon === 'success' || (opt.title && /berhasil/i.test(opt.title))) {
      opt.position = 'center';
    } else {
      opt.position = 'top-end';
    }
  }
  if(!opt.toast && opt.timer === undefined) opt.timer = 2500;
  if(!opt.toast && opt.showConfirmButton === undefined) opt.showConfirmButton = false;
  if (typeof Swal !== 'undefined') {
    Swal.fire(opt);
  }
})();
JS;
    $this->registerJs($js);
}
?>

<style>
  .laporan-pengaduan-index .table thead th,
  .laporan-pengaduan-index .table thead th a,
  .laporan-pengaduan-index .table thead tr th {
      color: #ffffff !important;
      background-color: #07877c !important;
      font-weight: 700 !important;
      font-size: 11px !important;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      vertical-align: middle;
      border-bottom: none !important;
  }
</style>

<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center justify-content-between">
      <div class="col-sm-auto">
        <div class="page-header-title">
          <h5 class="mb-0 fw-bold">MANAJEMEN PENGADUAN & LAPORAN ISU HOAKS</h5>
          <p>Tatakelola Pengaduan Isu Publik, Verifikasi Cek Fakta & Notifikasi Email</p>
        </div>
      </div>
      <div class="col-sm-auto">
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= Url::to(['/site/index']) ?>"><i class="ph-duotone ph-house"></i></a></li>
          <li class="breadcrumb-item">PENGADUAN</li>
        </ul>
      </div>
      <div style="margin-top: 10px;" class="d-flex">
        <a class="btn btn-sm btn-primary rounded-pill px-2" role="button" href="javascript:void(0);">
          <i class="ti ti-external-link me-1"></i> Info Selengkapnya
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h5>DAFTAR LAPORAN PENGADUAN</h5>
          <p>Daftar pengaduan isu hoaks publik & status verifikasi.</p>
        </div>
        <div class="btn-group" role="group">
          <a href="<?= Url::to(['index']) ?>" class="btn btn-sm <?= empty($statusFilter) ? 'btn-primary' : 'btn-outline-secondary' ?>">
            Semua (<?= $countSemua ?>)
          </a>
          <a href="<?= Url::to(['index', 'status' => 'BARU']) ?>" class="btn btn-sm <?= $statusFilter === 'BARU' ? 'btn-warning text-white' : 'btn-outline-warning' ?>">
            Baru (<?= $countBaru ?>)
          </a>
          <a href="<?= Url::to(['index', 'status' => 'TERVERIFIKASI']) ?>" class="btn btn-sm <?= $statusFilter === 'TERVERIFIKASI' ? 'btn-success text-white' : 'btn-outline-success' ?>">
            Terverifikasi (<?= $countTerverifikasi ?>)
          </a>
          <a href="<?= Url::to(['index', 'status' => 'DITOLAK']) ?>" class="btn btn-sm <?= $statusFilter === 'DITOLAK' ? 'btn-danger text-white' : 'btn-outline-danger' ?>">
            Ditolak (<?= $countDitolak ?>)
          </a>
        </div>
      </div>
      <div class="card-body table-border-style">
        <div class="table-responsive">

          <?php Pjax::begin(['id' => 'pjax-laporan-grid']); ?>

          <?= GridView::widget([
            'summary' => '',
            'tableOptions' => ['class' => 'table table-striped table-hover'],
            'dataProvider' => $dataProvider,
            'columns' => [
              [
                'class' => 'yii\\grid\\SerialColumn',
                'header' => 'NO',
                'options' => ['style' => 'width: 50px; text-align: center;'],
              ],
              [
                'attribute' => 'no_tiket',
                'header' => 'NO. TIKET',
                'format' => 'raw',
                'value' => function($model) {
                  return '<strong>' . Html::encode($model->no_tiket) . '</strong>';
                }
              ],
              [
                'attribute' => 'judul_isu',
                'header' => 'JUDUL ISU HOAKS',
                'format' => 'raw',
                'value' => function($model) {
                  return '<div><strong>' . Html::encode(\yii\helpers\StringHelper::truncate($model->judul_isu, 60)) . '</strong></div>' .
                         '<div class="text-muted text-xs">' . Html::encode(\yii\helpers\StringHelper::truncate($model->deskripsi_isu, 70)) . '</div>';
                }
              ],
              [
                'attribute' => 'nama_pelapor',
                'header' => 'PELAPOR & EMAIL',
                'format' => 'raw',
                'value' => function($model) {
                  return '<div>' . Html::encode($model->nama_pelapor) . '</div>' .
                         '<div class="text-muted text-xs">' . Html::encode($model->email_pelapor) . '</div>';
                }
              ],
              [
                'attribute' => 'kategori_slug',
                'header' => 'KATEGORI',
                'value' => function($model) {
                  return strtoupper(str_replace('-', ' ', $model->kategori_slug ?? 'pengaduan'));
                }
              ],
              [
                'attribute' => 'status_verifikasi',
                'header' => 'STATUS',
                'format' => 'raw',
                'value' => function($model) {
                  if ($model->status_verifikasi === 'BARU') {
                    return '<span class="badge bg-warning">BARU</span>';
                  }
                  if ($model->status_verifikasi === 'TERVERIFIKASI') {
                    return '<span class="badge bg-success">TERVERIFIKASI</span>';
                  }
                  if ($model->status_verifikasi === 'DITOLAK') {
                    return '<span class="badge bg-danger">DITOLAK</span>';
                  }
                  return '<span class="badge bg-secondary">' . Html::encode($model->status_verifikasi) . '</span>';
                }
              ],
              [
                'attribute' => 'created_at',
                'header' => 'TGL LAPORAN',
                'value' => function($model) {
                  return date('d M Y H:i', strtotime($model->created_at));
                }
              ],
              [
                'class' => 'yii\\grid\\ActionColumn',
                'header' => 'AKSI',
                'template' => '{view} {verifikasi} {tolak}',
                'buttons' => [
                  'view' => function ($url, $model) {
                    return Html::a('<i class="ti ti-eye"></i>', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info', 'title' => 'Tinjau Rincian']);
                  },
                  'verifikasi' => function ($url, $model) {
                    return Html::button('<i class="ti ti-check"></i>', [
                      'class' => 'btn btn-sm btn-success btn-approve-modal me-1',
                      'title' => 'Verifikasi & Publikasi Artikel',
                      'data-id' => $model->id,
                      'data-tiket' => $model->no_tiket,
                      'data-judul' => $model->judul_isu,
                      'data-pelapor' => $model->nama_pelapor . ' (' . $model->email_pelapor . ')',
                      'data-penjelasan' => $model->penjelasan_fakta,
                      'data-url' => $model->counter_fact_urls,
                      'data-status' => $model->status_hoaks ? '1' : '0',
                    ]);
                  },
                  'tolak' => function ($url, $model) {
                    return Html::button('<i class="ti ti-x"></i>', [
                      'class' => 'btn btn-sm btn-danger btn-reject-modal',
                      'title' => 'Tolak Laporan',
                      'data-id' => $model->id,
                      'data-tiket' => $model->no_tiket,
                      'data-judul' => $model->judul_isu,
                      'data-pelapor' => $model->nama_pelapor . ' (' . $model->email_pelapor . ')',
                    ]);
                  },
                ],
              ],
            ],
          ]); ?>

          <?php Pjax::end(); ?>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 1: Modal Persetujuan & Publikasi Artikel Cek Fakta -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalVerifikasi" tabindex="-1" aria-labelledby="modalVerifikasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="formVerifikasi" method="post" action="">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
        
        <div class="modal-header bg-primary text-white py-3">
          <h5 class="modal-title text-white fw-bold" id="modalVerifikasiLabel">
            <i class="ti ti-check-circle me-1"></i> Verifikasi & Publikasi Cek Fakta
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body p-4">
          <div class="alert alert-info py-2 px-3 mb-3 text-xs">
            <i class="ti ti-info-circle me-1"></i> Formulir ini akan menyetujui status verifikasi dan <strong>mengirimkan email hasil penelusuran beserta link artikel publikasi ke pelapor</strong>.
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label text-xs uppercase font-weight-bold text-muted">No. Tiket Pengaduan</label>
              <input type="text" id="v_no_tiket" class="form-control font-monospace font-weight-bold bg-light" readonly />
            </div>
            <div class="col-md-6">
              <label class="form-label text-xs uppercase font-weight-bold text-muted">Pelapor & Email</label>
              <input type="text" id="v_pelapor" class="form-control bg-light" readonly />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label text-xs uppercase font-weight-bold text-muted">Judul Isu Hoaks</label>
            <input type="text" id="v_judul" class="form-control font-weight-bold bg-light" readonly />
          </div>

          <hr class="my-3" />

          <div class="mb-3">
            <label class="form-label fw-bold">1. Kesimpulan Verifikasi Isu <span class="text-danger">*</span></label>
            <select name="status_hoaks" id="v_status_hoaks" class="form-select">
              <option value="1">🔴 HOAKS / DISINFORMASI (Informasi Sesat / Palsu)</option>
              <option value="0">🟢 FAKTA / RESMI (Informasi Benar / Terverifikasi)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">2. Uraian Penelusuran Cek Fakta Resmi <span class="text-danger">*</span></label>
            <textarea name="penjelasan_fakta" id="v_penjelasan_fakta" rows="4" class="form-control" placeholder="Tuliskan uraian hasil verifikasi & penjelasan resmi Kemenkes RI..." required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">3. Link URL Artikel / Publikasi Cek Fakta Resmi</label>
            <div class="input-group">
              <span class="input-group-text"><i class="ti ti-link"></i></span>
              <input type="url" name="counter_fact_urls" id="v_counter_fact_urls" class="form-control" placeholder="Contoh: https://sehatnegeriku.kemkes.go.id/baca/klarifikasi-isu-123 atau link berita resmi" />
            </div>
            <span class="text-muted text-xs mt-1 d-block">Link ini akan disertakan pada email notifikasi yang dikirimkan ke pelapor.</span>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success rounded-pill px-4">
            <i class="ti ti-mail-forward me-1"></i> Verifikasi & Terbitkan Artikel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: Modal Penolakan Laporan -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formTolak" method="post" action="">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />

        <div class="modal-header bg-danger text-white py-3">
          <h5 class="modal-title text-white fw-bold" id="modalTolakLabel">
            <i class="ti ti-x me-1"></i> Penolakan Laporan Isu
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label text-xs uppercase font-weight-bold text-muted">No. Tiket Pengaduan</label>
            <input type="text" id="r_no_tiket" class="form-control font-monospace font-weight-bold bg-light" readonly />
          </div>

          <div class="mb-3">
            <label class="form-label text-xs uppercase font-weight-bold text-muted">Judul Isu Hoaks</label>
            <input type="text" id="r_judul" class="form-control font-weight-bold bg-light" readonly />
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Alasan Penolakan Laporan <span class="text-danger">*</span></label>
            <textarea name="alasan_penolakan" id="r_alasan" rows="3" class="form-control" placeholder="Tuliskan alasan penolakan (misal: informasi tidak lengkap, bukan ranah hoaks kesehatan, dsb)..." required></textarea>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger rounded-pill px-4">
            <i class="ti ti-send me-1"></i> Tolak Laporan & Kirim Email
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$baseUrlVerifikasi = Url::to(['verifikasi']);
$baseUrlTolak = Url::to(['tolak']);

$jsModal = <<<JS
$(document).on('click', '.btn-approve-modal', function() {
    var id = $(this).data('id');
    var tiket = $(this).data('tiket');
    var judul = $(this).data('judul');
    var pelapor = $(this).data('pelapor');
    var penjelasan = $(this).data('penjelasan') || '';
    var url = $(this).data('url') || '';
    var status = $(this).data('status') !== undefined ? $(this).data('status') : '1';

    $('#formVerifikasi').attr('action', '{$baseUrlVerifikasi}?id=' + id);
    $('#v_no_tiket').val(tiket);
    $('#v_judul').val(judul);
    $('#v_pelapor').val(pelapor);
    $('#v_penjelasan_fakta').val(penjelasan);
    $('#v_counter_fact_urls').val(url);
    $('#v_status_hoaks').val(status);

    var modal = new bootstrap.Modal(document.getElementById('modalVerifikasi'));
    modal.show();
});

$(document).on('click', '.btn-reject-modal', function() {
    var id = $(this).data('id');
    var tiket = $(this).data('tiket');
    var judul = $(this).data('judul');

    $('#formTolak').attr('action', '{$baseUrlTolak}?id=' + id);
    $('#r_no_tiket').val(tiket);
    $('#r_judul').val(judul);
    $('#r_alasan').val('Informasi tidak lengkap atau tidak memenuhi kriteria isu hoaks kesehatan.');

    var modal = new bootstrap.Modal(document.getElementById('modalTolak'));
    modal.show();
});
JS;
$this->registerJs($jsModal);
?>
