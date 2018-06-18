<?php
header('Content-type: text/css');
ob_start("compress");

function compress( $minify )
{
	/* remove comments */
	$minify = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify );

	/* remove tabs, spaces, newlines, etc. */
	$minify = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minify );

	return $minify;
}

/* css files for combining */
$get_templates = array_diff( scandir( __DIR__ ), Array( ".", "..", "asset", "template.css.php", "rtl-template.css.php" ) );
foreach ( $get_templates as $template ) {
	if ( strpos($template, 'block-') !== false || strpos($template, 'rtl-') !== false ) {
//		echo $template . ', ';
		if ( isset( $template ) ) {
			include($template);
		}
	}
}
//echo "<pre>";
//var_dump($get_templates);
//echo "</pre>";

ob_end_flush();