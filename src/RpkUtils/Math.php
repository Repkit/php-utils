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
      * @param number $value the value for which we want to calc the percent
      * @param number $total the total from which we want to calc the percent value 
      * @return float
      */ 
    public static function percentage($value, $from) 
    {
        $value = floatval($value);
        $from = floatval($from);
        return floatval($value/$from*100);
    }
 }