<?php

/**
 * Client object
 *
 * @package    RpkUtils\Sysinfo
 * @author     Repkit <repkit@gmail.com>
 * @copyright  2015 Repkit
 * @license    MIT <http://opensource.org/licenses/MIT>
 * @since      2015-11-12
 */
 
 namespace RpkUtils\Sysinfo;
 
 class Client
 {
    /**
     * Client ip
     * @return string
     */
    public static function ip()
    {
        if(!empty($_SERVER['REMOTE_ADDR'])){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            /*dunno secure alternative as tring to get from other $_SERVER values
            generate security problems 
            @link: http://roshanbh.com.np/2007/12/getting-real-ip-address-in-php.html*/
            $ip = '0.0.0.0';
        }
        
        return $ip;
    } 
 }