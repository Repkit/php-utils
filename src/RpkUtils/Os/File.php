<?php

/**
 * File object
 *
 * @package    RpkUtils\Os
 * @author     Repkit <repkit@gmail.com>
 * @copyright  2015 Repkit
 * @license    MIT <http://opensource.org/licenses/MIT>
 * @since      2015-11-12
 */
 
 namespace RpkUtils\Os;
 
 class File
 {
     /**
      * Create file
      * @param string $dir full path and file name 
      *         example /home/coco/readme.md
      * @return bool
     */
    public static function create($dir) 
    {
        //create dir structure if necesary
        $parts = explode(DIRECTORY_SEPARATOR, $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach($parts as $part)
            if(!is_dir($dir .= DIRECTORY_SEPARATOR.$part)) mkdir($dir);
    
        //create file
        $handle = fopen($dir.DIRECTORY_SEPARATOR.$file, 'cb');
        fclose($handle);
    
        return true;
    }
 }