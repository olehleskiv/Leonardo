<?php
  if(!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data, $file_append = false) {
      $fp = fopen($filename, (!$file_append ? 'w+' : 'a+'));
        if(!$fp) {
          trigger_error('file_put_contents cannot write in file.', E_USER_ERROR);
          return;
        }
      fputs($fp, $data);
      fclose($fp);
    }
  }
?>