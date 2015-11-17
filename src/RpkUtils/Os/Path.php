<?php

/**
 * Path object
 *
 * @package    RpkUtils\Os
 * @author     Repkit <repkit@gmail.com>
 * @copyright  2015 Repkit
 * @license    MIT <http://opensource.org/licenses/MIT>
 * @since      2015-11-12
 */
 
 namespace RpkUtils\Os;
 
 class Path
 {
     /**
      * List files from a path based filtered by extensions
      * @param string $path path to scan
      * @param option array $extensions a list o extensions that will filter the file's list
      *         example array( 'jpg', 'png' ,'txt' )
      * @return array of SplFileInfo
     */
    public static function files($path, array $extensions = array()) 
    {
        $files = array();
        $it = new \RecursiveDirectoryIterator($path);
        $filter = false;
        if(!empty($extensions) && is_array($extensions)){
            $filter = true;    
        }
        foreach(new \RecursiveIteratorIterator($it) as $file){
            if($filter){
                $f = explode('.', $file);
            	$ext = strtolower(array_pop($f));
                if (in_array($ext, $extensions)){
                // 	$files[] = $file->__toString();
                    $files[] = $file;
                }
            }else{
                $files[] = $file;
            }
        }
        
        return $files;
    }
 }