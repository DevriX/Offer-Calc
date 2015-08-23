<?php 

//Settings: You can customize the captcha here

if (!session_id()) {
    session_start();
}

$image_width = 151;

$image_height = 26;

$characters_on_image = 6;

//$font = '../font/monofont.ttf';
$font = OFFER_CALC_PRO_DIR.'/font/monofont.ttf';

//The characters that can be used in the CAPTCHA code.

//avoid confusing characters (l 1 and i for example)
$possible_letters	= '23456789bcdfghjkmnpqrstvwxyz';

$random_dots			= 0;
$random_lines			= 20;

$captcha_text_color		= "0x142864";
$captcha_noice_color	= "0x142864";

$code					= '';

$i						= 0;

while( $i < $characters_on_image ) { 
	$code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
	$i++;
}

$font_size			= $image_height * 0.75;
$image				= @imagecreate($image_width, $image_height);

/* setting the background, text and noise colours here */
$background_color	= imagecolorallocate($image, 255, 255, 255);
$text_color			= imagecolorallocate($image, 0, 0, 0);

$image_noise_color	= imagecolorallocate($image, 155, 155, 155);

/* generating the dots randomly in background */
for( $i=0; $i < $random_dots; $i++ ) {

	imagefilledellipse( $image, mt_rand( 0, $image_width ), mt_rand( 0, $image_height ), 2, 3, $image_noise_color );
}

/* generating lines randomly in background of image */
for( $i=0; $i<$random_lines; $i++ ) {
	
	imageline($image, mt_rand(0,$image_width), mt_rand(0,$image_height),
	mt_rand(0,$image_width), mt_rand(0,$image_height), $image_noise_color);
}

/* create a text box and add 6 letters code in it */
$textbox	= imagettfbbox( $font_size, 0, $font, $code ); 

$x			= ( $image_width - $textbox[4] ) / 2;
$y			= ( $image_height - $textbox[5] ) / 2;

imagettftext( $image, $font_size, 0, $x, $y, $text_color, $font , $code );

/* Show captcha image in the page html page */
header('Content-Type: image/jpeg');// defining the image type to be shown in browser widow
imagejpeg($image);//showing the image
imagedestroy($image);//destroying the image instance

$_SESSION['offer_calc_captcha_code'][$_GET['rand']][$_GET['ofc_id']] = $code;

function hexrgb( $hexstr ) {
	
	$int	= hexdec( $hexstr );
	
	return array(
				"red" => 0xFF & ($int >> 0x10),
				"green" => 0xFF & ($int >> 0x8),
				"blue" => 0xFF & $int
			);
}