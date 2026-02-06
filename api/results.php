<?php
// عرض كل الأخطاء أثناء التطوير (ممكن تغييره لـ 0 لاحقًا)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// الحصول على keyid و key من GET
$hexKeyID = $_GET["keyid"] ?? '';
$hexKey   = $_GET["key"] ?? '';

// تحويل hex لـ binary والتحقق
$binKeyID = hex2bin($hexKeyID);
if ($binKeyID === false || empty($hexKeyID)) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode([
        "Status" => 400,
        "Content" => "Validation Failed!",
        "Reason" => "Invalid or missing Key ID"
    ]);
    exit;
}

$binKey = hex2bin($hexKey);
if ($binKey === false || empty($hexKey)) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode([
        "Status" => 400,
        "Content" => "Validation Failed!",
        "Reason" => "Invalid or missing Key"
    ]);
    exit;
}

// تحويل binary لـ Base64 وإزالة '='
$finalKeyID = str_replace('=', '', base64_encode($binKeyID));
$finalKey   = str_replace('=', '', base64_encode($binKey));

// إنشاء JSON النهائي
$license = [
    "keys" => [
        [
            "kty" => "oct",
            "k"   => $finalKey,
            "kid" => $finalKeyID
        ]
    ],
    "type" => "temporary"
];

// إخراج JSON
header("Content-Type: application/json");
echo json_encode($license, JSON_PRETTY_PRINT);
?>
