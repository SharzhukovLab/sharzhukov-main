<?php
/**
 * Миграция sharzhukov_com → main_shrz.
 * Читаем старую БД через root, пишем в новую через PDO (main_shrz_usr).
 * DRY_RUN=1 php migrate.php
 */

$DRY = getenv('DRY_RUN') === '1';

echo $DRY ? "═══ DRY RUN (ничего не пишем) ═══\n\n" : "═══ REAL MIGRATION ═══\n\n";

// ── Root connection к старой БД
$oldPdo = new PDO('mysql:unix_socket=/run/mysqld/mysqld.sock;dbname=sharzhukov_com;charset=utf8mb4', 'main_shrz_usr', 'Signal21');
$oldPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$oldPdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// ── Main connection к новой БД
$newPdo = new PDO('mysql:unix_socket=/run/mysqld/mysqld.sock;dbname=main_shrz;charset=utf8mb4', 'main_shrz_usr', 'Signal21');
$newPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$newPdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Хелперы
function oldAll(PDO $pdo, string $table): array {
    return $pdo->query("SELECT * FROM `{$table}`")->fetchAll();
}
function newExists(PDO $pdo, string $table, array $where): bool {
    $cond = [];
    $vals = [];
    foreach ($where as $k => $v) { $cond[] = "`{$k}` = ?"; $vals[] = $v; }
    $st = $pdo->prepare("SELECT 1 FROM `{$table}` WHERE " . implode(' AND ', $cond) . " LIMIT 1");
    $st->execute($vals);
    return (bool) $st->fetchColumn();
}
function newId(PDO $pdo, string $table, array $where): ?int {
    $cond = [];
    $vals = [];
    foreach ($where as $k => $v) { $cond[] = "`{$k}` = ?"; $vals[] = $v; }
    $st = $pdo->prepare("SELECT id FROM `{$table}` WHERE " . implode(' AND ', $cond) . " LIMIT 1");
    $st->execute($vals);
    $id = $st->fetchColumn();
    return $id !== false ? (int) $id : null;
}
function insertRow(PDO $pdo, string $table, array $data): int {
    $cols = array_keys($data);
    $place = array_fill(0, count($cols), '?');
    $sql = "INSERT INTO `{$table}` (`" . implode('`,`', $cols) . "`) VALUES (" . implode(',', $place) . ")";
    $st = $pdo->prepare($sql);
    $st->execute(array_values($data));
    return (int) $pdo->lastInsertId();
}

$userIdMap = [];
$catIdMap  = [];
$tagIdMap  = [];
$pageIdMap = [];

// ═══════════════════════════════════════════════════════════════
// 1. USERS
// ═══════════════════════════════════════════════════════════════
echo "═══ USERS ═══\n";
$users = oldAll($oldPdo, 'users');
echo "  old: " . count($users) . "\n";

foreach ($users as $u) {
    $existingId = newId($newPdo, 'users', ['email' => $u['email']]);
    if ($existingId) {
        echo "  SKIP (exists id={$existingId}): {$u['email']}\n";
        $userIdMap[$u['id']] = $existingId;
        continue;
    }

    // Проверка на конфликт username
    $username = $u['username'];
    if ($username && newExists($newPdo, 'users', ['username' => $username])) {
        $username = $username . '-' . $u['id'];
        echo "  ⚠ username conflict → {$username}\n";
    }

    $data = [
        'name'              => $u['name'],
        'username'          => $username,
        'email'             => $u['email'],
        'avatar_path'       => $u['avatar_path'],
        'bio'               => $u['bio'],
        'social_links'      => $u['social_links'],
        'team_role'         => $u['team_role'],
        'team_roles_data'   => $u['team_roles_data'],
        'team_year'         => $u['team_year'],
        'team_specs'        => $u['team_specs'],
        'team_specs_data'   => $u['team_specs_data'],
        'team_order'        => $u['team_order'],
        'show_in_team'      => $u['show_in_team'],
        'terms_accepted_at' => $u['terms_accepted_at'],
        'approved_comments_count' => $u['approved_comments_count'],
        'is_trusted'        => $u['is_trusted'],
        'is_banned'         => $u['is_banned'],
        'email_verified_at' => $u['email_verified_at'],
        'password'          => $u['password'],
        'two_factor_secret' => $u['two_factor_secret'],
        'two_factor_recovery_codes' => $u['two_factor_recovery_codes'],
        'two_factor_confirmed_at'   => $u['two_factor_confirmed_at'],
        'role'              => $u['role'],
        'created_at'        => $u['created_at'],
        'updated_at'        => $u['updated_at'],
    ];

    if ($DRY) {
        echo "  + USER: {$u['email']} ({$u['name']})\n";
        $userIdMap[$u['id']] = -1;
    } else {
        $newId = insertRow($newPdo, 'users', $data);
        $userIdMap[$u['id']] = $newId;
        echo "  ✓ USER: {$u['id']} → {$newId} ({$u['email']})\n";
    }
}

