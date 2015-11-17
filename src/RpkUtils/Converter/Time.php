<?php

/**
 * Time object
 *
 * @package    RpkUtils\Converter
 * @author     Repkit <repkit@gmail.com>
 * @copyright  2015 Repkit
 * @license    MIT <http://opensource.org/licenses/MIT>
 * @since      2015-11-12
 */
 
 namespace RpkUtils\Converter;
 
 class Time
 {
    /**
     * Convert seconds into human readeble time
     * @param int $seconds 
     * @return string
    */ 
    public static function sec2time($seconds) 
    {
        $sec = intval($seconds);
        $dtF = new \DateTime("@0");
        $dtT = new \DateTime("@$sec");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }
 }