<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ec_organization".
 *
 * @property string $code
 * @property string $name
 * @property string $description
 *
 * @property EcEvent[] $ecEvents
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['description'], 'string'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 150],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'name' => 'Наименование',
            'description' => 'Описание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['org_code' => 'code']);
    }

    /**
     * {@inheritdoc}
     * @return OrganizationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrganizationQuery(get_called_class());
    }
    
    /**
     * Return name organization with code
     * @property string full
     * @return string
     */
    public function getFull()
    {
        return $this->name . " ({$this->code})";
    }
    
    /**
     * Return all organizaions
     * @return array
     */
    public static function allOrganizaions()
    {
        $query = self::find()->all();
        return ArrayHelper::map($query, 'code', 'full');  
    }
    
}