// ═══════════════════════════════════════════════════════════════
// 2. USER PROJECTS
// ═══════════════════════════════════════════════════════════════
echo "\n═══ USER PROJECTS ═══\n";
$projects = oldAll($oldPdo, 'user_projects');
echo "  old: " . count($projects) . "\n";

foreach ($projects as $p) {
    if (newExists($newPdo, 'user_projects', ['slug' => $p['slug']])) {
        echo "  SKIP: {$p['slug']}\n"; continue;
    }
    $newUserId = $userIdMap[$p['user_id']] ?? null;
    if (!$newUserId || $newUserId < 0) { echo "  SKIP (no user): {$p['slug']}\n"; continue; }

    $data = [
        'user_id'         => $newUserId,
        'title'           => $p['title'],
        'slug'            => $p['slug'],
        'tagline'         => $p['tagline'],
        'description'     => $p['description'],
        'github_url'      => $p['github_url'],
        'demo_url'        => $p['demo_url'],
        'download_url'    => $p['download_url'],
        'external_url'    => $p['external_url'],
        'cover_image_path'=> $p['cover_image_path'],
        'gallery'         => $p['gallery'],
        'tech_stack'      => $p['tech_stack'],
        'license'         => $p['license'],
        'status_label'    => $p['status_label'],
        'status'          => $p['status'],
        'moderation_note' => $p['moderation_note'],
        'moderated_by'    => null,
        'moderated_at'    => $p['moderated_at'],
        'is_featured'     => $p['is_featured'],
        'order'           => $p['order'],
        'views_count'     => $p['views_count'],
        'created_at'      => $p['created_at'],
        'updated_at'      => $p['updated_at'],
        'deleted_at'      => $p['deleted_at'],
    ];

    if ($DRY) { echo "  + PROJECT: {$p['title']}\n"; }
    else { insertRow($newPdo, 'user_projects', $data); echo "  ✓ PROJECT: {$p['title']}\n"; }
}

// ═══════════════════════════════════════════════════════════════
// 3. NEWS
// ═══════════════════════════════════════════════════════════════
echo "\n═══ NEWS ═══\n";
$news = oldAll($oldPdo, 'news');
echo "  old: " . count($news) . "\n";

foreach ($news as $n) {
    if (newExists($newPdo, 'news', ['slug' => $n['slug']])) {
        echo "  SKIP: {$n['slug']}\n"; continue;
    }
    $data = [
        'title'            => $n['title'],
        'slug'             => $n['slug'],
        'category'         => $n['category'],
        'excerpt'          => $n['excerpt'],
        'body'             => $n['body'],
        'gradient'         => $n['gradient'],
        'cover_image_path' => $n['cover_image_path'],
        'related_repo'     => $n['related_repo'],
        'external_url'     => $n['external_url'],
        'is_published'     => $n['is_published'],
        'is_pinned'        => $n['is_pinned'],
        'published_at'     => $n['published_at'],
        'created_at'       => $n['created_at'],
        'updated_at'       => $n['updated_at'],
    ];
    if ($DRY) { echo "  + NEWS: {$n['title']}\n"; }
    else { insertRow($newPdo, 'news', $data); echo "  ✓ NEWS: {$n['title']}\n"; }
}

