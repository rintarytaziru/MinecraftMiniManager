<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_GET['uid'])){
    echo json_encode(['error'=>'Thiếu uid']);
    exit;
}

$uid = $_GET['uid'];
$folders = ['resource_packs','behavior_packs'];
$pack = null;

// Hàm lấy tất cả UUID từ manifest
function getAllUUIDs($manifest){
    $uuids = [];
    if(isset($manifest['header']['uuid'])) $uuids[] = $manifest['header']['uuid'];

    if(!empty($manifest['modules'])){
        foreach($manifest['modules'] as $mod){
            if(isset($mod['uuid'])) $uuids[] = $mod['uuid'];
        }
    }

    if(!empty($manifest['dependencies'])){
        foreach($manifest['dependencies'] as $dep){
            if(isset($dep['uuid'])) $uuids[] = $dep['uuid'];
        }
    }

    return $uuids;
}

// Hàm convert version array -> string
function getVersion($manifest){
    if(isset($manifest['header']['version']) && is_array($manifest['header']['version'])){
        return implode('.', $manifest['header']['version']);
    }
    return 'Không xác định';
}

// Hàm lấy description
function getDescription($manifest){
    return $manifest['header']['description'] ?? '';
}

// Hàm load JSON và clean ký tự lạ
function loadJSON($path){
    $content = file_get_contents($path);
    if(!$content) return null;

    // loại bỏ BOM UTF-8
    if(substr($content,0,3) === "\xEF\xBB\xBF"){
        $content = substr($content,3);
    }

    // clean ký tự control và lạ
    $content = preg_replace('/[\x00-\x1F\x7F-\x9F]/u','',$content);

    $json = json_decode($content,true);
    if(json_last_error() !== JSON_ERROR_NONE){
        return null;
    }
    return $json;
}


foreach($folders as $folder){
    $dir = __DIR__.'/'.$folder;
    if(!is_dir($dir)) continue;

    foreach(scandir($dir) as $sub){
        if($sub==='.'||$sub==='..') continue;
        $subDir = $dir.'/'.$sub;
        $manifestPath = $subDir.'/manifest.json';
        if(!file_exists($manifestPath)) continue;

        $manifest = loadJSON($manifestPath);
        if(!$manifest) continue;

        // check UUID trong header + modules + dependencies
        if(in_array($uid,getAllUUIDs($manifest))){
            $pack = $manifest;
            $pack['type'] = $folder;
            $pack['version'] = getVersion($manifest);
            $pack['description'] = getDescription($manifest);
            $pack['path'] = str_replace("\\", "/", $subDir);

            // icon
            $pack['icon'] = false;
            foreach(['icon.png','pack_icon.png'] as $iconFile){
                if(file_exists($subDir.'/'.$iconFile)){
                    $pack['icon'] = '/'.$folder.'/'.$sub.'/'.$iconFile;
                    break;
                }
            }

            break 2;
        }
    }
}

if(!$pack){
    echo json_encode(['error'=>'Không tìm thấy pack']);
    exit;
}

echo json_encode($pack, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
