<?php
error_reporting(0);
session_start();
$text =  rand(10000, 30000);
$_SESSION['code'] = $text;

$im = imagecreatetruecolor(50, 24);
$bg = imagecolorallocate($im, 22, 86, 165); //background color blue
$fg = imagecolorallocate($im, 255, 255, 255);//text color white
imagefill($im, 0, 0, $bg);
imagestring($im, 5, 5, 5,  $text, $fg);

imagepng($im);
imagedestroy($im);

?>
