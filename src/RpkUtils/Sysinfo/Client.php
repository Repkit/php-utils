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
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          //to check ip is pass from proxy
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
          
        } else {
            
          $ip=$_SERVER['REMOTE_ADDR'];
          
        }
        
        return $ip;
    } 