<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Request extends ActiveRecord
{
    public static function tableName(): string { return '{{%requests}}'; }

    public function behaviors(): array { return [TimestampBehavior::class]; }

    public function rules(): array
    {
        return [
            [['user_id','amount','term'], 'required'],
            [['user_id','amount','term'], 'integer', 'min' => 1],
            ['status', 'in', 'range' => ['pending','processing','approved','declined']],
            ['status', 'default', 'value' => 'pending'],
            ['user_id', 'validateNoApproved', 'on' => ['default','insert']],
        ];
    }

    public function validateNoApproved($attribute)
    {
        $exists = static::find()->where(['user_id' => (int)$this->user_id, 'status' => 'approved'])->exists();
        if ($exists) $this->addError($attribute, 'User already has an approved request.');
    }
}
