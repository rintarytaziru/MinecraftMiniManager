<?php
header('Content-Type: application/json; charset=utf-8');

function loadList($file, $dir, $type) {
    if (!file_exists($file)) return [];
    $list = json_decode(file_get_contents($file), true);
    if (!is_array($list)) return [];

    $packs = [];
    foreach ($list as $p) {
        $icon = "";
        $name = $p["pack_id"];

        // dò folder theo uuid
        foreach (glob($dir . "/*/manifest.json") as $mf) {
            $folder = dirname($mf);
            $json = json_decode(file_get_contents($mf), true);
            if (!$json) continue;

            if (($json["header"]["uuid"] ?? "") == $p["pack_id"]) {
                $iconPath = $folder . "/pack_icon.png";
                if (file_exists($iconPath)) {
                    // trả về path relative thay vì absolute
                    $icon = str_replace(__DIR__ . "/", "", $iconPath);
                }
                $name = $json["header"]["name"] ?? $p["pack_id"];
                break;
            }
        }

        $packs[] = [
            "pack_id" => $p["pack_id"],
            "version" => $p["version"],
            "icon" => $icon,
            "name" => $name,
            "type" => $type,
            "uuid" => $p["pack_id"]
        ];
    }
    return $packs;
}

$behavior = loadList("./world_behavior_packs.json", "./behavior_packs", "behavior");
$resource = loadList("./world_resource_packs.json", "./resource_packs", "resource");

echo json_encode([
    "behavior" => $behavior,
    "resource" => $resource,
]);