// ═══════════════════════════════════════════════════════════════
// 4. WIKI CATEGORIES
// ═══════════════════════════════════════════════════════════════
echo "\n═══ WIKI CATEGORIES ═══\n";
$cats = oldAll($oldPdo, 'wiki_categories');
echo "  old: " . count($cats) . "\n";

foreach ($cats as $c) {
    $existingId = newId($newPdo, 'wiki_categories', ['slug' => $c['slug']]);
    if ($existingId) {
        echo "  SKIP (exists): {$c['slug']}\n";
        $catIdMap[$c['id']] = $existingId;
        continue;
    }
    $data = [
        'parent_id'   => null,
        'name'        => $c['name'],
        'slug'        => $c['slug'],
        'description' => $c['description'],
        'icon'        => $c['icon'],
        'sort_order'  => $c['sort_order'],
        'is_visible'  => $c['is_visible'],
        'created_at'  => $c['created_at'],
        'updated_at'  => $c['updated_at'],
    ];
    if ($DRY) { echo "  + CAT: {$c['name']}\n"; $catIdMap[$c['id']] = -1; }
    else {
        $newId = insertRow($newPdo, 'wiki_categories', $data);
        $catIdMap[$c['id']] = $newId;
        echo "  ✓ CAT: {$c['name']} ({$c['id']} → {$newId})\n";
    }
}
if (!$DRY) {
    foreach ($cats as $c) {
        if ($c['parent_id'] && isset($catIdMap[$c['parent_id']]) && $catIdMap[$c['parent_id']] > 0) {
            $st = $newPdo->prepare("UPDATE wiki_categories SET parent_id = ? WHERE id = ?");
            $st->execute([$catIdMap[$c['parent_id']], $catIdMap[$c['id']]]);
        }
    }
}

// ═══════════════════════════════════════════════════════════════
// 5. WIKI TAGS
// ═══════════════════════════════════════════════════════════════
echo "\n═══ WIKI TAGS ═══\n";
$tags = oldAll($oldPdo, 'wiki_tags');
echo "  old: " . count($tags) . "\n";

foreach ($tags as $t) {
    $existingId = newId($newPdo, 'wiki_tags', ['slug' => $t['slug']]);
    if ($existingId) {
        echo "  SKIP: {$t['slug']}\n";
        $tagIdMap[$t['id']] = $existingId;
        continue;
    }
    $data = [
        'name'       => $t['name'],
        'slug'       => $t['slug'],
        'color'      => $t['color'],
        'created_at' => $t['created_at'],
        'updated_at' => $t['updated_at'],
    ];
    if ($DRY) { echo "  + TAG: {$t['name']}\n"; $tagIdMap[$t['id']] = -1; }
    else {
        $newId = insertRow($newPdo, 'wiki_tags', $data);
        $tagIdMap[$t['id']] = $newId;
        echo "  ✓ TAG: {$t['name']}\n";
    }
}

// ═══════════════════════════════════════════════════════════════
// 6. WIKI PAGES
// ═══════════════════════════════════════════════════════════════
echo "\n═══ WIKI PAGES ═══\n";
$pages = oldAll($oldPdo, 'wiki_pages');
echo "  old: " . count($pages) . "\n";

foreach ($pages as $pg) {
    if (newExists($newPdo, 'wiki_pages', ['slug' => $pg['slug']])) {
        echo "  SKIP: {$pg['slug']}\n";
        $pageIdMap[$pg['id']] = newId($newPdo, 'wiki_pages', ['slug' => $pg['slug']]);
        continue;
    }
    $newCatId = $catIdMap[$pg['category_id']] ?? null;
    $newAuthorId = $userIdMap[$pg['author_id']] ?? null;
    if ($newAuthorId && $newAuthorId < 0) $newAuthorId = null;

    $data = [
        'category_id'      => $newCatId,
        'parent_id'        => null,
        'author_id'        => $newAuthorId,
        'title'            => $pg['title'],
        'slug'             => $pg['slug'],
        'excerpt'          => $pg['excerpt'],
        'content'          => $pg['content'],
        'meta_title'       => $pg['meta_title'],
        'meta_description' => $pg['meta_description'],
        'status'           => $pg['status'],
        'views_count'      => $pg['views_count'],
        'sort_order'       => $pg['sort_order'],
        'is_pinned'        => $pg['is_pinned'],
        'published_at'     => $pg['published_at'],
        'created_at'       => $pg['created_at'],
        'updated_at'       => $pg['updated_at'],
    ];
    if ($DRY) { echo "  + PAGE: {$pg['title']}\n"; $pageIdMap[$pg['id']] = -1; }
    else {
        $newId = insertRow($newPdo, 'wiki_pages', $data);
        $pageIdMap[$pg['id']] = $newId;
        echo "  ✓ PAGE: {$pg['title']} ({$pg['id']} → {$newId})\n";
    }
}

