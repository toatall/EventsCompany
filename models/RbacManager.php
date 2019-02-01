<?php
namespace app\models;

use yii\rbac\PhpManager;

class RbacManager extends PhpManager
{
    
    /**
     * Проверка роли из БД
     * {@inheritDoc}
     * @see \yii\rbac\PhpManager::checkAccess()
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        return User::find()
            ->where('username=:id and rolename=:role', [':id'=>$userId, ':role'=>$permissionName])
            ->exists();
    }
    
}