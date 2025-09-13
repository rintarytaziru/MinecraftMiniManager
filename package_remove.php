<?php
header('Content-Type: text/plain; charset=utf-8');

if (!isset($_GET['data'])) { echo "false"; exit; }

$data = json_decode(urldecode($_GET['data']), true);
if (!$data) { echo "false"; exit; }

$files = ["./world_behavior_packs.json", "./world_resource_packs.json"];

foreach ($files as $file) {
    $list = json_decode(file_get_contents($file), true);
    $new = [];
    foreach ($list as $p) {
        if ($p["pack_id"] != $data["pack_id"]) {
            $new[] = $p;
        }
    }

    // encode trước
    $json = json_encode($new, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // fix riêng dòng version thành [ 0, 0, 1 ]
    $json = preg_replace_callback(
        '/"version":\s*\[(.*?)\]/s',
        function ($m) {
            $clean = preg_replace('/\s+/', '', $m[1]);
            return '"version": [ ' . str_replace(',', ', ', $clean) . ' ]';
        },
        $json
    );

    file_put_contents($file, $json);
}

echo "true";
