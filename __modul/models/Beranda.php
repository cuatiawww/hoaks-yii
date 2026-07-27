<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Beranda extends ActiveRecord
{
    /**
     * Tidak memerlukan tabel khusus, hanya untuk query data beranda
     */
    public static function tableName()
    {
        return '{{%user}}'; // dummy table
    }

    public static function getStats()
    {
        try {
            $stats = [];

            // Total Active Users
            $stats['total_users'] = (int) Yii::$app->db->createCommand(
                'SELECT COUNT(*) FROM public."user" WHERE is_active = true'
            )->queryScalar();

            $stats['total_pending_registrations'] = 0;

            return $stats;
        } catch (\Exception $e) {
            Yii::error('Error getting beranda stats: ' . $e->getMessage());
            return [
                'total_users' => 0,
                'total_pending_registrations' => 0,
            ];
        }
    }
}
