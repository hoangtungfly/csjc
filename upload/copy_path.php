<?php
error_reporting(0);
$result = array();
if(isset($_POST['from_path']) && $_POST['to_path']){
    $from = __DIR__ . trim($_POST['from_path']);
    $to = __DIR__ . trim($_POST['to_path']);
    recurse_copy($from, $to);
    if(isset($_POST['delete']) && $_POST['delete']) {
        rrmdir($from);
    }
}

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    if(!file_exists($basepath)) {
        @mkdir($dst);
    }
    
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
} 

function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") 
            rrmdir($dir."/".$object); 
         else 
             unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
} 
