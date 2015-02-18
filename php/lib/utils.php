<?php

/**
 * @param $inputImage the input image. e.g.: $_FILES['image']
 * @return string the extension of the image
 *
 * @throw InvalidArgumentException if the input png file is not valid
 * @throw InvalidArgumentException if the input jpg file is not valid
 * @throw RangeException if the type is different than jpg, JPG, jpeg, JPEG, png or PNG
 * @throw RangeException if the mime type is different than jpg or png
 */
function checkInputImage($inputImage) {
	// check extension for normal users
	$extensions = array("jpg", "jpeg", "png");
	$extension  = strtolower(end(explode(".", $inputImage["name"])));
	if(in_array($extension, $extensions) === false) {
		throw new RangeException('The input image file should be either jpg, JPG, jpeg, JPEG, png or PNG');
	}

	// check file content for malicious users and totally incompetent users
	$mimeType = $inputImage["type"];
	if($mimeType !== 'image/png' && $mimeType !== 'image/jpeg') {
		throw new RangeException('PNG and JPEG images are the only valid image types');
	}

	$image = null;

	if($mimeType === "image/png") {
		if(($image = @imagecreatefrompng($inputImage["tmp_name"])) === false) {
			throw new InvalidArgumentException('The input png image format is incorrect');
		}
	}

	if($mimeType === "image/jpeg") {
		if(($image = @imagecreatefromjpeg($inputImage["tmp_name"])) === false) {
			throw new InvalidArgumentException('The input jpg image format is incorrect');
		}
	}

	var_dump($image);

	// want to resize/crop/vandalize Alonso's images?
	// do so here!
	imagedestroy($image);

	if($mimeType === "image/png") {
		return 'png';
	} else {
		return 'jpg';
	}
}

?>