<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Tinjau Laporan: ' . $model->no_tiket;
$this->params['breadcrumbs'][] = ['label' => 'Laporan Pengaduan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->no_tiket;
?>

<div class="laporan-pengaduan-view">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-info-circle"></i> Rincian Isu & Informasi Pelapor</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 180px;">No. Tiket</th>
                            <td><strong class="text-teal" style="font-size: 16px;"><?= Html::encode($model->no_tiket) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Judul Isu</th>
                            <td><strong><?= Html::encode($model->judul_isu) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Pelapor</th>
                            <td>
                                <strong><?= Html::encode($model->nama_pelapor) ?></strong><br>
                                <span class="text-muted"><i class="fa fa-envelope"></i> <?= Html::encode($model->email_pelapor) ?></span> | 
                                <span class="text-muted"><i class="fa fa-phone"></i> <?= Html::encode($model->telepon_pelapor ?: '-') ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Kategori Isu</th>
                            <td><span class="label label-info"><?= Html::encode(strtoupper($model->kategori_slug)) ?></span></td>
                        </tr>
                        <tr>
                            <th>Deskripsi Isu</th>
                            <td><div style="background: #f9f9f9; padding: 12px; border-radius: 6px;"><?= nl2br(Html::encode($model->deskripsi_isu)) ?></div></td>
                        </tr>
                        <tr>
                            <th>Bukti Lampiran</th>
                            <td>
                                <?php if ($model->bukti_url): ?>
                                    <a href="<?= Html::encode($model->bukti_url) ?>" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-external-link"></i> Lihat Bukti Lampiran</a>
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada lampiran link/file.</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status Verifikasi</th>
                            <td>
                                <?php
                                $badgeClass = 'label-default';
                                if ($model->status_verifikasi === 'BARU') $badgeClass = 'label-warning';
                                if ($model->status_verifikasi === 'TERVERIFIKASI') $badgeClass = 'label-success';
                                if ($model->status_verifikasi === 'DITOLAK') $badgeClass = 'label-danger';
                                ?>
                                <span class="label <?= $badgeClass ?>" style="font-size: 14px;"><?= Html::encode($model->status_verifikasi) ?></span>
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h4><i class="fa fa-edit text-teal"></i> Form Verifikasi & Penelusuran Cek Fakta</h4>
                    <?php $form = ActiveForm::begin(); ?>

                        <div class="form-group">
                            <label class="control-label">Kesimpulan Status Isu</label>
                            <select name="LaporanHoaks[status_hoaks]" class="form-control">
                                <option value="1" <?= $model->status_hoaks ? 'selected' : '' ?>>🔴 HOAKS / DISINFORMASI (Informasi Palsu/Kesesatan)</option>
                                <option value="0" <?= !$model->status_hoaks ? 'selected' : '' ?>>🟢 FAKTA / RESMI (Informasi Benar/Terverifikasi)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Hasil Penelusuran Cek Fakta (Penjelasan Resmi Kemenkes)</label>
                            <textarea name="LaporanHoaks[penjelasan_fakta]" rows="5" class="form-control" placeholder="Tuliskan uraian hasil verifikasi fakta resmi oleh tim ahli Kemenkes..."><?= Html::encode($model->penjelasan_fakta) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Link Sumber Fakta Resmi (Counter-Fact URLs)</label>
                            <input type="text" name="LaporanHoaks[counter_fact_urls]" value="<?= Html::encode($model->counter_fact_urls) ?>" class="form-control" placeholder="https://sehatnegeriku.kemkes.go.id/...">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Penelusuran Fakta</button>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-cogs"></i> Aksi Verifikasi & Notifikasi</h3>
                </div>
                <div class="panel-body">
                    <p class="text-muted">Aksi di bawah ini akan memperbarui status dan mengirimkan notifikasi email otomatis ke <strong><?= Html::encode($model->email_pelapor) ?></strong>.</p>
                    
                    <div class="well">
                        <h4>1. Setujui & Diverifikasi</h4>
                        <p class="small text-muted">Kirim hasil verifikasi fakta ke email pelapor.</p>
                        <a href="<?= Url::to(['verifikasi', 'id' => $model->id]) ?>" class="btn btn-block btn-success" onclick="return confirm('Apakah Anda yakin ingin memverifikasi laporan ini dan mengirim notifikasi email ke pelapor?');">
                            <i class="fa fa-check-circle"></i> Verifikasi & Kirim Email
                        </a>
                    </div>

                    <div class="well">
                        <h4>2. Tolak Laporan</h4>
                        <p class="small text-muted">Tolak jika laporan tidak relevan atau informasi tidak lengkap.</p>
                        <?php $formTolak = ActiveForm::begin(['action' => Url::to(['tolak', 'id' => $model->id])]); ?>
                            <div class="form-group">
                                <input type="text" name="alasan_penolakan" class="form-control input-sm" placeholder="Alasan penolakan..." required>
                            </div>
                            <button type="submit" class="btn btn-block btn-danger" onclick="return confirm('Tolak laporan ini dan kirim pemberitahuan email ke pelapor?');">
                                <i class="fa fa-times-circle"></i> Tolak & Kirim Email
                            </button>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
