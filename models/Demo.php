<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test".
 *
 * @property int $id
 * @property string $title
 * @property int $value
 * @property int $userId
 */
class Demo extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'value', 'userId'], 'required'],
            [['value', 'userId'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'value' => 'Value',
            'userId' => 'User ID',
        ];
    }
}
