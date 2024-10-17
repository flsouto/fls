<?php

function b64_data_url($filePath) {
    $mimeType = mime_content_type($filePath);
    $fileData = file_get_contents($filePath);
    $base64Data = base64_encode($fileData);
    $base64Url = 'data:' . $mimeType . ';base64,' . $base64Data;
    return $base64Url;
}
