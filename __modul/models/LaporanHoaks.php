<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Model LaporanHoaks
 *
 * @property int $id
 * @property string $no_tiket
 * @property string $nama_pelapor
 * @property string $email_pelapor
 * @property string|null $telepon_pelapor
 * @property string $judul_isu
 * @property string $kategori_slug
 * @property string $deskripsi_isu
 * @property string|null $bukti_url
 * @property string $status_verifikasi
 * @property bool $status_hoaks
 * @property string|null $penjelasan_fakta
 * @property string|null $counter_fact_urls
 * @property string|null $alasan_penolakan
 * @property int|null $verified_by
 * @property string|null $verified_at
 * @property string $created_at
 * @property string $updated_at
 */
class LaporanHoaks extends ActiveRecord
{
    const STATUS_BARU = 'BARU';
    const STATUS_DIPROSES = 'DIPROSES';
    const STATUS_TERVERIFIKASI = 'TERVERIFIKASI';
    const STATUS_DITOLAK = 'DITOLAK';
    const STATUS_DITERBITKAN = 'DITERBITKAN';

    public static function tableName()
    {
        return 'tbl_laporan_hoaks';
    }

    public function rules()
    {
        return [
            [['nama_pelapor', 'email_pelapor', 'judul_isu', 'deskripsi_isu'], 'required'],
            [['email_pelapor'], 'email'],
            [['deskripsi_isu', 'bukti_url', 'penjelasan_fakta', 'counter_fact_urls', 'alasan_penolakan'], 'string'],
            [['status_hoaks'], 'boolean'],
            [['verified_by'], 'integer'],
            [['verified_at', 'created_at', 'updated_at'], 'safe'],
            [['no_tiket', 'kategori_slug', 'status_verifikasi'], 'string', 'max' => 100],
            [['nama_pelapor', 'email_pelapor', 'judul_isu'], 'string', 'max' => 255],
            [['telepon_pelapor'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_tiket' => 'No. Tiket',
            'nama_pelapor' => 'Nama Pelapor',
            'email_pelapor' => 'Email Pelapor',
            'telepon_pelapor' => 'No. Telepon / WhatsApp',
            'judul_isu' => 'Judul Isu Hoaks',
            'kategori_slug' => 'Kategori Isu',
            'deskripsi_isu' => 'Deskripsi Isu',
            'bukti_url' => 'Link / File Lampiran Bukti',
            'status_verifikasi' => 'Status Verifikasi',
            'status_hoaks' => 'Status Hoaks / Fakta',
            'penjelasan_fakta' => 'Hasil Penelusuran Cek Fakta',
            'counter_fact_urls' => 'Link Sumber Fakta Resmi',
            'alasan_penolakan' => 'Alasan Penolakan',
            'verified_by' => 'Diverifikasi Oleh',
            'verified_at' => 'Waktu Verifikasi',
            'created_at' => 'Waktu Pelaporan',
            'updated_at' => 'Terakhir Diubah',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $now = date('Y-m-d H:i:s');
        if ($insert) {
            $this->created_at = $now;
            if (empty($this->status_verifikasi)) {
                $this->status_verifikasi = self::STATUS_BARU;
            }
            if (empty($this->no_tiket)) {
                $this->no_tiket = $this->generateNoTiket();
            }
        }
        $this->updated_at = $now;

        return true;
    }

    /**
     * Generate Nomor Tiket Pengaduan Unik (Format: HOAX-YYYY-XXXXX)
     */
    public function generateNoTiket()
    {
        $prefix = 'HOAX-' . date('Y') . '-';
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5));
        return $prefix . $random;
    }

    /**
     * Pengiriman Email Notifikasi via SMTP Gmail (SIPKK Notification)
     * 
     * @param string $type 'SUBMITTED' | 'VERIFIED' | 'REJECTED'
     * @return bool
     */
    public function sendEmailNotification($type = 'SUBMITTED')
    {
        if (empty($this->email_pelapor)) {
            return false;
        }

        try {
            $fromEmail = $_ENV['MAIL_FROM_ADDRESS'] ?? 'yosuaelwistio@gmail.com';
            $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'SIPKK Notification';

            $subject = '';
            $bodyHtml = '';

            if ($type === 'SUBMITTED') {
                $subject = '[SIPKK Cek Fakta] Laporan Isu Hoaks Diterima - Tiket: ' . $this->no_tiket;
                $bodyHtml = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                        <h2 style='color: #07877c;'>Kementerian Kesehatan Republik Indonesia</h2>
                        <h3 style='color: #07877c;'>Konfirmasi Tanda Terima Laporan Isu Hoaks</h3>
                        <p>Yth. <strong>" . htmlspecialchars($this->nama_pelapor) . "</strong>,</p>
                        <p>Terima kasih telah melaporkan dugaan isu hoaks kesehatan kepada tim verifikator kami.</p>
                        <table style='width: 100%; border-collapse: collapse; margin: 15px 0;'>
                            <tr><td style='padding: 8px; border-bottom: 1px solid #eee; width: 140px;'><strong>No. Tiket:</strong></td><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>" . htmlspecialchars($this->no_tiket) . "</strong></td></tr>
                            <tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Judul Isu:</strong></td><td style='padding: 8px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($this->judul_isu) . "</td></tr>
                            <tr><td style='padding: 8px; border-bottom: 1px solid #eee;'><strong>Status:</strong></td><td style='padding: 8px; border-bottom: 1px solid #eee;'><span style='background: #e6f4f1; color: #07877c; padding: 4px 10px; border-radius: 12px; font-weight: bold;'>Dalam Antrean Verifikasi</span></td></tr>
                        </table>
                        <p>Tim ahli kami sedang menelusuri kebenaran informasi tersebut. Hasil verifikasi akan diinformasikan kembali melalui email ini.</p>
                        <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;' />
                        <p style='font-size: 12px; color: #888;'>Pesan ini dikirim otomatis oleh Sistem Informasi Penanganan Kategori Kesehatan (SIPKK Kemenkes RI).</p>
                    </div>
                ";
            } elseif ($type === 'VERIFIED') {
                $statusLabel = $this->status_hoaks ? 'DISINFORMASI / HOAKS' : 'FAKTA / RESMI';
                $statusColor = $this->status_hoaks ? '#d9534f' : '#5cb85c';
                $pubUrlHtml = !empty($this->counter_fact_urls)
                    ? "<p style='margin: 15px 0 5px 0;'><strong>Link URL Berita / Artikel Cek Fakta Resmi:</strong></p><p style='margin: 0;'><a href='" . htmlspecialchars($this->counter_fact_urls) . "' target='_blank' style='color: #07877c; font-weight: bold; word-break: break-all;'>" . htmlspecialchars($this->counter_fact_urls) . "</a></p>"
                    : '';

                $subject = '[SIPKK Cek Fakta] Hasil Verifikasi Laporan - Tiket: ' . $this->no_tiket;
                $bodyHtml = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                        <h2 style='color: #07877c;'>Kementerian Kesehatan Republik Indonesia</h2>
                        <h3 style='color: #07877c;'>Hasil Penelusuran Cek Fakta Kesehatan</h3>
                        <p>Yth. <strong>" . htmlspecialchars($this->nama_pelapor) . "</strong>,</p>
                        <p>Laporan isu hoaks Anda dengan No. Tiket <strong>" . htmlspecialchars($this->no_tiket) . "</strong> telah selesai diverifikasi oleh tim ahli kami.</p>
                        <div style='background: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 4px solid " . $statusColor . "; margin: 15px 0;'>
                            <p style='margin: 0 0 10px 0;'><strong>Kesimpulan Verifikasi:</strong> <span style='color: " . $statusColor . "; font-weight: bold;'>" . $statusLabel . "</span></p>
                            <p style='margin: 0 0 10px 0;'><strong>Penjelasan Fakta:</strong></p>
                            <p style='margin: 0; line-height: 1.6;'>" . nl2br(htmlspecialchars($this->penjelasan_fakta ?? 'Telah diverifikasi sesuai standar cek fakta resmi Kemenkes.')) . "</p>
                            " . $pubUrlHtml . "
                        </div>
                        <p>Terima kasih atas peran aktif Anda dalam mencegah penyebaran disinformasi kesehatan di masyarakat.</p>
                        <hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;' />
                        <p style='font-size: 12px; color: #888;'>Pesan ini dikirim otomatis oleh Sistem Informasi Penanganan Kategori Kesehatan (SIPKK Kemenkes RI).</p>
                    </div>
                ";
            } elseif ($type === 'REJECTED') {
                $subject = '[SIPKK Cek Fakta] Pemberitahuan Laporan - Tiket: ' . $this->no_tiket;
                $bodyHtml = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                        <h2 style='color: #07877c;'>Kementerian Kesehatan Republik Indonesia</h2>
                        <h3>Pemberitahuan Laporan Isu Kesehatan</h3>
                        <p>Yth. <strong>" . htmlspecialchars($this->nama_pelapor) . "</strong>,</p>
                        <p>Laporan Anda dengan No. Tiket <strong>" . htmlspecialchars($this->no_tiket) . "</strong> (" . htmlspecialchars($this->judul_isu) . ") tidak dapat diproses lebih lanjut.</p>
                        <p><strong>Alasan:</strong> " . htmlspecialchars($this->alasan_penolakan ?? 'Informasi tidak lengkap atau tidak memenuhi kriteria penelusuran fakta.') . "</p>
                        <p>Terima kasih atas perhatian Anda.</p>
                    </div>
                ";
            }

            return Yii::$app->mailer->compose()
                ->setFrom([$fromEmail => $fromName])
                ->setTo($this->email_pelapor)
                ->setSubject($subject)
                ->setHtmlBody($bodyHtml)
                ->send();
        } catch (\Exception $e) {
            Yii::error('Gagal mengirim email notifikasi pelaporan: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
