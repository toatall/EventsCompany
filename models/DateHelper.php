<?php
namespace app\models;

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
     * Convert format date
     * @param string $date
     * @param boolean $onlyDate
     * @return NULL|string
     */
    public static function formatDateTime($date, $onlyDate = false)
    {
        if ($date == null)
            return null;
            return date('d.m.Y' . ($onlyDate ? '' : ' H:i:s'), strtotime($date));
    }
    
}