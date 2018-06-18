<?php

//header('Content-Type: application/json');
//ini_set('memory_limit','16M');

$error					= false;

$absolutedir			= dirname(__FILE__);
$dir					= '/tmp/';
$serverdir				= $absolutedir.$dir;
$filename				= array();

/* REMOVE THE COMMENT LINE TO MAKE THE EXAMPLE WORK! 
 * 
foreach($_FILES as $inputname => $file) {
	$newname				= $_POST[$inputname.'_name'];
	$extension				= strtolower(end(explode('.',$file['name'])));
	$fname					= $newname.'.'.$extension;
	
	$contents				= file_get_contents($file['tmp_name']);
	
	$handle					= fopen($serverdir.$fname,'w');
	fwrite($handle, $contents);
	fclose($handle);
	
	$filename[]				= $fname;
}
*/

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HTML5 Image upload width drag and drop, crop and ratio</title>
    
    <meta name="description" content="A HTML5 file uploader, single or multiple files, image preview, download, upload control and server files" />

    <!-- Bootstrap -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/html5fileupload.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
	<div class="container">
	  <div class="row">
	    <div class="col-xs-12">
	      <h1>HTML5 File upload</h1>
	    </div>
	  </div>
	  
	  <div class="row">
	    <div class="col-xs-6">
	      <h2>Result</h2>
	      
	      <p>As you can see on the right side, the form only has 1 input field text, but we get 3 results. This is because of the <code>input[type=file]</code> adds a input field with the new name that can be editted by the user. This way we post the file and a new name.</p>
	      <p>All there is to do is same the file on your server with the correct name. When you purchase the script, a PHP example will be given on how to do this. </p>
	      
	      <p><em>No files where uploaded in this example, i don't need all the example files on my server :)</em></p>
	      
	      <p>&nbsp;</p>
	      
	      <div class="row">
		    <div class="col-xs-12">
		      <p class="text-center"><a href="//codecanyon.net/item/html5-file-upload/9254506?ref=stbeets" class="btn btn-lg btn-info">Download this plugin at Code Canyon!</a></p>
		    </div>
		  </div>
	      
	      <div class="row">
		    <div class="col-xs-12">
		      <p class="text-center"><a href="/" class="btn btn-success"><i class="glyphicon glyphicon-chevron-left"></i> Back to homepage</a></p>
		    </div>
		  </div>
	      
	    </div>
	    
	    <div class="col-xs-6">
	      <h2></h2>
	      <p>The <code>$_POST</code> variable</p>
	      <pre><?php echo var_dump($_POST); ?></pre>
	      <p>The <code>$_FILES </code> variable</p>
	      <pre><?php echo var_dump($_FILES); ?></pre>
	    </div>
	    
	  </div>
	  
	</div>
	
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
	
	</body>
</html>