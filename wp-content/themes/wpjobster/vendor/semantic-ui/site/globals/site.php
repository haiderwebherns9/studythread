<?php
$primaryColor   = isset( $_COOKIE['primaryColor'] )   ? $_COOKIE['primaryColor']   : '#83C124';
$secondaryColor = isset( $_COOKIE['secondaryColor'] ) ? $_COOKIE['secondaryColor'] : '#2d5767';
$fontName       = isset( $_COOKIE['fontName'] )       ? $_COOKIE['fontName']       : 'Lato';
?>

@primaryColor   : <?php echo $primaryColor; ?>;
@secondaryColor : <?php echo $secondaryColor; ?>;
@linkColor      : <?php echo $primaryColor; ?>;

@headerFont     : <?php echo $fontName; ?>;
@pageFont       : <?php echo $fontName; ?>;
@fontName       : <?php echo $fontName; ?>;
@fontPath       : "@{spath}/themes/default/assets/fonts/";
@imagePath      : "@{spath}/themes/default/assets/images/";
