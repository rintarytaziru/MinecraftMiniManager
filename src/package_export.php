<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

function getPackList($jsonFile) {
    if (!file_exists($jsonFile)) return [];
    $data = json_decode(file_get_contents($jsonFile), true);
    if (!is_array($data)) return [];
    return array_map(fn($p) => $p['pack_id'], $data);
}

function scanManifests($dir) {
    $map = []; // uuid => folder
    foreach (glob($dir . "/*/manifest.json") as $file) {
        $folder = dirname($file);
        $jsonContent = file_get_contents($file);
        $jsonContent = preg_replace('/^\x{FEFF}/u', '', mb_convert_encoding($jsonContent, 'UTF-8', 'UTF-8'));
        $json = json_decode($jsonContent, true);
        if (!$json) continue;
        $header = $json["header"] ?? ($json["modules"][0] ?? []);
        $uuid = $header["uuid"] ?? "";
        if ($uuid) $map[$uuid] = $folder;
    }
    return $map;
}

function copyFolder($src, $dst) {
    if (!is_dir($src)) return 0;
    @mkdir($dst, 0777, true);
    $count = 0;
    $src = str_replace('\\', '/', realpath($src));
    $dst = str_replace('\\', '/', $dst);

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($files as $file) {
        $filePath = str_replace('\\', '/', $file->getRealPath());
        $relativePath = substr($filePath, strlen($src) + 1);
        $destPath = $dst . '/' . $relativePath;

        if ($file->isDir()) {
            @mkdir($destPath, 0777, true);
        } else {
            @mkdir(dirname($destPath), 0777, true);
            if (copy($filePath, $destPath)) $count++;
        }
    }
    return $count;
}

// --- MAIN ---
$behaviorWorld = getPackList('world_behavior_packs.json');
$resourceWorld = getPackList('world_resource_packs.json');

$behaviorMap = scanManifests('./behavior_packs');
$resourceMap = scanManifests('./resource_packs');

$exportDir = './exports';
// Xóa folder cũ trước khi copy
if (is_dir($exportDir)) {
    $it = new RecursiveDirectoryIterator($exportDir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        $path = $file->getRealPath();
        $file->isDir() ? @rmdir($path) : @unlink($path);
    }
    @rmdir($exportDir);
}

$totalFiles = 0;

// Copy behavior packs
foreach ($behaviorWorld as $pack) {
    if (isset($behaviorMap[$pack])) {
        $dst = $exportDir . '/behavior_packs/' . basename($behaviorMap[$pack]);
        $totalFiles += copyFolder($behaviorMap[$pack], $dst);
    }
}

// Copy resource packs
foreach ($resourceWorld as $pack) {
    if (isset($resourceMap[$pack])) {
        $dst = $exportDir . '/resource_packs/' . basename($resourceMap[$pack]);
        $totalFiles += copyFolder($resourceMap[$pack], $dst);
    }
}

// Copy tất cả file world_*_packs.json vào exports/
foreach (glob('world_*_packs.json') as $jsonFile) {
    @mkdir($exportDir, 0777, true);
    if (copy($jsonFile, $exportDir . '/' . basename($jsonFile))) {
        $totalFiles++;
    }
}

echo $totalFiles > 0
    ? "Hoàn tất! Đã copy $totalFiles file sang $exportDir\n"
    : "Chưa copy được file nào! Kiểm tra folder và JSON world\n";
?>
