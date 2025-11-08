<?php

namespace app\services;

use Yii;

class ProcessorService
{
    public function run(int $delay): void
    {
        while (true) {
            $row = Yii::$app->db->createCommand("
                WITH next AS (
                  SELECT id, user_id
                  FROM requests
                  WHERE status = 'pending'
                  ORDER BY id
                  FOR UPDATE SKIP LOCKED
                  LIMIT 1
                )
                UPDATE requests r
                SET status = 'processing', updated_at = EXTRACT(EPOCH FROM NOW())::int
                FROM next
                WHERE r.id = next.id
                RETURNING r.id, r.user_id
            ")->queryOne();

            if (!$row) return;

            $id = (int)$row['id'];
            $userId = (int)$row['user_id'];

            if ($delay > 0) sleep($delay);
            $approve = (mt_rand(1,100) <= 10);

            Yii::$app->db->createCommand('SELECT pg_advisory_lock(:key)', [':key' => $userId])->execute();
            try {
                $hasApproved = (bool)Yii::$app->db->createCommand("
                    SELECT 1 FROM requests WHERE user_id = :u AND status = 'approved' LIMIT 1
                ", [':u' => $userId])->queryScalar();

                $final = ($approve && !$hasApproved) ? 'approved' : 'declined';
                Yii::$app->db->createCommand("
                    UPDATE requests SET status = :s, updated_at = EXTRACT(EPOCH FROM NOW())::int WHERE id = :id
                ", [':s' => $final, ':id' => $id])->execute();
            } finally {
                Yii::$app->db->createCommand('SELECT pg_advisory_unlock(:key)', [':key' => $userId])->execute();
            }
        }
    }
}
