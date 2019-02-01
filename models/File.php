<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ec_file".
 *
 * @property int $id
 * @property int $id_event
 * @property string $filename
 * @property string $filename_path
 * @property string $date_create
 * @property string $username
 *
 * @property EcEvent $event
 * @property EcUser $username0
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_event', 'filename', 'filename_path', 'date_create', 'username'], 'required'],
            [['id_event'], 'integer'],
            [['date_create'], 'safe'],
            [['filename', 'username'], 'string', 'max' => 250],
            [['filename_path'], 'string', 'max' => 500],
            [['id_event'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['id_event' => 'id']],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['username' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_event' => 'Id Event',
            'filename' => 'Filename',
            'filename_path' => 'Filename Path',
            'date_create' => 'Date Create',
            'username' => 'Username',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'id_event']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsername0()
    {
        return $this->hasOne(User::className(), ['username' => 'username']);
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }
}
