<?php
namespace app\models;

use app\models\userinfo\UserInfo;

/**
 * Log change
 * @author toatall
 *
 */
class LogChangeHelper
{
    /**
     * Save log information
     * @param string $log_record
     * @param string $typeOperation
     * @return string
     */
    public static function setLog($log_record, $typeOperation) {
        if ($log_record != null)
            $log_record .= '|';
            return $log_record . date('d.m.Y H:i:s') . ' ' . UserInfo::inst()->userLogin . $typeOperation;
    }
    
    
}