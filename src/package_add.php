<?php
header('Content-Type: text/plain; charset=utf-8');

if (!isset($_GET['data'])) { echo "false"; exit; }

$data = json_decode(urldecode($_GET['data']), true);
if (!$data) { echo "false"; exit; }

$pack = [
  "pack_id" => $data["uuid"],
  "version" => $data["version"]
];

$target = "./world_resource_packs.json"; // mặc định resource

// duyệt toàn bộ behavior_packs
$dirs = glob("./behavior_packs/*/manifest.json");
foreach ($dirs as $file) {
    $manifestContent = file_get_contents($file);
    $manifestContent = preg_replace('/^\x{FEFF}/u', '', mb_convert_encoding($manifestContent, 'UTF-8', 'UTF-8'));
    $manifest = json_decode($manifestContent, true);

    if ($manifest) {
        // fallback header hoặc modules[0]
        $header = $manifest["header"] ?? ($manifest["modules"][0] ?? []);
        if (($header["uuid"] ?? "") === $data["uuid"]) {
            $target = "./world_behavior_packs.json";
            break;
        }
    }
}

// đọc list cũ
$list = file_exists($target) ? json_decode(file_get_contents($target), true) : [];
if (!$list) $list = [];

// tránh thêm trùng
foreach ($list as $p) {
    if ($p["pack_id"] === $pack["pack_id"]) {
        echo "duplicated";
        exit;
    }
}

$list[] = $pack;

// encode
$json = json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// ép riêng dòng "version" gọn lại
$json = preg_replace_callback(
    '/"version":\s*\[(.*?)\]/s',
    function ($m) {
        $clean = preg_replace('/\s+/', '', $m[1]);
        return '"version": [ ' . str_replace(',', ', ', $clean) . ' ]';
    },
    $json
);

// lưu
file_put_contents($target, $json);

echo "true";
