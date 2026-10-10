<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GithubService
{
    protected string $org;
    protected ?string $token;
    protected int $ttl;

    public function __construct()
    {
        $this->org   = config('services.github.org', 'SharzhukovLab');
        $this->token = config('services.github.token');   // null → unauthenticated
        $this->ttl   = (int) config('services.github.cache_ttl', 3600);
    }

    /**
     * Список репозиториев организации.
     * Кешируется на $ttl секунд.
     */
    public function repos(int $limit = 30): array
    {
        $cacheKey = "github.repos.{$this->org}.{$limit}";

        return Cache::remember($cacheKey, $this->ttl, function () use ($limit) {
            $url = "https://api.github.com/orgs/{$this->org}/repos";

            $http = Http::withHeaders([
                'Accept'     => 'application/vnd.github+json',
                'User-Agent' => 'SharzhukovLab/1.0',
            ])->timeout(8);

            if ($this->token) {
                $http = $http->withToken($this->token);
            }

            try {
                $resp = $http->get($url, [
                    'per_page' => $limit,
                    'sort'     => 'updated',
                    'type'     => 'public',
                ]);

                if (!$resp->ok()) {
                    return [];
                }

                $repos = $resp->json();

                return collect($repos)->map(function ($r) {
                    return [
                        'name'        => $r['name']            ?? '',
                        'full_name'   => $r['full_name']       ?? '',
                        'description' => $r['description']     ?? '',
                        'url'         => $r['html_url']        ?? '',
                        'homepage'    => $r['homepage']        ?? '',
                        'language'    => $r['language']        ?? '',
                        'stars'       => $r['stargazers_count']?? 0,
                        'forks'       => $r['forks_count']     ?? 0,
                        'open_issues' => $r['open_issues_count'] ?? 0,
                        'updated_at'  => $r['updated_at']      ?? '',
                        'topics'      => $r['topics']          ?? [],
                        'is_fork'     => (bool)($r['fork']     ?? false),
                    ];
                })
                ->reject(fn ($r) => $r['is_fork'])
                ->values()
                ->all();
            } catch (\Throwable $e) {
                logger()->warning('[github] API failed', ['error' => $e->getMessage()]);
                return [];
            }
        });
    }

    public function repoCount(): int
    {
        $cacheKey = "github.repos.count.{$this->org}";
        return Cache::remember($cacheKey, $this->ttl, function () {
            $url = "https://api.github.com/orgs/{$this->org}";

            $http = Http::withHeaders([
                'Accept'     => 'application/vnd.github+json',
                'User-Agent' => 'SharzhukovLab/1.0',
            ])->timeout(8);

            if ($this->token) {
                $http = $http->withToken($this->token);
            }

            try {
                $resp = $http->get($url);
                return $resp->ok() ? (int) ($resp->json()['public_repos'] ?? 0) : 0;
            } catch (\Throwable $e) {
                return 0;
            }
        });
    }
}
