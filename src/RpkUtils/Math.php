<?php

/**
 * Math object
 *
 * @package    RpkUtils
 * @author     Repkit <repkit@gmail.com>
 * @copyright  2015 Repkit
 * @license    MIT <http://opensource.org/licenses/MIT>
 * @since      2015-11-12
 */
 
 namespace RpkUtils;
 
 class Math
 {
     /**
      * Calculate percentage of a value from a total
      * @param $value the value for which we want to calc the percent
      * @param $total the total from which we want to calc the percent value 
      * @return float
      */ 
    public static function percentage($value, $from) 
    {
        return floatval($value/$from*100);
    }
 }