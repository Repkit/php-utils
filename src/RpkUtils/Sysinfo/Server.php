<?php

/**
 * Server object
 *
 * @package    RpkUtils\Sysinfo
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
    
    /**
     * Server uptime
     * @return float seconds
     */
    public static function uptime()
    {
        $uptime = -1;
        if(false !== ($data = @file("/proc/uptime"))){
            $data = explode(' ',reset($data));
            $uptime = reset($data);
        }
        
        return floatval($uptime);
    }
    
    /**
     * Server cpu
     * @return \StdClass
     */
    public static function cpu()
    {
        $cpu = new \StdClass();
        
        if(false !== ($data = @file('/proc/cpuinfo'))){
            
            $data = implode("", $data);
            
            // processing aka pre matching data
            @preg_match_all('/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s', $data, $model);
            @preg_match_all('/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/', $data, $mhz);
            @preg_match_all('/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/', $data, $cache);
            @preg_match_all('/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/', $data, $bogomips);
            
        }
        
        if (false !== is_array($model[1])){
            
            $cpu->num = sizeof($model[1]);
            $x1 = '';
    		if($cpu->num != 1){
    		    $x1 = ' ×'.$cpu->num;
    		}
    		
    		$cpu->frequency = $mhz[1][0];
    		$cpu->cache2    = $cache[1][0];
    		$cpu->bogomips  = $bogomips[1][0];
    		$cpu->model     = $model[1][0];
    		$cpu->summary   = 'Model: '.$model[1][0].' | Frequency: '.$mhz[1][0].' | Secondary cache: '.$cache[1][0].' | Bogomips: '.$bogomips[1][0].' '.$x1;
    		
    	}
    	
    	return $cpu;
    }
    
    /**
     * Server cpu cores information
     * @return array 
     */
    public static function cpuCoreInfo() 
    {
        $cores = array();
        if(false !== ($data = @file('/proc/stat'))){
            foreach($data as $line ) {
                if( preg_match('/^cpu[0-9]/', $line) ){
                    $info = explode(' ', $line);
                    $cores[]=array(
                        'user'=>$info[1],
                        'nice'=>$info[2],
                        'sys' => $info[3],
                        'idle'=>$info[4],
                        'iowait'=>$info[5],
                        'irq' => $info[6],
                        'softirq' => $info[7]
                    );
                }
            }
        }
        
        return $cores;
        
    }
    
    /**
     * Server cpu percentages
     * @param cpuCoreInfo()
     * @param cpuCoreInfo()
     * @return array
     */
    public static function cpuPercentages($cpuCoreInfo1, $cpuCoreInfo2) 
    {
        
        $cpus = array();
        foreach($cpuCoreInfo1 as $idx => $core){
            
            $dif = array();
            $cpu = array();
            
            $dif['user']    = $cpuCoreInfo2[$idx]['user'] - $cpuCoreInfo1[$idx]['user'];
            $dif['nice']    = $cpuCoreInfo2[$idx]['nice'] - $cpuCoreInfo1[$idx]['nice'];
            $dif['sys']     = $cpuCoreInfo2[$idx]['sys'] - $cpuCoreInfo1[$idx]['sys'];
            $dif['idle']    = $cpuCoreInfo2[$idx]['idle'] - $cpuCoreInfo1[$idx]['idle'];
            $dif['iowait']  = $cpuCoreInfo2[$idx]['iowait'] - $cpuCoreInfo1[$idx]['iowait'];
            $dif['irq']     = $cpuCoreInfo2[$idx]['irq'] - $cpuCoreInfo1[$idx]['irq'];
            $dif['softirq'] = $cpuCoreInfo2[$idx]['softirq'] - $cpuCoreInfo1[$idx]['softirq'];
            
            $total = array_sum($dif);
            
            foreach($dif as $x=>$y){
                $cpu[$x] = round($y / $total * 100, 2);
            } 
                
            $cpus['cpu' . $idx] = $cpu;
            
        }
        
        return $cpus;
        
    }
    
     
 }