<?php

function getPubliciteImageName(string $image_name)
{
    $imagePath = public_path('images/publicites/' . $image_name);
    $imageFileName = getName($imagePath);
    $imageFilePath = public_path('images/publicites/' . $imageFileName);
    if (!file_exists($imagePath)) {
        throw new Exception("Image file not found.");
    }
    if (!copy($imagePath, $imageFilePath)) {
        throw new Exception("Failed to copy image file.");
    }
    return $imageFileName;
}

function getName(string $imagePath): string
{
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    $validExtensions = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageExtension, $validExtensions)) {
        throw new Exception("Invalid image format. Only JPG and PNG images are supported.");
    }
    $imageName = pathinfo($imagePath, PATHINFO_FILENAME);
    return $imageName . '_' . uniqid() . '.' . $imageExtension;
}