// ═══════════════════════════════════════════════════════════════
// 7. WIKI PAGE TAG (pivot)
// ═══════════════════════════════════════════════════════════════
echo "\n═══ WIKI PAGE TAG ═══\n";
$pivots = oldAll($oldPdo, 'wiki_page_tag');
echo "  old: " . count($pivots) . "\n";
$nP = 0;
foreach ($pivots as $pv) {
    $pid = $pageIdMap[$pv['page_id']] ?? null;
    $tid = $tagIdMap[$pv['tag_id']] ?? null;
    if (!$pid || !$tid || $pid < 0 || $tid < 0) continue;

    $st = $newPdo->prepare("SELECT 1 FROM wiki_page_tag WHERE page_id = ? AND tag_id = ? LIMIT 1");
    $st->execute([$pid, $tid]);
    if ($st->fetchColumn()) continue;

    if (!$DRY) {
        $st = $newPdo->prepare("INSERT INTO wiki_page_tag (page_id, tag_id) VALUES (?,?)");
        $st->execute([$pid, $tid]);
    }
    $nP++;
}
echo "  ✓ pivots: {$nP}\n";

// ═══════════════════════════════════════════════════════════════
// 8. SHORT LINKS
// ═══════════════════════════════════════════════════════════════
echo "\n═══ SHORT LINKS ═══\n";
$links = oldAll($oldPdo, 'short_links');
echo "  old: " . count($links) . "\n";

foreach ($links as $l) {
    if (newExists($newPdo, 'short_links', ['slug' => $l['slug']])) {
        echo "  SKIP: {$l['slug']}\n"; continue;
    }
    $newUserId = $l['user_id'] ? ($userIdMap[$l['user_id']] ?? null) : null;
    if ($newUserId && $newUserId < 0) $newUserId = null;

    $data = [
        'user_id'      => $newUserId,
        'slug'         => $l['slug'],
        'target_url'   => $l['target_url'],
        'description'  => $l['description'],
        'category'     => $l['category'],
        'is_active'    => $l['is_active'],
        'is_reserved'  => $l['is_reserved'],
        'expires_at'   => $l['expires_at'],
        'clicks_count' => $l['clicks_count'],
        'source'       => $l['source'],
        'utm_source'   => $l['utm_source'],
        'utm_medium'   => $l['utm_medium'],
        'utm_campaign' => $l['utm_campaign'],
        'utm_content'  => $l['utm_content'],
        'utm_term'     => $l['utm_term'],
        'created_at'   => $l['created_at'],
        'updated_at'   => $l['updated_at'],
    ];
    if ($DRY) { echo "  + LINK: /s/{$l['slug']}\n"; }
    else { insertRow($newPdo, 'short_links', $data); echo "  ✓ LINK: /s/{$l['slug']}\n"; }
}

// ═══════════════════════════════════════════════════════════════
// SUMMARY
// ═══════════════════════════════════════════════════════════════
echo "\n═══ SUMMARY ═══\n";
foreach (['users','user_projects','news','wiki_categories','wiki_tags','wiki_pages','wiki_page_tag','short_links'] as $t) {
    $st = $newPdo->query("SELECT COUNT(*) AS n FROM `{$t}`");
    echo "  {$t}: " . $st->fetchColumn() . "\n";
}

echo "\n" . ($DRY ? "⚠️  DRY RUN — ничего не записано" : "✓ MIGRATION COMPLETE") . "\n";
