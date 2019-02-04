<?php
namespace app\models;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class DateBehavior extends AttributeBehavior
{
    
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'createUpdate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'createUpdate',
        ];        
    }
    
    public function createUpdate($event)
    {
        if ($this->value != null)
        {
            var_dump($event); exit;
        }
    }
    
}