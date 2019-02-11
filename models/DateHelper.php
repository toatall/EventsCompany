<?php
namespace app\models;

use yii\db\Expression;

/**
 * Работа с датами
 * @author toatall
 *
 */
class DateHelper
{
    /**
     * Получение наименование месяца по его индексу
     * @param int $index
     * @return NULL|string
     */
    public static function monthByIndex($index)
    {
        $months = [
            '01' => 'января',
            '02' => 'февраля',
            '03' => 'марта',
            '04' => 'апреля',
            '05' => 'мая',
            '06' => 'июня',
            '07' => 'июля',
            '08' => 'августа',
            '09' => 'сентября',
            '10' => 'октября',
            '11' => 'ноября',
            '12' => 'декабря',
        ];
        
        return (isset($months[$index])) ? $months[$index] : null;
    }
    
    /**
     * Convert date and time for view
     * @param string $date
     * @param boolean $onlyDate
     * @return NULL|string
     */
    public static function readDateTime($date, $onlyDate = false)
    {
        if ($date == null)
            return null;
        
        return ($onlyDate) ? \Yii::$app->formatter->asDate($date)
            : \Yii::$app->formatter->asDatetime($date);        
    }
    
    /**
     * Return sql expression for current date and time
     * @return string
     */
    public static function currentDateTime()
    {
        if (\Yii::$app->db->driverName == 'mysql')
        {
            return new Expression('NOW()');
        }
        elseif (\Yii::$app->db->driverName == 'sqlsrv')
        {
            return new Expression('GETDATE()');
        }
        return null;
    }
    
    /**
     * Convert date and time for save in db
     * @param string $date
     * @param boolean $onlyDate
     * @return NULL|string
     */
    public static function writeDateTime($date, $onlyDate = false)
    {
        if ($date==null)
            return null;
        return ($onlyDate) ? \Yii::$app->formatter->asDatetime(strtotime($date), 'yyyy-MM-dd hh:mm:ss')
            : \Yii::$app->formatter->asDate(strtotime($date), 'yyyy-MM-dd');        
    }
    
}