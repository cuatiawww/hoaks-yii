<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController;
use app\models\LaporanHoaks;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * LaporanPengaduanController
 * 
 * Modul Manajemen Admin untuk Memproses, Memverifikasi, & Menindaklanjuti Laporan Isu Hoaks Publik.
 */
class LaporanPengaduanController extends BaseController
{
    public function actionIndex()
    {
        $query = LaporanHoaks::find()->orderBy(['created_at' => SORT_DESC]);

        $statusFilter = Yii::$app->request->get('status');
        if (!empty($statusFilter)) {
            $query->andWhere(['status_verifikasi' => $statusFilter]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('LaporanHoaks');
            if (!empty($post)) {
                $model->penjelasan_fakta = $post['penjelasan_fakta'] ?? $model->penjelasan_fakta;
                $model->status_hoaks = isset($post['status_hoaks']) ? (bool)$post['status_hoaks'] : $model->status_hoaks;
                $model->counter_fact_urls = $post['counter_fact_urls'] ?? $model->counter_fact_urls;
                
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Penjelasan fakta & status hoaks berhasil diperbarui.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Verifikasi & Setujui Laporan Isu -> Kirim Email Notifikasi ke Pelapor
     */
    public function actionVerifikasi($id)
    {
        $model = $this->findModel($id);
        $model->status_verifikasi = LaporanHoaks::STATUS_TERVERIFIKASI;
        $model->verified_at = date('Y-m-d H:i:s');
        $model->verified_by = Yii::$app->user->id ?? 1;

        if ($model->save()) {
            // Send SMTP Email Notification to reporter
            $emailSent = $model->sendEmailNotification('VERIFIED');
            if ($emailSent) {
                Yii::$app->session->setFlash('success', 'Laporan berhasil diverifikasi dan email notifikasi hasil verifikasi telah terkirim ke ' . htmlspecialchars($model->email_pelapor) . '.');
            } else {
                Yii::$app->session->setFlash('warning', 'Laporan diverifikasi, namun terjadi kendala saat mengirim email notifikasi SMTP.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Gagal memverifikasi laporan.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Tolak Laporan -> Kirim Email Alasan Penolakan ke Pelapor
     */
    public function actionTolak($id)
    {
        $model = $this->findModel($id);
        $alasan = Yii::$app->request->post('alasan_penolakan', 'Informasi yang disampaikan kurang lengkap atau tidak memenuhi kriteria isu hoaks kesehatan.');

        $model->status_verifikasi = LaporanHoaks::STATUS_DITOLAK;
        $model->alasan_penolakan = $alasan;
        $model->verified_at = date('Y-m-d H:i:s');
        $model->verified_by = Yii::$app->user->id ?? 1;

        if ($model->save()) {
            $model->sendEmailNotification('REJECTED');
            Yii::$app->session->setFlash('info', 'Laporan telah ditolak dan email pemberitahuan telah dikirim ke pelapor.');
        } else {
            Yii::$app->session->setFlash('error', 'Gagal menolak laporan.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    protected function findModel($id)
    {
        if (($model = LaporanHoaks::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Halaman atau data laporan yang Anda cari tidak ditemukan.');
    }
}
