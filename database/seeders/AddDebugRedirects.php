<?php

$maps = [
    // debug.conf → (target prod domain, path to debug.conf)
    'main.sharzhukov.com' => [
        'target' => 'sharzhukov.com',
        'conf'   => '/etc/nginx/fastpanel2-sites/main_shrz_usr/main.sharzhukov.com.conf',
        'user'   => 'main_shrz_usr',
    ],
    'account-main.sharzhukov.com' => [
        'target' => 'account.sharzhukov.com',
        'conf'   => '/etc/nginx/fastpanel2-sites/account_main_usr/account-main.sharzhukov.com.conf',
        'user'   => 'account_main_usr',
    ],
    'wiki-main.sharzhukov.com' => [
        'target' => 'wiki.sharzhukov.com',
        'conf'   => '/etc/nginx/fastpanel2-sites/wiki_main_sh_usr/wiki-main.sharzhukov.com.conf',
        'user'   => 'wiki_main_sh_usr',
    ],
    'go-main.sharzhukov.com' => [
        'target' => 'go.sharzhukov.com',
        'conf'   => '/etc/nginx/fastpanel2-sites/go_main_shar_usr/go-main.sharzhukov.com.conf',
        'user'   => 'go_main_shar_usr',
    ],
];

foreach ($maps as $debug => $info) {
    $conf = $info['conf'];
    if (!file_exists($conf)) {
        echo "!! SKIP (not found): $conf\n";
        continue;
    }

    $c = file_get_contents($conf);

    // Если уже есть редирект — skip
    if (strpos($c, 'return 301 https://' . $info['target']) !== false) {
        echo "SKIP (already has redirect): $debug\n";
        continue;
    }

    // Бэкап
    $backup = $conf . '.before-redirect-' . date('Y-m-d-His');
    copy($conf, $backup);
    echo "  backup: " . basename($backup) . "\n";

    // Найти блок `server {` и вставить `return 301` сразу после listen-строк.
    // Ищем первую строку с `listen `, и после неё (со всей группой listen + ssl) ставим return.
    // Простой способ: вставляем после последней строки `listen ...` ПЕРЕД первым `location` или `ssl_certificate`.

    $lines = explode("\n", $c);
    $out = [];
    $inserted = false;
    $lastListenIdx = -1;

    // Первый проход — найти индекс последней listen-строки в первом server-блоке
    $inFirstServer = false;
    $braceDepth = 0;
    foreach ($lines as $i => $line) {
        if (preg_match('/^\s*server\s*\{/', $line)) {
            $inFirstServer = true;
            $braceDepth = 1;
            continue;
        }
        if ($inFirstServer) {
            $braceDepth += substr_count($line, '{') - substr_count($line, '}');
            if (preg_match('/^\s*listen\s+/', $line)) {
                $lastListenIdx = $i;
            }
            if ($braceDepth <= 0) break;
        }
    }

    if ($lastListenIdx === -1) {
        echo "!! FAILED (no listen found in first server): $debug\n";
        continue;
    }

    // Вставляем после lastListenIdx
    $redirectLine = '';
    $redirectLine .= "\n    # ─── Redirect debug → prod ───\n";
    $redirectLine .= "    return 301 https://{$info['target']}\$request_uri;\n";

    array_splice($lines, $lastListenIdx + 1, 0, [$redirectLine]);
    $c = implode("\n", $lines);

    file_put_contents($conf, $c);
    echo "✓ Added 301 redirect: $debug → {$info['target']}\n\n";
}

shell_exec("nginx -t 2>&1");
echo "\n=== DONE ===\n";
