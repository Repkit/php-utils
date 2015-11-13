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
             if('/' == DIRECTORY_SEPARATOR ){
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
            $data   = explode(' ',reset($data));
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
                        'user'      => $info[1],
                        'nice'      => $info[2],
                        'sys'       => $info[3],
                        'idle'      => $info[4],
                        'iowait'    => $info[5],
                        'irq'       => $info[6],
                        'softirq'   => $info[7]
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
    
    /**
     * Server hdd information
     * @return \StdClass
     *          ->total
     *          ->free
     */
    public static function hdd() 
    {
        $hdd        = new \StdClass();
        $hdd->total = @disk_total_space(".");
        $hdd->free  = @disk_free_space(".");
        
        return $hdd;
        
    }
    
    /**
     * Server memory information
     * @return \StdClass 
     *              ->total
     *              ->used
     *              ->free
     *              ->cached
     *              ->buffers
     *              ->real->used
     *              ->real->free
     *              ->swapo->used
     *              ->swapo->free
     * @important: all data is in bytes
     */
    public static function memory() 
    {
        $memory         = new \StdClass();
        $memory->real   = new \StdClass();
        $memory->swap   = new \StdClass();
        
        if(false !== ($data = @file('/proc/meminfo'))){
            
            $data = implode("", $data);
            
            //processing and stuff aka preg matching
            preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $data, $meminfo);
    	    preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $data, $buffers);
    	    
    	    $memory->total          = $meminfo[1][0]*1024;
    	    $memory->free           = $meminfo[2][0]*1024;
    	    $memory->used           = $memory->total - $memory->free;
	        $memory->cached         = $meminfo[3][0]*1024;
    	    $memory->buffers        = $buffers[1][0]*1024;
    	    
    	    $memory->real->used     = $memory->total - $memory->free - $memory->cached - $memory->buffers;
    	    $memory->real->free     = $memory->total - $memory->real->used;
    	    
    	    $memory->swap->free     = $meminfo[5][0]*1024;
    	    $memory->swap->used     = $meminfo[4][0]*1024 - $memory->swap->free;
    	    
        }

        return $memory;
        
    }
    
    /**
     * Server average load info
     * @return \StdClass
     *              ->min1
     *              ->min5
     *              ->min15
     *              ->running
     *              ->exists
     *              ->recentPID
     */ 
    public static function avgload()
    {
        $avgload = new \StdClass();
        if(false !== ($data = @file('/proc/loadavg'))){
            $data               = explode(" ", implode("", $data));
            $data               = array_chunk($data, 4);
            $avgload->min1      = $data[0][0];
            $avgload->min5      = $data[0][1];
            $avgload->min15     = $data[0][2];
            $fourth             = explode('/',$data[0][3]);
            $avgload->running   = $fourth[0];
            $avgload->exists    = $fourth[1];
            $avgload->recentPID = $data[1][0];
        }
        
        return $avgload;
        
    }
    
    public static function network()
    {
        $network = array();
        if (false !== ($data = @file("/proc/net/dev"))){
            //first two fields are headers :)
            for ($i = 2; $i < count($data); $i++ ){
                //processing aka preg matching
                preg_match_all("/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $data[$i], $netinfo);
                $network[$i]['name']    = $netinfo[1][0];
                $network[$i]['in']      = $netinfo[2][0];
                $network[$i]['out']     = $netinfo[10][0];
            }
        }
        
        return $network;
    }
    
     
 }