<?php

/**
 * Server object
 *
 * @package    Server
 * @author     Repkit <repkit@gmail.com>
 * @copyright  2015 Repkit
 * @license    MIT <http://opensource.org/licenses/MIT>
 * @since      2015-11-12
 */
 
 namespace RpkUtils\Sysinfo;
 
 class Server
 {
    
    /**
     * Server name
     * @return string
     */
    public static function name()
    {
        if(!empty($_SERVER['SERVER_NAME'])){
            $name = $_SERVER['SERVER_NAME'];
        }else{
            $name = @gethostname();
        }
        
        return $name;
    }
    
    
    /**
     * Server ip
     * @return string
     */
    public static function ip()
    {
        if(!empty($_SERVER['SERVER_ADDR'])){
            $ip = $_SERVER['SERVER_ADDR'];
        }else{
            $ip = @gethostbyname(static::name());
        }
        
        return $ip;
    }
    
    /**
     * Server os
     * @param bool $Detalied
     * @return string
     */
    public static function os($Detalied = false)
    {
        //Kernel version: if('/'==DIRECTORY_SEPARATOR){echo $os[2];}else{echo $os[1];}
        if($Detalied){
            $os = @php_uname();
        }else{
            $os = PHP_OS;
        }
        
        return $os;
    }
    
    /**
     * Server kernel
     * @return string
     */
    public static function kernel()
    {
        if($os = static::os(true)){
            $os = explode(' ', $os);
            if('/' == DIRECTORY_SEPARATOR){
                $kernel = $os[2];
            }else{
                $kernel = $os[1];
            }
        }else{
            $kernel = '';
        }
        
        return $kernel;
    }
    
    /**
     * Server hostname
     * @return string
     */
    public static function hostname()
    {
        if($os = static::os(true)){
            $os = explode(' ', $os);
             if('/'==DIRECTORY_SEPARATOR ){
                $hostname = $os[1];
            }else{
                $hostname = $os[2];
            }
        }else{
            $hostname = '';
        }
        
        return $hostname;
    }
    
    /**
     * Server software
     * @return string
     */
    public static function software()
    {
        if(!empty($_SERVER['SERVER_SOFTWARE'])){
            $software = $_SERVER['SERVER_SOFTWARE'];
        }else{
            $software = '';
        }
        
        return $software;
    }
    
    /**
     * Server language
     * @return string
     */
    public static function language()
    {
        if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }else{
            $language = '';
        }
        
        return $language;
    }
    
    /**
     * Server port
     * @return string
     */
    public static function port()
    {
        if(!empty($_SERVER['SERVER_PORT'])){
            $port = $_SERVER['SERVER_PORT'];
        }else{
            $port = '';
        }
        
        return $port;
    }
    
    /**
     * Server document root
     * @return string
     */
    public static function docroot()
    {
        if(!empty($_SERVER['DOCUMENT_ROOT'])){
            $docroot = str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']);
        }else{
            $docroot = str_replace('\\','/',dirname(__FILE__));
        }
        
        return $docroot;
    }
    
    /**
     * Server admin email
     * @return string
     */
    public static function adminmail()
    {
        if(!empty($_SERVER['SERVER_ADMIN'])){
            $adminmail = $_SERVER['SERVER_ADMIN'];
        }else{
            $adminmail = '';
        }
        
        return $adminmail;
    }
    
     
 }