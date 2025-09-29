<?php
header('Content-Type: application/json; charset=utf-8');

function scanManifests($dir, $type) {
    $packs = [];
    foreach (glob($dir . "/*/manifest.json") as $file) {
        $folder = dirname($file);
        $jsonContent = file_get_contents($file);

        // Ép UTF-8
        $jsonContent = preg_replace('/^\x{FEFF}/u', '', mb_convert_encoding($jsonContent, 'UTF-8', 'UTF-8'));
        $json = json_decode($jsonContent, true);
        if (!$json) continue;

        // Header hoặc fallback modules đầu tiên
        $header = $json["header"] ?? ($json["modules"][0] ?? []);

        $iconPath = $folder . "/pack_icon.png";
        $icon = file_exists($iconPath) ? $iconPath : "";

        $name = $header["name"] ?? "Unknown";
        $description = $header["description"] ?? "";

        $packs[] = [
            "name" => $name,
            "description" => $description,
            "uuid" => $header["uuid"] ?? "",
            "version" => $header["version"] ?? [1,0,0],
            "icon" => $icon,
            "type" => $type
        ];
    }
    return $packs;
}

$behavior = scanManifests("./behavior_packs", "behavior");
$resource = scanManifests("./resource_packs", "resource");

echo json_encode([
    "behavior" => $behavior,
    "resource" => $resource
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
