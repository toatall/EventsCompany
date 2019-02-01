<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ec_user".
 *
 * @property string $username
 * @property string $userfio
 * @property string $rolename
 * @property string $date_create
 * @property string $date_update
 *
 * @property EcEvent[] $ecEvents
 * @property EcFile[] $ecFiles
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'date_create'], 'required'],
            [['date_create', 'date_update'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['userfio'], 'string', 'max' => 500],
            [['rolename'], 'string', 'max' => 30],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'userfio' => 'Userfio',
            'rolename' => 'Rolename',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcEvents()
    {
        return $this->hasMany(Event::className(), ['username' => 'username']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcFiles()
    {
        return $this->hasMany(File::className(), ['username' => 'username']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
