<?php

/**
 * Size object
 *
 * @package    RpkUtils\Converter
 * @author     Repkit <repkit@gmail.com>
 * @copyright  2015 Repkit
 * @license    MIT <http://opensource.org/licenses/MIT>
 * @since      2015-11-13
 */
 
 namespace RpkUtils\Converter;
 
 class Size
 {
    
    /**
     * Convert size into human friendly
     * @param int $size bytes 
     * @return mixt
     */
    public static function convert($size)
    {
       $unit = array('b','kb','mb','gb','tb','pb');
       return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
 }