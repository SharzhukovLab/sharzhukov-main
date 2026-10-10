<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\GithubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $source = $request->get('source', 'all');

        // ── Local products (curated) ──
        $localProducts = Product::query()
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // ── GitHub repos (live) ──
        $githubRepos = [];
        if ($source === 'all' || $source === 'github') {
            try {
                $githubRepos = Cache::remember('products.github.v1', 1800, function () {
                    return app(GithubService::class)->repos(50);
                });
            } catch (\Throwable $e) {
                logger()->warning('[products] github failed', ['error' => $e->getMessage()]);
            }
        }

        // ── Filtering by source ──
        if ($source === 'manual') {
            $githubRepos = [];
        } elseif ($source === 'github') {
            $localProducts = collect();
        }

        $stats = [
            'local'  => $localProducts->count(),
            'github' => count($githubRepos),
            'total'  => $localProducts->count() + count($githubRepos),
        ];

        return view('products', compact('localProducts', 'githubRepos', 'source', 'stats'));
    }
}
