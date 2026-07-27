<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\LaporanHoaks;

/**
 * ApiPengaduanController
 * 
 * Endpoint REST API publik untuk pengajuan laporan hoaks & pelacakan status oleh masyarakat.
 */
class ApiPengaduanController extends Controller
{
    public $enableCsrfValidation = false;

    public function isActionPublic($actionId)
    {
        return in_array($actionId, ['submit', 'lacak', 'ping'], true);
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Header CORS
        Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        if (Yii::$app->request->isOptions) {
            Yii::$app->end();
        }

        return parent::beforeAction($action);
    }

    public function actionPing()
    {
        return [
            'success' => true,
            'message' => 'API Service Pengaduan Isu Hoaks SIPKK Kemenkes Aktif',
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * POST /api-pengaduan/submit
     * Menerima kiriman formulir laporan masyarakat dari Next.js frontend
     */
    public function actionSubmit()
    {
        $request = Yii::$app->request;
        $params = $request->getBodyParams();

        // Fallback ke post biasa jika x-www-form-urlencoded
        if (empty($params)) {
            $params = $request->post();
        }

        $model = new LaporanHoaks();
        $model->nama_pelapor = $params['nama_pelapor'] ?? '';
        $model->email_pelapor = $params['email_pelapor'] ?? '';
        $model->telepon_pelapor = $params['telepon_pelapor'] ?? '';
        $model->judul_isu = $params['judul_isu'] ?? '';
        $model->kategori_slug = $params['kategori_slug'] ?? 'pengaduan-masyarakat';
        $model->deskripsi_isu = $params['deskripsi_isu'] ?? '';
        $model->bukti_url = $params['bukti_url'] ?? '';

        if (!$model->validate()) {
            return [
                'success' => false,
                'message' => 'Data formulir tidak valid.',
                'errors' => $model->getErrors(),
            ];
        }

        if ($model->save()) {
            // Trigger SMTP Mail Notification
            $emailSent = $model->sendEmailNotification('SUBMITTED');

            return [
                'success' => true,
                'message' => 'Laporan isu hoaks berhasil dikirim dan masuk ke antrean verifikasi tim Kemenkes RI.',
                'data' => [
                    'id' => $model->id,
                    'no_tiket' => $model->no_tiket,
                    'judul_isu' => $model->judul_isu,
                    'status_verifikasi' => $model->status_verifikasi,
                    'created_at' => $model->created_at,
                    'email_notification_sent' => $emailSent,
                ],
            ];
        }

        return [
            'success' => false,
            'message' => 'Gagal menyimpan data laporan.',
            'errors' => $model->getErrors(),
        ];
    }

    /**
     * GET /api-pengaduan/lacak?tiket=HOAX-2026-XXXXX
     * Melacak status laporan publik berdasarkan nomor tiket
     */
    public function actionLacak()
    {
        $tiket = Yii::$app->request->get('tiket', '');
        if (empty($tiket)) {
            return [
                'success' => false,
                'message' => 'Nomor tiket wajib diisi.',
            ];
        }

        $laporan = LaporanHoaks::findOne(['no_tiket' => trim($tiket)]);
        if (!$laporan) {
            return [
                'success' => false,
                'message' => 'Nomor tiket pengaduan tidak ditemukan.',
            ];
        }

        return [
            'success' => true,
            'data' => [
                'no_tiket' => $laporan->no_tiket,
                'judul_isu' => $laporan->judul_isu,
                'kategori_slug' => $laporan->kategori_slug,
                'status_verifikasi' => $laporan->status_verifikasi,
                'status_hoaks' => $laporan->status_hoaks ? 'DISINFORMASI / HOAKS' : 'FAKTA / RESMI',
                'penjelasan_fakta' => $laporan->penjelasan_fakta,
                'created_at' => $laporan->created_at,
                'verified_at' => $laporan->verified_at,
            ],
        ];
    }
}
