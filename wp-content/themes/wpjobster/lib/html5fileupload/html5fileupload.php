<?php
if (!empty($_POST)) {
    $absolutedir            = dirname(__FILE__);
    $absolutedir_arr        = explode("/wp-content",$absolutedir,2);
    $absolutedir_str        = $absolutedir_arr['0'];
    if (file_exists($absolutedir_str."/wp-load.php")) {
        require($absolutedir_str.'/wp-load.php');
    } else {
        $response ['debug1'] ="not found ".$absolutedir_str."/wp-load.php";
    }
    $dir                    = '/wp-content/uploads/html5fileupload';
    $dir_path                    = 'wp-content/uploads/html5fileupload';

    if (!is_dir($absolutedir_str.$dir)) {
        @mkdir($absolutedir_str.$dir,0777);// or die("can not create directory");
    }

    $error                  = false;
    $dir                    = $dir."/";
    $serverdir              = $absolutedir_str.$dir;

    $tmp                    = explode(',',$_POST['file']);
    $file                   = base64_decode($tmp[1]);

    $extension              = @strtolower(@end(@explode('.',$_POST['filename'])));
    $filename               = str_replace(array(" ",",","\"","'"),"-",$_POST['name']).'.'.$extension;
    //$filename             = $file.'.'.substr(sha1(time()),0,6).'.'.$extension;

    $handle                 = fopen($serverdir.$filename,'w');
    fwrite($handle, $file);
    fclose($handle);

    $response = array(
        "result"        => true,
        //"serverpath"           => $_SERVER['DOCUMENT_ROOT'].$dir.$filename,//.'?'.time(), //added the time to force update when editting multiple times
        "filepath"      =>ABSPATH.$dir_path."/".$filename,
        "absolutedir_str_path"=>$absolutedir_str.$dir.$filename,
        "url"           => site_url().$dir.$filename,//.'?'.time(), //added the time to force update when editting multiple times
        "filename"      => $filename
    );
    include 'save_wpattachment.php';

    // delete tmp file
    unlink($absolutedir_str.$dir.$filename);

    // response
    echo json_encode($response);

} else {
    $filename           = basename($_SERVER['QUERY_STRING']);
    $file_url           = dirname(__FILE__).'/tmp/'.$filename;
    header('Content-Type:               application/octet-stream');
    header("Content-Transfer-Encoding:  Binary");
    header("Content-disposition:        attachment; filename=\"" . basename($file_url) . "\"");
    readfile($file_url);
    exit();
}
?>