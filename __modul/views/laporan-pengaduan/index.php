<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Manajemen Pengaduan & Laporan Isu Hoaks';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="laporan-pengaduan-index">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="font-bold text-dark"><i class="fa fa-bullhorn text-teal"></i> <?= Html::encode($this->title) ?></h2>
            <p class="text-muted">Kelola pengaduan isu hoaks publik, proses verifikasi fakta, dan kirim notifikasi otomatis via email.</p>
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group" role="group">
                <a href="<?= Url::to(['index']) ?>" class="btn btn-default <?= empty($statusFilter) ? 'active' : '' ?>">Semua</a>
                <a href="<?= Url::to(['index', 'status' => 'BARU']) ?>" class="btn btn-warning <?= $statusFilter === 'BARU' ? 'active' : '' ?>">Baru</a>
                <a href="<?= Url::to(['index', 'status' => 'TERVERIFIKASI']) ?>" class="btn btn-success <?= $statusFilter === 'TERVERIFIKASI' ? 'active' : '' ?>">Terverifikasi</a>
                <a href="<?= Url::to(['index', 'status' => 'DITOLAK']) ?>" class="btn btn-danger <?= $statusFilter === 'DITOLAK' ? 'active' : '' ?>">Ditolak</a>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'no_tiket',
                        'label' => 'No. Tiket',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<strong>' . Html::encode($model->no_tiket) . '</strong>';
                        }
                    ],
                    [
                        'attribute' => 'judul_isu',
                        'label' => 'Judul Isu',
                        'value' => function ($model) {
                            return \yii\helpers\StringHelper::truncate(Html::encode($model->judul_isu), 60);
                        }
                    ],
                    [
                        'attribute' => 'nama_pelapor',
                        'label' => 'Pelapor & Email',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::encode($model->nama_pelapor) . '<br><small class="text-muted">' . Html::encode($model->email_pelapor) . '</small>';
                        }
                    ],
                    [
                        'attribute' => 'kategori_slug',
                        'label' => 'Kategori',
                        'value' => function ($model) {
                            return strtoupper($model->kategori_slug ?? 'Lain-lain');
                        }
                    ],
                    [
                        'attribute' => 'status_verifikasi',
                        'label' => 'Status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $badgeClass = 'label-default';
                            if ($model->status_verifikasi === 'BARU') $badgeClass = 'label-warning';
                            if ($model->status_verifikasi === 'TERVERIFIKASI') $badgeClass = 'label-success';
                            if ($model->status_verifikasi === 'DITOLAK') $badgeClass = 'label-danger';
                            return '<span class="label ' . $badgeClass . '">' . Html::encode($model->status_verifikasi) . '</span>';
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Tgl Laporan',
                        'value' => function ($model) {
                            return date('d M Y H:i', strtotime($model->created_at));
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fa fa-eye"></i> Tinjau', ['view', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-info',
                                ]);
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
