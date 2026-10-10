<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GithubService
{
    protected array $accounts;
    protected ?string $token;
    protected int $ttl;

    public function __construct()
    {
        $this->accounts = config('services.github.accounts', ['Sharzhukov', 'SharzhukovLab']);
        $this->token    = config('services.github.token');
        $this->ttl      = (int) config('services.github.cache_ttl', 3600);
    }

    /**
     * Все публичные репозитории со всех аккаунтов (личный + org).
     * Кэшируется на $ttl секунд.
     */
    public function repos(int $limit = 100): array
    {
        $key = 'github.repos.v3.' . md5(implode(',', $this->accounts)) . ".{$limit}";

        return Cache::remember($key, $this->ttl, function () use ($limit) {
            $all = [];

            foreach ($this->accounts as $account) {
                $fetched = $this->fetchAccountRepos($account, $limit);
                $all = array_merge($all, $fetched);
            }

            // Сортируем: свежие сверху
            usort($all, function ($a, $b) {
                return strcmp($b['updated_at'] ?? '', $a['updated_at'] ?? '');
            });

            // Уникализируем по full_name
            $seen = [];
            $out  = [];
            foreach ($all as $repo) {
                if (isset($seen[$repo['full_name']])) continue;
                $seen[$repo['full_name']] = true;
                $out[] = $repo;
            }

            return array_slice($out, 0, $limit);
        });
    }

    /**
     * Забрать публичные репы одного аккаунта (User или Organization).
     */
    protected function fetchAccountRepos(string $account, int $limit): array
    {
        $type = $this->detectAccountType($account);   // 'User' или 'Organization'

        $url = $type === 'Organization'
            ? "https://api.github.com/orgs/{$account}/repos"
            : "https://api.github.com/users/{$account}/repos";

        try {
            $params = [
                'per_page' => min(100, max(30, $limit)),
                'sort'     => 'updated',
                'direction'=> 'desc',
            ];

            // Для org — просим только публичные
            if ($type === 'Organization') {
                $params['type'] = 'public';
            } else {
                // для User — просим owned (потом отфильтруем приватные)
                $params['type'] = 'owner';
            }

            $resp = $this->client()->get($url, $params);

            if (! $resp->ok()) {
                logger()->warning('[github] repos failed', [
                    'account' => $account,
                    'type'    => $type,
                    'status'  => $resp->status(),
                    'body'    => substr($resp->body(), 0, 200),
                ]);
                return [];
            }

            $raw = $resp->json();
            if (! is_array($raw)) return [];

            return collect($raw)
                ->filter(fn ($r) => empty($r['private']) && empty($r['fork']))   // ← только публичные, без форков
                ->map(function ($r) use ($account, $type) {
                    return [
                        'name'        => $r['name']              ?? '',
                        'full_name'   => $r['full_name']         ?? '',
                        'owner'       => $account,
                        'owner_type'  => $type,
                        'description' => $r['description']       ?? '',
                        'url'         => $r['html_url']          ?? '',
                        'homepage'    => $r['homepage']          ?? '',
                        'language'    => $r['language']          ?? '',
                        'stars'       => (int) ($r['stargazers_count'] ?? 0),
                        'forks'       => (int) ($r['forks_count'] ?? 0),
                        'open_issues' => (int) ($r['open_issues_count'] ?? 0),
                        'updated_at'  => $r['updated_at']        ?? '',
                        'topics'      => $r['topics']            ?? [],
                        'license'     => $r['license']['spdx_id'] ?? null,
                        'is_fork'     => false,
                    ];
                })
                ->values()
                ->all();
        } catch (\Throwable $e) {
            logger()->warning('[github] fetch failed', [
                'account' => $account,
                'error'   => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * User или Organization. Кэш 1 день.
     */
    protected function detectAccountType(string $login): string
    {
        return Cache::remember("github.type.v2.{$login}", 86400, function () use ($login) {
            try {
                $resp = $this->client()->get("https://api.github.com/users/{$login}");
                if ($resp->ok()) {
                    return $resp->json()['type'] ?? 'User';
                }
            } catch (\Throwable $e) {}
            return 'User';
        });
    }

    /**
     * Общее количество публичных репозиториев.
     */
    public function repoCount(): int
    {
        $repos = $this->repos(100);
        return count($repos);
    }

    /**
     * Сумма звёзд по всем публичным репам.
     */
    public function starsTotal(): int
    {
        return (int) collect($this->repos(100))->sum('stars');
    }

    protected function client()
    {
        $http = Http::withHeaders([
            'Accept'     => 'application/vnd.github+json',
            'User-Agent' => 'SharzhukovLab/1.0',
        ])->timeout(10);

        if ($this->token) {
            $http = $http->withToken($this->token);
        }

        return $http;
    }

    /**
     * Очистить весь кэш github.*
     */
    public function clearCache(): void
    {
        foreach ($this->accounts as $acc) {
            Cache::forget("github.type.v2.{$acc}");
        }
        foreach ([30, 50, 100] as $n) {
            Cache::forget('github.repos.v3.' . md5(implode(',', $this->accounts)) . ".{$n}");
        }
        Cache::forget('products.github.v1');
        Cache::forget('home.stats.v2');
    }
}
