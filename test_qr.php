<?php
require __DIR__ . '/vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$options = new QROptions([
    'version'    => 5,
    'outputType' => QRCode::OUTPUT_MARKUP_SVG,
    'eccLevel'   => QRCode::ECC_L,
    'imageBase64' => false,
]);

$qrcode = new QRCode($options);
$data = $qrcode->render('TEST-BATCH-123');

file_put_contents('test_qr.svg', $data);

echo "QR Code generated.\n";
