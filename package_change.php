<?php
header("Content-Type: text/plain; charset=utf-8");

// Lấy tham số
$type = $_GET['type'] ?? '';
$current = intval($_GET['current'] ?? 0) - 1; // js gửi index+1 nên trừ đi
$newChange = intval($_GET['newChange'] ?? 0) - 1;

if (!$type || $current < 0 || $newChange < 0) {
    echo "false";
    exit;
}

// Xác định file
$file = $type === "resource" ? "world_resource_packs.json" : "world_behavior_packs.json";

// Đọc danh sách
if (!file_exists($file)) {
    echo "false";
    exit;
}
$data = json_decode(file_get_contents($file), true);
if (!$data || !is_array($data)) {
    echo "false";
    exit;
}

// Kiểm tra index
if ($current >= count($data) || $newChange >= count($data)) {
    echo "false";
    exit;
}

// Lấy item cần move
$item = $data[$current];

// Xóa vị trí cũ
array_splice($data, $current, 1);

// Thêm vào vị trí mới
array_splice($data, $newChange, 0, [$item]);

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// ép riêng "version" gọn lại
$json = preg_replace_callback(
    '/"version":\s*\[([^\]]*)\]/',
    function ($m) {
        $clean = preg_replace('/\s+/', '', $m[1]);
        return '"version": [ ' . str_replace(',', ', ', $clean) . ' ]';
    },
    $json
);

file_put_contents($file, $json);

echo "true";
