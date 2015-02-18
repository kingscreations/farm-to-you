<?php

function checkInputImage($inputImage) {
	// check extension for normal users
	$extensions = array("jpg", "jpeg", "png");
	$extension  = strtolower(end(explode(".", $inputImage["name"])));
	if(in_array($extension, $extensions) === false) {
		throw new InvalidArgumentException('The input image file is not valid');
	}

	// check file content for malicious users and totally incompetent users
	$mimeType = $inputImage["type"];
	if($mimeType !== 'image/png' || $mimeType !== 'image/jpeg') {
		throw new RangeException('GIF and JPEG images are the only valid image types');
	}

	if($mimeType === "image/png") {
		if(($image = @imagecreatefrompng($inputImage["tmp_name"])) === false) {
			throw new InvalidArgumentException('The input png image format is incorrect');
		}
	}

	if($mimeType === "image/jpg") {
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