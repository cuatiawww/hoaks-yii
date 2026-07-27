<?php

namespace app\services;

use Yii;
use app\models\User;

class EmailService
{
    /**
     * Mengirim notifikasi email untuk akun yang baru dibuat oleh Admin.
     */
    public static function sendAdminCreatedAccount(User $user, string $plainPassword): bool
    {
        $email = $user->email;
        if (empty($email)) {
            return false;
        }

        try {
            $appName = Yii::$app->name;
            $loginUrl = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);

            $subject = "[$appName] Akun Anda Telah Dibuat";
            $body = "
                <h3>Halo, {$user->nama_lengkap}</h3>
                <p>Akun Anda pada sistem <strong>{$appName}</strong> telah berhasil dibuat oleh Administrator.</p>
                <p>Berikut adalah kredensial login Anda:</p>
                <table cellpadding='5' style='border-collapse: collapse;'>
                    <tr>
                        <td><strong>Username:</strong></td>
                        <td>{$user->username}</td>
                    </tr>
                    <tr>
                        <td><strong>Password:</strong></td>
                        <td>{$plainPassword}</td>
                    </tr>
                </table>
                <br>
                <p>Silakan masuk melalui tautan berikut:</p>
                <p><a href='{$loginUrl}' style='display:inline-block; padding: 10px 20px; background-color: #229799; color: #fff; text-decoration: none; border-radius: 4px;'>Masuk ke Sistem</a></p>
                <br>
                <hr>
                <p style='font-size:0.85em; color:#777;'>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            ";

            return Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([($_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@kemenkes.go.id') => ($_ENV['MAIL_FROM_NAME'] ?? $appName)])
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();
        } catch (\Throwable $e) {
            Yii::error('Gagal mengirim email pembuatan akun: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
